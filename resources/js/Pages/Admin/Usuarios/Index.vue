<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    usuarios:   { type: Array, default: () => [] },
    roles:      { type: Array, default: () => [] },
    organismos: { type: Array, default: () => [] },
})

const modal         = ref(false)
const usuarioActual = ref(null)
const verPassword   = ref(false)
const verPassword2  = ref(false)

const form = useForm({
    id_rol:           '',
    id_organismo:     '',
    nombre:           '',
    primer_apellido:  '',
    segundo_apellido: '',
    curp:             '',
    rfc:              '',
    email:            '',
    password:         '',
    password_confirmation: '',
    activo:           true,
})

const abrirCrear = () => {
    form.reset(); form.clearErrors()
    usuarioActual.value = null
    verPassword.value = false; verPassword2.value = false
    modal.value = 'crear'
}

const abrirEditar = (u) => {
    form.id_rol           = u.id_rol
    form.id_organismo     = u.id_organismo
    form.nombre           = u.nombre
    form.primer_apellido  = u.primer_apellido
    form.segundo_apellido = u.segundo_apellido ?? ''
    form.curp             = u.curp ?? ''
    form.rfc              = u.rfc  ?? ''
    form.email            = u.email
    form.password         = ''
    form.password_confirmation = ''
    form.activo           = u.activo
    form.clearErrors()
    usuarioActual.value = u
    verPassword.value = false; verPassword2.value = false
    modal.value = 'editar'
}

const abrirEliminar = (u) => {
    usuarioActual.value = u
    modal.value = 'eliminar'
}

const cerrar = () => {
    modal.value = false
    usuarioActual.value = null
    form.reset(); form.clearErrors()
}

const guardar = () => {
    if (modal.value === 'crear') {
        form.post(route('admin.usuarios.store'), { onSuccess: cerrar })
    } else {
        form.put(route('admin.usuarios.update', usuarioActual.value.id), { onSuccess: cerrar })
    }
}

const confirmarEliminar = () => {
    router.delete(route('admin.usuarios.destroy', usuarioActual.value.id), { onSuccess: cerrar })
}

// Búsqueda y filtro por rol
const busqueda  = ref('')
const filtroRol = ref('')

const usuariosFiltrados = computed(() =>
    props.usuarios.filter(u => {
        const matchRol = !filtroRol.value || u.id_rol == filtroRol.value
        const q = busqueda.value.toLowerCase()
        const matchQ = !q ||
            u.nombre_completo.toLowerCase().includes(q) ||
            u.email.toLowerCase().includes(q) ||
            (u.organismo ?? '').toLowerCase().includes(q)
        return matchRol && matchQ
    })
)

// Badge color por rol
const rolColor = (rol) => {
    if (rol === 'SUPER_ADMIN')       return 'badge-rol--vino'
    if (rol === 'ADMIN_DEPENDENCIA') return 'badge-rol--verde'
    return 'badge-rol--magenta'
}
</script>

