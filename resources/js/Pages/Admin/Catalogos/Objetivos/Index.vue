<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    objetivos: { type: Array, default: () => [] },
})

const modal          = ref(false)
const objetivoActual = ref(null)
const form           = useForm({ numero_objetivo: '', objetivo: '', activo: true })

const abrirCrear = () => {
    form.reset(); form.clearErrors()
    objetivoActual.value = null
    modal.value = 'crear'
}

const abrirEditar = (obj) => {
    form.numero_objetivo = obj.numero_objetivo
    form.objetivo        = obj.objetivo
    form.activo          = obj.activo
    form.clearErrors()
    objetivoActual.value = obj
    modal.value = 'editar'
}

const abrirEliminar = (obj) => {
    objetivoActual.value = obj
    modal.value = 'eliminar'
}

const cerrar = () => {
    modal.value = false
    objetivoActual.value = null
    form.reset(); form.clearErrors()
}

const guardar = () => {
    if (modal.value === 'crear') {
        form.post(route('admin.catalogos.objetivos.store'), { onSuccess: cerrar })
    } else {
        form.put(route('admin.catalogos.objetivos.update', objetivoActual.value.id), { onSuccess: cerrar })
    }
}

const confirmarEliminar = () => {
    router.delete(route('admin.catalogos.objetivos.destroy', objetivoActual.value.id), { onSuccess: cerrar })
}

const busqueda = ref('')
const objetivosFiltrados = computed(() =>
    props.objetivos.filter(o =>
        o.objetivo.toLowerCase().includes(busqueda.value.toLowerCase()) ||
        String(o.numero_objetivo).includes(busqueda.value)
    )
)

// Truncar texto largo para la tabla
const truncar = (texto, max = 90) =>
    texto?.length > max ? texto.substring(0, max) + '...' : texto
</script>