<template>
    <AdminLayout>
        <div class="page">

            <div class="page-head">
                <div>
                    <p class="page-breadcrumb">Administración</p>
                    <h1 class="page-title">Usuarios del sistema</h1>
                </div>
                <button class="btn btn-primary" @click="abrirCrear">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Nuevo usuario
                </button>
            </div>

            <!-- Filtros -->
            <div class="filters">
                <div class="search-bar">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;color:var(--color-gris-400)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input v-model="busqueda" type="text" placeholder="Buscar por nombre, correo u organismo..." class="search-input"/>
                    <span class="search-count">{{ usuariosFiltrados.length }} de {{ usuarios.length }}</span>
                </div>
                <select v-model="filtroRol" class="field-input select-filtro">
                    <option value="">Todos los roles</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.rol }}</option>
                </select>
            </div>

            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                    <tr>
                        <th class="th">Nombre</th>
                        <th class="th">Correo</th>
                        <th class="th">Organismo</th>
                        <th class="th th--center">Rol</th>
                        <th class="th th--center">Estado</th>
                        <th class="th th--center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="usuariosFiltrados.length === 0">
                        <td colspan="6" class="tabla-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--color-gris-300)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                            </svg>
                            <p>{{ busqueda || filtroRol ? 'Sin resultados.' : 'No hay usuarios registrados.' }}</p>
                            <button v-if="!busqueda && !filtroRol" class="btn btn-secondary btn-sm" @click="abrirCrear">Crear primer usuario</button>
                        </td>
                    </tr>
                    <tr v-for="u in usuariosFiltrados" :key="u.id" class="tr">
                        <td class="td">
                            <p class="u-nombre">{{ u.nombre_completo }}</p>
                            <p class="u-sub">Registro: {{ u.fecha_registro }}</p>
                        </td>
                        <td class="td td--email">{{ u.email }}</td>
                        <td class="td td--org">{{ u.organismo ?? '—' }}</td>
                        <td class="td td--center">
                            <span :class="['badge-rol', rolColor(u.rol)]">{{ u.rol }}</span>
                        </td>
                        <td class="td td--center">
                                <span :class="['badge-estado', u.activo ? 'badge-estado--activo' : 'badge-estado--inactivo']">
                                    {{ u.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                        </td>
                        <td class="td td--center td--acciones">
                            <template v-if="u.es_superadmin">
                                    <span class="protegido-label" title="Usuario protegido">
                                        <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px;color:var(--color-gris-400)"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                    </span>
                            </template>
                            <template v-else>
                                <button class="btn-ico btn-ico--edit" @click="abrirEditar(u)" title="Editar">
                                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                <button class="btn-ico btn-ico--delete" @click="abrirEliminar(u)" title="Eliminar">
                                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                </button>
                            </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal crear / editar -->
        <Teleport to="body">
            <div v-if="modal === 'crear' || modal === 'editar'" class="modal-overlay" @click.self="cerrar">
                <div class="modal modal--lg">
                    <div class="modal-head">
                        <h2 class="modal-title">{{ modal === 'crear' ? 'Nuevo usuario' : 'Editar usuario' }}</h2>
                        <button class="modal-close" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <div class="modal-body">

                        <p class="seccion-label">Acceso y permisos</p>

                        <!-- Rol + Organismo -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">Rol <span class="field-req">*</span></label>
                                <select v-model="form.id_rol" class="field-input" :class="{ 'field-input--error': form.errors.id_rol }">
                                    <option value="">Selecciona un rol...</option>
                                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.rol }}</option>
                                </select>
                                <p v-if="form.errors.id_rol" class="field-error">{{ form.errors.id_rol }}</p>
                            </div>
                            <div class="field">
                                <label class="field-label">Organismo <span class="field-req">*</span></label>
                                <select v-model="form.id_organismo" class="field-input" :class="{ 'field-input--error': form.errors.id_organismo }">
                                    <option value="">Selecciona un organismo...</option>
                                    <option v-for="o in organismos" :key="o.id" :value="o.id">{{ o.label }}</option>
                                </select>
                                <p v-if="form.errors.id_organismo" class="field-error">{{ form.errors.id_organismo }}</p>
                            </div>
                        </div>

                        <div class="seccion-sep"></div>
                        <p class="seccion-label">Datos personales</p>

                        <!-- Nombre -->
                        <div class="field">
                            <label class="field-label">Nombre(s) <span class="field-req">*</span></label>
                            <input v-model="form.nombre" type="text" class="field-input" :class="{ 'field-input--error': form.errors.nombre }" placeholder="Ej. María Fernanda" autofocus/>
                            <p v-if="form.errors.nombre" class="field-error">{{ form.errors.nombre }}</p>
                        </div>

                        <!-- Apellidos -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">Primer apellido <span class="field-req">*</span></label>
                                <input v-model="form.primer_apellido" type="text" class="field-input" :class="{ 'field-input--error': form.errors.primer_apellido }" placeholder="Ej. García"/>
                                <p v-if="form.errors.primer_apellido" class="field-error">{{ form.errors.primer_apellido }}</p>
                            </div>
                            <div class="field">
                                <label class="field-label">Segundo apellido</label>
                                <input v-model="form.segundo_apellido" type="text" class="field-input" placeholder="Ej. López"/>
                            </div>
                        </div>

                        <!-- CURP + RFC -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">CURP</label>
                                <input v-model="form.curp" type="text" maxlength="18" class="field-input" :class="{ 'field-input--error': form.errors.curp }"
                                       placeholder="18 caracteres" @input="form.curp = form.curp.toUpperCase()"/>
                                <p v-if="form.errors.curp" class="field-error">{{ form.errors.curp }}</p>
                            </div>
                            <div class="field">
                                <label class="field-label">RFC</label>
                                <input v-model="form.rfc" type="text" maxlength="13" class="field-input" :class="{ 'field-input--error': form.errors.rfc }"
                                       placeholder="12-13 caracteres" @input="form.rfc = form.rfc.toUpperCase()"/>
                                <p v-if="form.errors.rfc" class="field-error">{{ form.errors.rfc }}</p>
                            </div>
                        </div>

                        <div class="seccion-sep"></div>
                        <p class="seccion-label">Credenciales de acceso</p>

                        <!-- Email -->
                        <div class="field">
                            <label class="field-label">Correo electrónico <span class="field-req">*</span></label>
                            <input v-model="form.email" type="email" class="field-input" :class="{ 'field-input--error': form.errors.email }" placeholder="correo@dominio.gob.mx"/>
                            <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
                        </div>

                        <!-- Contraseña -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">
                                    Contraseña <span v-if="modal === 'crear'" class="field-req">*</span>
                                    <span v-else class="field-opt">(dejar vacío para no cambiar)</span>
                                </label>
                                <div class="pass-wrap">
                                    <input v-model="form.password" :type="verPassword ? 'text' : 'password'" class="field-input" :class="{ 'field-input--error': form.errors.password }" placeholder="Mínimo 8 caracteres"/>
                                    <button type="button" class="pass-toggle" @click="verPassword = !verPassword">
                                        <svg v-if="!verPassword" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                        <svg v-else viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/></svg>
                                    </button>
                                </div>
                                <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
                            </div>
                            <div class="field">
                                <label class="field-label">Confirmar contraseña</label>
                                <div class="pass-wrap">
                                    <input v-model="form.password_confirmation" :type="verPassword2 ? 'text' : 'password'" class="field-input" placeholder="Repetir contraseña"/>
                                    <button type="button" class="pass-toggle" @click="verPassword2 = !verPassword2">
                                        <svg v-if="!verPassword2" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                        <svg v-else viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Activo -->
                        <div class="field-check">
                            <label class="check-wrap">
                                <input type="checkbox" v-model="form.activo" class="check-input"/>
                                <span class="check-label">Usuario activo</span>
                            </label>
                            <p class="field-hint">Los usuarios inactivos no pueden iniciar sesión.</p>
                        </div>

                    </div>
                    <div class="modal-foot">
                        <button class="btn btn-ghost" @click="cerrar" :disabled="form.processing">Cancelar</button>
                        <button class="btn btn-primary" @click="guardar" :disabled="form.processing">
                            <svg v-if="form.processing" class="btn-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            {{ form.processing ? 'Guardando...' : (modal === 'crear' ? 'Crear usuario' : 'Guardar cambios') }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Modal eliminar -->
        <Teleport to="body">
            <div v-if="modal === 'eliminar'" class="modal-overlay" @click.self="cerrar">
                <div class="modal modal--sm">
                    <div class="modal-head modal-head--danger">
                        <h2 class="modal-title modal-title--danger">Eliminar usuario</h2>
                        <button class="modal-close modal-close--danger" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-warn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:44px;height:44px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        </div>
                        <p class="delete-msg">¿Eliminar a <strong>{{ usuarioActual?.nombre_completo }}</strong>?</p>
                        <p class="delete-sub">No se puede eliminar si tiene líneas de acción asignadas.</p>
                    </div>
                    <div class="modal-foot">
                        <button class="btn btn-ghost" @click="cerrar">Cancelar</button>
                        <button class="btn btn-danger" @click="confirmarEliminar">Sí, eliminar</button>
                    </div>
                </div>
            </div>
        </Teleport>

    </AdminLayout>