<template>
    <AdminLayout>
        <div class="page">

            <div class="page-head">
                <div>
                    <p class="page-breadcrumb">Catálogos PI-PEA</p>
                    <h1 class="page-title">Objetivos específicos</h1>
                </div>
                <button class="btn btn-primary" @click="abrirCrear">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Nuevo objetivo
                </button>
            </div>

            <div class="search-bar">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;color:var(--color-gris-400)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input v-model="busqueda" type="text" placeholder="Buscar objetivo..." class="search-input"/>
                <span class="search-count">{{ objetivosFiltrados.length }} de {{ objetivos.length }}</span>
            </div>

            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                    <tr>
                        <th class="th th--num">Obj.</th>
                        <th class="th">Descripción</th>
                        <th class="th th--center">Estado</th>
                        <th class="th th--center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="objetivosFiltrados.length === 0">
                        <td colspan="4" class="tabla-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--color-gris-300)"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p>{{ busqueda ? `Sin resultados para "${busqueda}"` : 'No hay objetivos registrados.' }}</p>
                            <button v-if="!busqueda" class="btn btn-secondary btn-sm" @click="abrirCrear">Crear primer objetivo</button>
                        </td>
                    </tr>
                    <tr v-for="obj in objetivosFiltrados" :key="obj.id" class="tr">
                        <td class="td td--num">
                            <span class="num-badge">{{ String(obj.numero_objetivo).padStart(2, '0') }}</span>
                        </td>
                        <td class="td td--desc" :title="obj.objetivo">{{ truncar(obj.objetivo) }}</td>
                        <td class="td td--center">
                                <span :class="['badge-estado', obj.activo ? 'badge-estado--activo' : 'badge-estado--inactivo']">
                                    {{ obj.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                        </td>
                        <td class="td td--center td--acciones">
                            <button class="btn-ico btn-ico--edit" @click="abrirEditar(obj)" title="Editar">
                                <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </button>
                            <button class="btn-ico btn-ico--delete" @click="abrirEliminar(obj)" title="Eliminar">
                                <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal crear / editar -->
        <Teleport to="body">
            <div v-if="modal === 'crear' || modal === 'editar'" class="modal-overlay" @click.self="cerrar">
                <div class="modal">
                    <div class="modal-head">
                        <h2 class="modal-title">{{ modal === 'crear' ? 'Nuevo objetivo' : 'Editar objetivo' }}</h2>
                        <button class="modal-close" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Número -->
                        <div class="field">
                            <label class="field-label">Número de objetivo <span class="field-req">*</span></label>
                            <input
                                v-model="form.numero_objetivo"
                                type="number" min="1" max="99"
                                class="field-input field-input--sm"
                                :class="{ 'field-input--error': form.errors.numero_objetivo }"
                                placeholder="Ej. 1"
                            />
                            <p v-if="form.errors.numero_objetivo" class="field-error">{{ form.errors.numero_objetivo }}</p>
                        </div>

                        <!-- Descripción -->
                        <div class="field">
                            <label class="field-label">Descripción del objetivo <span class="field-req">*</span></label>
                            <textarea
                                v-model="form.objetivo"
                                class="field-input field-textarea"
                                :class="{ 'field-input--error': form.errors.objetivo }"
                                placeholder="Ej. Promover los mecanismos de coordinación de las autoridades competentes..."
                                rows="4"
                            ></textarea>
                            <p v-if="form.errors.objetivo" class="field-error">{{ form.errors.objetivo }}</p>
                        </div>

                        <!-- Activo -->
                        <div class="field-check">
                            <label class="check-wrap">
                                <input type="checkbox" v-model="form.activo" class="check-input"/>
                                <span class="check-label">Objetivo activo</span>
                            </label>
                            <p class="field-hint">Los objetivos inactivos no aparecen al capturar líneas de acción.</p>
                        </div>

                    </div>
                    <div class="modal-foot">
                        <button class="btn btn-ghost" @click="cerrar" :disabled="form.processing">Cancelar</button>
                        <button class="btn btn-primary" @click="guardar" :disabled="form.processing">
                            <svg v-if="form.processing" class="btn-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            {{ form.processing ? 'Guardando...' : (modal === 'crear' ? 'Crear objetivo' : 'Guardar cambios') }}
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
                        <h2 class="modal-title modal-title--danger">Eliminar objetivo</h2>
                        <button class="modal-close modal-close--danger" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-warn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:44px;height:44px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        </div>
                        <p class="delete-msg">¿Eliminar el <strong>Objetivo Específico {{ objetivoActual?.numero_objetivo }}</strong>?</p>
                        <p class="delete-sub">Esta acción no se puede deshacer. Si tiene líneas de acción asociadas, no podrá eliminarse.</p>
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
.page { max-width: 1000px; }
.page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
.page-breadcrumb { font-size: var(--text-xs); color: var(--color-gris-400); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.2rem; }
.page-title { font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-gris-800); }

.search-bar { display: flex; align-items: center; gap: 0.75rem; background: var(--color-blanco); border: 1px solid var(--color-gris-200); border-radius: var(--radius-md); padding: 0.6rem 1rem; margin-bottom: 1rem; box-shadow: var(--shadow-sm); }
.search-input { flex: 1; border: none; outline: none; font-size: var(--text-sm); color: var(--color-gris-700); background: transparent; font-family: var(--font-body); }
.search-input::placeholder { color: var(--color-gris-400); }
.search-count { font-size: var(--text-xs); color: var(--color-gris-400); white-space: nowrap; }

.tabla-wrap { background: var(--color-blanco); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); overflow: hidden; }
.tabla { width: 100%; border-collapse: collapse; }
.th { padding: 0.75rem 1rem; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-gris-500); background: var(--color-gris-100); border-bottom: 1px solid var(--color-gris-200); text-align: left; }
.th--num    { width: 80px; text-align: center; }
.th--center { text-align: center; }
.tr { border-bottom: 1px solid var(--color-gris-200); transition: background var(--transition-base); }
.tr:last-child { border-bottom: none; }
.tr:hover { background: var(--color-gris-100); }
.td { padding: 0.85rem 1rem; font-size: var(--text-sm); color: var(--color-gris-700); vertical-align: middle; }
.td--num     { text-align: center; }
.td--desc    { color: var(--color-gris-800); line-height: 1.5; }
.td--center  { text-align: center; }
.td--acciones { white-space: nowrap; }
.tabla-empty { padding: 3rem 1rem; text-align: center; color: var(--color-gris-500); font-size: var(--text-sm); display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }

.num-badge { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: var(--radius-md); background: var(--color-verde-lt); color: var(--color-verde); font-family: var(--font-display); font-size: var(--text-sm); font-weight: 700; }

.badge-estado { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: var(--radius-full); font-size: var(--text-xs); font-weight: 700; }
.badge-estado--activo   { background: var(--color-verde-lt); color: var(--color-verde); }
.badge-estado--inactivo { background: var(--color-gris-200); color: var(--color-gris-500); }

.btn-ico { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-md); border: 1px solid var(--color-gris-200); background: transparent; cursor: pointer; transition: background var(--transition-base), color var(--transition-base), border-color var(--transition-base); margin: 0 2px; }
.btn-ico--edit  { color: var(--color-gris-500); }
.btn-ico--delete { color: var(--color-gris-500); }
.btn-ico--edit:hover   { background: var(--color-verde-lt); color: var(--color-verde); border-color: var(--color-verde); }
.btn-ico--delete:hover { background: var(--color-vino-lt);  color: var(--color-vino);  border-color: var(--color-vino); }

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 200; padding: 1rem; }
.modal { background: var(--color-blanco); border-radius: var(--radius-xl); box-shadow: 0 20px 60px rgba(0,0,0,0.2); width: 100%; max-width: 520px; animation: modal-in 0.18s ease; }
.modal--sm { max-width: 400px; }
@keyframes modal-in { from { opacity: 0; transform: translateY(-12px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
.modal-head { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-gris-200); }
.modal-head--danger { border-bottom-color: var(--color-vino-lt); }
.modal-title { font-family: var(--font-display); font-size: var(--text-lg); color: var(--color-gris-800); }
.modal-title--danger { color: var(--color-vino); }
.modal-close { width: 30px; height: 30px; border-radius: var(--radius-md); border: none; background: transparent; cursor: pointer; color: var(--color-gris-400); display: flex; align-items: center; justify-content: center; transition: background var(--transition-base); }
.modal-close:hover { background: var(--color-gris-200); color: var(--color-gris-700); }
.modal-close--danger:hover { background: var(--color-vino-lt); color: var(--color-vino); }
.modal-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
.modal-foot { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid var(--color-gris-200); }

.field { display: flex; flex-direction: column; gap: 0.4rem; }
.field-label { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); }
.field-req { color: var(--color-vino); }
.field-hint { font-size: var(--text-xs); color: var(--color-gris-400); margin-top: 0.25rem; }
.field-error { font-size: var(--text-xs); color: var(--color-vino); font-weight: 600; }
.field-input--sm { max-width: 120px; }
.field-textarea { resize: vertical; min-height: 100px; line-height: 1.6; }
.field-check { display: flex; flex-direction: column; gap: 0.3rem; }
.check-wrap { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.check-input { width: 16px; height: 16px; accent-color: var(--color-verde); cursor: pointer; }
.check-label { font-size: var(--text-sm); font-weight: 500; color: var(--color-gris-700); }
.btn-spin { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.delete-warn { display: flex; justify-content: center; color: var(--color-vino); }
.delete-msg { text-align: center; font-size: var(--text-sm); color: var(--color-gris-700); line-height: 1.6; }
.delete-sub { text-align: center; font-size: var(--text-xs); color: var(--color-gris-400); }
</style>