</template>

<style scoped>
.page { max-width: 1100px; }
.page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
.page-breadcrumb { font-size: var(--text-xs); color: var(--color-gris-400); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.2rem; }
.page-title { font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-gris-800); }

.filters { display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap; }
.search-bar { flex: 1; min-width: 220px; display: flex; align-items: center; gap: 0.75rem; background: var(--color-blanco); border: 1px solid var(--color-gris-200); border-radius: var(--radius-md); padding: 0.6rem 1rem; box-shadow: var(--shadow-sm); }
.search-input { flex: 1; border: none; outline: none; font-size: var(--text-sm); color: var(--color-gris-700); background: transparent; font-family: var(--font-body); }
.search-input::placeholder { color: var(--color-gris-400); }
.search-count { font-size: var(--text-xs); color: var(--color-gris-400); white-space: nowrap; }
.select-filtro { min-width: 180px; flex-shrink: 0; }

.tabla-wrap { background: var(--color-blanco); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); overflow: hidden; }
.tabla { width: 100%; border-collapse: collapse; }
.th { padding: 0.75rem 1rem; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-gris-500); background: var(--color-gris-100); border-bottom: 1px solid var(--color-gris-200); text-align: left; }
.th--center { text-align: center; }
.tr { border-bottom: 1px solid var(--color-gris-200); transition: background var(--transition-base); }
.tr:last-child { border-bottom: none; }
.tr:hover { background: var(--color-gris-100); }
.td { padding: 0.85rem 1rem; font-size: var(--text-sm); color: var(--color-gris-700); vertical-align: middle; }
.td--center  { text-align: center; }
.td--email   { font-size: var(--text-xs); color: var(--color-gris-600); }
.td--org     { font-size: var(--text-xs); font-weight: 600; color: var(--color-gris-700); }
.td--acciones { white-space: nowrap; }
.tabla-empty { padding: 3rem 1rem; text-align: center; color: var(--color-gris-500); font-size: var(--text-sm); display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }

.u-nombre { font-weight: 600; color: var(--color-gris-800); }
.u-sub    { font-size: var(--text-xs); color: var(--color-gris-400); margin-top: 2px; }

.badge-rol { display: inline-flex; align-items: center; padding: 0.2rem 0.55rem; border-radius: var(--radius-sm); font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.04em; }
.badge-rol--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.badge-rol--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.badge-rol--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }

.badge-estado { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: var(--radius-full); font-size: var(--text-xs); font-weight: 700; }
.badge-estado--activo   { background: var(--color-verde-lt); color: var(--color-verde); }
.badge-estado--inactivo { background: var(--color-gris-200); color: var(--color-gris-500); }

.btn-ico { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-md); border: 1px solid var(--color-gris-200); background: transparent; cursor: pointer; transition: background var(--transition-base), color var(--transition-base), border-color var(--transition-base); margin: 0 2px; }
.btn-ico--edit  { color: var(--color-gris-500); }
.btn-ico--delete { color: var(--color-gris-500); }
.btn-ico--edit:hover   { background: var(--color-verde-lt); color: var(--color-verde); border-color: var(--color-verde); }
.btn-ico--delete:hover { background: var(--color-vino-lt);  color: var(--color-vino);  border-color: var(--color-vino); }

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 200; padding: 1rem; }
.modal { background: var(--color-blanco); border-radius: var(--radius-xl); box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 100%; max-width: 560px; animation: modal-in 0.18s ease; max-height: 90vh; display: flex; flex-direction: column; }
.modal--lg { max-width: 640px; }
.modal--sm { max-width: 400px; }
@keyframes modal-in { from { opacity: 0; transform: translateY(-12px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
.modal-head { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-gris-200); flex-shrink: 0; }
.modal-head--danger { border-bottom-color: var(--color-vino-lt); }
.modal-title { font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-gris-800); }
.modal-title--danger { color: var(--color-vino); }
.modal-close { width: 30px; height: 30px; border-radius: var(--radius-md); border: none; background: transparent; cursor: pointer; color: var(--color-gris-400); display: flex; align-items: center; justify-content: center; transition: background var(--transition-base); flex-shrink: 0; }
.modal-close:hover { background: var(--color-gris-200); color: var(--color-gris-700); }
.modal-close--danger:hover { background: var(--color-vino-lt); color: var(--color-vino); }
.modal-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; overflow-y: auto; }
.modal-foot { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid var(--color-gris-200); flex-shrink: 0; }

.seccion-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--color-gris-500); }
.seccion-sep   { border-top: 1px solid var(--color-gris-200); }

.field      { display: flex; flex-direction: column; gap: 0.35rem; }
.field-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.field-label { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); }
.field-req  { color: var(--color-vino); }
.field-opt  { font-size: var(--text-xs); font-weight: 400; color: var(--color-gris-400); }
.field-hint { font-size: var(--text-xs); color: var(--color-gris-400); }
.field-error { font-size: var(--text-xs); color: var(--color-vino); font-weight: 600; }
.field-check { display: flex; flex-direction: column; gap: 0.3rem; }
.check-wrap { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.check-input { width: 16px; height: 16px; accent-color: var(--color-verde); cursor: pointer; }
.check-label { font-size: var(--text-sm); font-weight: 500; color: var(--color-gris-700); }

/* Contraseña con toggle */
.pass-wrap { position: relative; display: flex; align-items: center; }
.pass-wrap .field-input { padding-right: 2.5rem; width: 100%; }
.pass-toggle { position: absolute; right: 0.6rem; background: none; border: none; cursor: pointer; color: var(--color-gris-400); display: flex; align-items: center; padding: 0; }
.pass-toggle:hover { color: var(--color-gris-700); }

.btn-spin { animation: spin 0.8s linear infinite; }
.protegido-label { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; }
@keyframes spin { to { transform: rotate(360deg); } }
.delete-warn { display: flex; justify-content: center; color: var(--color-vino); }
.delete-msg  { text-align: center; font-size: var(--text-sm); color: var(--color-gris-700); line-height: 1.6; }
.delete-sub  { text-align: center; font-size: var(--text-xs); color: var(--color-gris-400); }
</style>
