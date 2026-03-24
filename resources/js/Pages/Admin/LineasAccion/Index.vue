<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    lineas:      { type: Array, default: () => [] },
    ejes:        { type: Array, default: () => [] },
    estrategias: { type: Array, default: () => [] },
    plazos:      { type: Array, default: () => [] },
    frecuencias: { type: Array, default: () => [] },
    usuarios:    { type: Array, default: () => [] },
})

// ── Estado modal ──────────────────────────────────────────────────────────────
const modal       = ref(false)
const lineaActual = ref(null)

const form = useForm({
    id_eje:              '',
    id_objetivo:         '',
    id_prioridad:        '',
    id_estrategia:       '',
    id_plazo:            '',
    id_frecuencia:       '',
    id_usuario:          '',
    meta:                '',
    unidad_medida:       '',
    avance_cuantitativo: 0,
    activo:              true,
})

// ── Cascade ───────────────────────────────────────────────────────────────────
const objetivos   = ref([])
const prioridades = ref([])
const loadingObj  = ref(false)
const loadingPri  = ref(false)
const cargandoEdicion = ref(false)

const cargarObjetivos = async (id_eje) => {
    if (!id_eje) { objetivos.value = []; prioridades.value = []; return }
    loadingObj.value = true
    try {
        const res = await fetch(route('admin.lineas.cascade.objetivos', id_eje))
        objetivos.value = await res.json()
    } finally {
        loadingObj.value = false
    }
}

const cargarPrioridades = async (id_objetivo) => {
    if (!id_objetivo) { prioridades.value = []; return }
    loadingPri.value = true
    try {
        const res = await fetch(route('admin.lineas.cascade.prioridades', id_objetivo))
        prioridades.value = await res.json()
    } finally {
        loadingPri.value = false
    }
}

// Watches solo activos cuando NO estamos cargando una edición
watch(() => form.id_eje, (val) => {
    if (cargandoEdicion.value) return
    form.id_objetivo  = ''
    form.id_prioridad = ''
    prioridades.value = []
    cargarObjetivos(val)
})

watch(() => form.id_objetivo, (val) => {
    if (cargandoEdicion.value) return
    form.id_prioridad = ''
    cargarPrioridades(val)
})

// ── Acciones ──────────────────────────────────────────────────────────────────
const abrirCrear = () => {
    form.reset(); form.clearErrors()
    objetivos.value = []; prioridades.value = []
    lineaActual.value = null
    modal.value = 'crear'
}

const abrirEditar = async (linea) => {
    form.clearErrors()
    lineaActual.value = linea
    cargandoEdicion.value = true

    // Cargar cascade primero sin que los watches interfieran
    await cargarObjetivos(linea.id_eje)
    await cargarPrioridades(linea.id_objetivo)

    // Llenar form después de cargar
    form.id_eje              = linea.id_eje
    form.id_objetivo         = linea.id_objetivo
    form.id_prioridad        = linea.id_prioridad
    form.id_estrategia       = linea.id_estrategia ?? ''
    form.id_plazo            = linea.id_plazo ?? ''
    form.id_frecuencia       = linea.id_frecuencia ?? ''
    form.id_usuario          = linea.id_usuario
    form.meta                = linea.meta ?? ''
    form.unidad_medida       = linea.unidad_medida ?? ''
    form.avance_cuantitativo = linea.avance_cuantitativo ?? 0
    form.activo              = linea.activo

    cargandoEdicion.value = false
    modal.value = 'editar'
}

const abrirEliminar = (linea) => {
    lineaActual.value = linea
    modal.value = 'eliminar'
}

const cerrar = () => {
    modal.value = false
    lineaActual.value = null
    form.reset(); form.clearErrors()
    objetivos.value = []; prioridades.value = []
}

const guardar = () => {
    if (modal.value === 'crear') {
        form.post(route('admin.lineas.store'), { onSuccess: cerrar })
    } else {
        form.put(route('admin.lineas.update', lineaActual.value.id), { onSuccess: cerrar })
    }
}

const confirmarEliminar = () => {
    router.delete(route('admin.lineas.destroy', lineaActual.value.id), { onSuccess: cerrar })
}

// ── Filtros y paginación ──────────────────────────────────────────────────────
const busqueda  = ref('')
const filtroEje = ref('')

const lineasFiltradas = computed(() =>
    props.lineas.filter(l => {
        const matchEje = !filtroEje.value || l.id_eje == filtroEje.value
        const q = busqueda.value.toLowerCase()
        const matchQ = !q ||
            l.prioridad?.toLowerCase().includes(q)  ||
            l.organismo?.toLowerCase().includes(q)  ||
            l.usuario?.toLowerCase().includes(q)    ||
            String(l.numero_prioridad).includes(q)
        return matchEje && matchQ
    })
)

const POR_PAGINA   = 15
const pagina       = ref(1)
const totalPaginas = computed(() => Math.ceil(lineasFiltradas.value.length / POR_PAGINA))
const lineasPagina = computed(() => {
    const ini = (pagina.value - 1) * POR_PAGINA
    return lineasFiltradas.value.slice(ini, ini + POR_PAGINA)
})
watch([busqueda, filtroEje], () => { pagina.value = 1 })

const truncar = (texto, max = 70) =>
    texto?.length > max ? texto.substring(0, max) + '...' : texto
</script>

<template>
    <AdminLayout>
        <div class="page">

            <div class="page-head">
                <div>
                    <p class="page-breadcrumb">PI-PEA</p>
                    <h1 class="page-title">Líneas de acción</h1>
                </div>
                <button class="btn btn-primary" @click="abrirCrear">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Nueva línea
                </button>
            </div>

            <!-- Filtros -->
            <div class="filters">
                <div class="search-bar">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;color:var(--color-gris-400)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input v-model="busqueda" type="text" placeholder="Buscar por prioridad, organismo o responsable..." class="search-input"/>
                    <span class="search-count">{{ lineasFiltradas.length }} de {{ lineas.length }}</span>
                </div>
                <select v-model="filtroEje" class="field-input select-filtro">
                    <option value="">Todos los ejes</option>
                    <option v-for="eje in ejes" :key="eje.id" :value="eje.id">
                        Eje {{ String(eje.numero_eje).padStart(2,'0') }} — {{ eje.eje }}
                    </option>
                </select>
            </div>

            <!-- Tabla -->
            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                    <tr>
                        <th class="th th--sm">Eje</th>
                        <th class="th th--sm">Obj.</th>
                        <th class="th th--sm">Prior.</th>
                        <th class="th">Descripción de la prioridad</th>
                        <th class="th">Organismo / Responsable</th>
                        <th class="th th--center">Avance</th>
                        <th class="th th--center">Estado</th>
                        <th class="th th--center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="lineasPagina.length === 0">
                        <td colspan="8" class="tabla-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--color-gris-300)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p>{{ busqueda || filtroEje ? 'Sin resultados para los filtros aplicados.' : 'No hay líneas de acción registradas.' }}</p>
                            <button v-if="!busqueda && !filtroEje" class="btn btn-secondary btn-sm" @click="abrirCrear">Crear primera línea</button>
                        </td>
                    </tr>
                    <tr v-for="l in lineasPagina" :key="l.id" class="tr">
                        <td class="td td--center">
                            <span class="badge-num badge-num--vino">{{ String(l.numero_eje).padStart(2,'0') }}</span>
                        </td>
                        <td class="td td--center">
                            <span class="badge-num badge-num--verde">{{ String(l.numero_objetivo).padStart(2,'0') }}</span>
                        </td>
                        <td class="td td--center">
                            <span class="badge-num badge-num--magenta">{{ String(l.numero_prioridad).padStart(2,'0') }}</span>
                        </td>
                        <td class="td td--desc" :title="l.prioridad">{{ truncar(l.prioridad) }}</td>
                        <td class="td">
                            <p class="org-nombre">{{ l.organismo }}</p>
                            <p class="org-usuario">{{ l.usuario }}</p>
                        </td>
                        <td class="td td--center">
                            <div class="avance-wrap">
                                <div class="avance-bar">
                                    <div class="avance-fill" :style="{ width: Math.min(l.porcentaje_avance, 100) + '%' }"></div>
                                </div>
                                <span class="avance-pct">{{ l.porcentaje_avance }}%</span>
                            </div>
                        </td>
                        <td class="td td--center">
                                <span :class="['badge-estado', l.activo ? 'badge-estado--activo' : 'badge-estado--inactivo']">
                                    {{ l.activo ? 'Activa' : 'Inactiva' }}
                                </span>
                        </td>
                        <td class="td td--center td--acciones">
                            <button class="btn-ico btn-ico--edit" @click="abrirEditar(l)" title="Editar">
                                <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </button>
                            <button class="btn-ico btn-ico--delete" @click="abrirEliminar(l)" title="Eliminar">
                                <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="totalPaginas > 1" class="paginacion">
                <button class="pag-btn" :disabled="pagina === 1" @click="pagina--">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>
                <span class="pag-info">Página {{ pagina }} de {{ totalPaginas }}</span>
                <button class="pag-btn" :disabled="pagina === totalPaginas" @click="pagina++">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </button>
            </div>

        </div>

        <!-- ── MODAL CREAR / EDITAR ──────────────────────────────────────────── -->
        <Teleport to="body">
            <div v-if="modal === 'crear' || modal === 'editar'" class="modal-overlay" @click.self="cerrar">
                <div class="modal modal--lg">

                    <div class="modal-head">
                        <h2 class="modal-title">{{ modal === 'crear' ? 'Nueva línea de acción' : 'Editar línea de acción' }}</h2>
                        <button class="modal-close" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    <div class="modal-body">

                        <p class="seccion-label">Clasificación PI-PEA</p>

                        <!-- Eje -->
                        <div class="field">
                            <label class="field-label">Eje <span class="field-req">*</span></label>
                            <select v-model="form.id_eje" class="field-input" :class="{ 'field-input--error': form.errors.id_eje }">
                                <option value="">Selecciona un eje...</option>
                                <option v-for="eje in ejes" :key="eje.id" :value="eje.id">
                                    Eje {{ String(eje.numero_eje).padStart(2,'0') }} — {{ eje.eje }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_eje" class="field-error">{{ form.errors.id_eje }}</p>
                        </div>

                        <!-- Objetivo -->
                        <div class="field">
                            <label class="field-label">Objetivo específico <span class="field-req">*</span></label>
                            <select v-model="form.id_objetivo" class="field-input" :class="{ 'field-input--error': form.errors.id_objetivo }" :disabled="!form.id_eje || loadingObj">
                                <option value="">{{ !form.id_eje ? 'Primero selecciona un eje' : loadingObj ? 'Cargando...' : 'Selecciona un objetivo...' }}</option>
                                <option v-for="obj in objetivos" :key="obj.id" :value="obj.id">
                                    Obj. {{ String(obj.numero_objetivo).padStart(2,'0') }} — {{ obj.objetivo }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_objetivo" class="field-error">{{ form.errors.id_objetivo }}</p>
                        </div>

                        <!-- Prioridad -->
                        <div class="field">
                            <label class="field-label">Prioridad <span class="field-req">*</span></label>
                            <select v-model="form.id_prioridad" class="field-input" :class="{ 'field-input--error': form.errors.id_prioridad }" :disabled="!form.id_objetivo || loadingPri">
                                <option value="">{{ !form.id_objetivo ? 'Primero selecciona un objetivo' : loadingPri ? 'Cargando...' : 'Selecciona una prioridad...' }}</option>
                                <option v-for="p in prioridades" :key="p.id" :value="p.id">
                                    {{ String(p.numero).padStart(2,'0') }} — {{ p.prioridad }}
                                </option>
                            </select>
                            <p v-if="form.errors.id_prioridad" class="field-error">{{ form.errors.id_prioridad }}</p>
                        </div>

                        <!-- Estrategia + Plazo -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">Estrategia</label>
                                <select v-model="form.id_estrategia" class="field-input">
                                    <option value="">Sin estrategia</option>
                                    <option v-for="e in estrategias" :key="e.id" :value="e.id">{{ e.estrategia }}</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field-label">Plazo</label>
                                <select v-model="form.id_plazo" class="field-input">
                                    <option value="">Sin plazo</option>
                                    <option v-for="p in plazos" :key="p.id" :value="p.id">{{ p.plazo }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Frecuencia -->
                        <div class="field">
                            <label class="field-label">Frecuencia de medición</label>
                            <select v-model="form.id_frecuencia" class="field-input">
                                <option value="">Sin frecuencia</option>
                                <option v-for="f in frecuencias" :key="f.id" :value="f.id">{{ f.frecuencia }}</option>
                            </select>
                        </div>

                        <div class="seccion-sep"></div>
                        <p class="seccion-label">Responsabilidad y medición</p>

                        <!-- Responsable -->
                        <div class="field">
                            <label class="field-label">Responsable <span class="field-req">*</span></label>
                            <select v-model="form.id_usuario" class="field-input" :class="{ 'field-input--error': form.errors.id_usuario }">
                                <option value="">Selecciona un responsable...</option>
                                <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.label }}</option>
                            </select>
                            <p v-if="form.errors.id_usuario" class="field-error">{{ form.errors.id_usuario }}</p>
                        </div>

                        <!-- Meta + Unidad -->
                        <div class="field-row">
                            <div class="field">
                                <label class="field-label">Meta</label>
                                <input v-model="form.meta" type="number" min="0" step="0.01" class="field-input" :class="{ 'field-input--error': form.errors.meta }" placeholder="Ej. 100"/>
                                <p v-if="form.errors.meta" class="field-error">{{ form.errors.meta }}</p>
                            </div>
                            <div class="field">
                                <label class="field-label">Unidad de medida</label>
                                <input v-model="form.unidad_medida" type="text" class="field-input" placeholder="Ej. %, acciones, documentos"/>
                            </div>
                        </div>

                        <!-- Avance solo en edición -->
                        <div v-if="modal === 'editar'" class="field">
                            <label class="field-label">Avance cuantitativo</label>
                            <input v-model="form.avance_cuantitativo" type="number" min="0" step="0.01" class="field-input" placeholder="0"/>
                            <p class="field-hint">Ingresa el avance en la misma unidad que la meta.</p>
                        </div>

                        <!-- Activo -->
                        <div class="field-check">
                            <label class="check-wrap">
                                <input type="checkbox" v-model="form.activo" class="check-input"/>
                                <span class="check-label">Línea activa</span>
                            </label>
                        </div>

                    </div>

                    <div class="modal-foot">
                        <button class="btn btn-ghost" @click="cerrar" :disabled="form.processing">Cancelar</button>
                        <button class="btn btn-primary" @click="guardar" :disabled="form.processing">
                            <svg v-if="form.processing" class="btn-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            {{ form.processing ? 'Guardando...' : (modal === 'crear' ? 'Crear línea' : 'Guardar cambios') }}
                        </button>
                    </div>

                </div>
            </div>
        </Teleport>

        <!-- ── MODAL ELIMINAR ────────────────────────────────────────────────── -->
        <Teleport to="body">
            <div v-if="modal === 'eliminar'" class="modal-overlay" @click.self="cerrar">
                <div class="modal modal--sm">
                    <div class="modal-head modal-head--danger">
                        <h2 class="modal-title modal-title--danger">Eliminar línea de acción</h2>
                        <button class="modal-close modal-close--danger" @click="cerrar">
                            <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-warn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:44px;height:44px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        </div>
                        <p class="delete-msg">¿Eliminar la línea de acción de la <strong>Prioridad {{ lineaActual?.numero_prioridad }}</strong>?</p>
                        <p class="delete-sub">Si tiene historial de avances registrado, no podrá eliminarse.</p>
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
.page { max-width: 1300px; }
.page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
.page-breadcrumb { font-size: var(--text-xs); color: var(--color-gris-400); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.2rem; }
.page-title { font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-gris-800); }

.filters { display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap; }
.search-bar { flex: 1; min-width: 220px; display: flex; align-items: center; gap: 0.75rem; background: var(--color-blanco); border: 1px solid var(--color-gris-200); border-radius: var(--radius-md); padding: 0.6rem 1rem; box-shadow: var(--shadow-sm); }
.search-input { flex: 1; border: none; outline: none; font-size: var(--text-sm); color: var(--color-gris-700); background: transparent; font-family: var(--font-body); }
.search-input::placeholder { color: var(--color-gris-400); }
.search-count { font-size: var(--text-xs); color: var(--color-gris-400); white-space: nowrap; }
.select-filtro { min-width: 200px; flex-shrink: 0; }

.tabla-wrap { background: var(--color-blanco); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); overflow: hidden; }
.tabla { width: 100%; border-collapse: collapse; }
.th { padding: 0.75rem 1rem; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-gris-500); background: var(--color-gris-100); border-bottom: 1px solid var(--color-gris-200); text-align: left; }
.th--sm     { width: 64px; text-align: center; }
.th--center { text-align: center; }
.tr { border-bottom: 1px solid var(--color-gris-200); transition: background var(--transition-base); }
.tr:last-child { border-bottom: none; }
.tr:hover { background: var(--color-gris-100); }
.td { padding: 0.75rem 1rem; font-size: var(--text-sm); color: var(--color-gris-700); vertical-align: middle; }
.td--center  { text-align: center; }
.td--desc    { color: var(--color-gris-800); line-height: 1.5; max-width: 320px; }
.td--acciones { white-space: nowrap; }
.tabla-empty { padding: 3rem 1rem; text-align: center; color: var(--color-gris-500); font-size: var(--text-sm); display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }

.badge-num { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-md); font-family: var(--font-display); font-size: var(--text-xs); font-weight: 700; }
.badge-num--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.badge-num--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.badge-num--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }

.org-nombre  { font-weight: 600; color: var(--color-gris-800); font-size: var(--text-sm); }
.org-usuario { font-size: var(--text-xs); color: var(--color-gris-500); margin-top: 2px; }

.avance-wrap { display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 80px; }
.avance-bar  { width: 70px; height: 6px; background: var(--color-gris-200); border-radius: var(--radius-full); overflow: hidden; }
.avance-fill { height: 100%; background: var(--color-verde); border-radius: var(--radius-full); transition: width 0.4s ease; }
.avance-pct  { font-size: var(--text-xs); font-weight: 700; color: var(--color-gris-600); }

.badge-estado { display: inline-flex; align-items: center; padding: 0.2rem 0.65rem; border-radius: var(--radius-full); font-size: var(--text-xs); font-weight: 700; }
.badge-estado--activo   { background: var(--color-verde-lt); color: var(--color-verde); }
.badge-estado--inactivo { background: var(--color-gris-200); color: var(--color-gris-500); }

.btn-ico { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: var(--radius-md); border: 1px solid var(--color-gris-200); background: transparent; cursor: pointer; transition: background var(--transition-base), color var(--transition-base), border-color var(--transition-base); margin: 0 2px; }
.btn-ico--edit   { color: var(--color-gris-500); }
.btn-ico--delete { color: var(--color-gris-500); }
.btn-ico--edit:hover   { background: var(--color-verde-lt); color: var(--color-verde); border-color: var(--color-verde); }
.btn-ico--delete:hover { background: var(--color-vino-lt);  color: var(--color-vino);  border-color: var(--color-vino); }

.paginacion { display: flex; align-items: center; justify-content: center; gap: 1rem; margin-top: 1rem; }
.pag-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: var(--radius-md); border: 1px solid var(--color-gris-200); background: var(--color-blanco); cursor: pointer; color: var(--color-gris-600); transition: background var(--transition-base); }
.pag-btn:hover:not(:disabled) { background: var(--color-gris-100); }
.pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.pag-info { font-size: var(--text-sm); color: var(--color-gris-500); }

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

.field       { display: flex; flex-direction: column; gap: 0.35rem; }
.field-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.field-label { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); }
.field-req   { color: var(--color-vino); }
.field-hint  { font-size: var(--text-xs); color: var(--color-gris-400); }
.field-error { font-size: var(--text-xs); color: var(--color-vino); font-weight: 600; }
.field-check { display: flex; flex-direction: column; gap: 0.3rem; }
.check-wrap  { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
.check-input { width: 16px; height: 16px; accent-color: var(--color-verde); cursor: pointer; }
.check-label { font-size: var(--text-sm); font-weight: 500; color: var(--color-gris-700); }

.btn-spin { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.delete-warn { display: flex; justify-content: center; color: var(--color-vino); }
.delete-msg  { text-align: center; font-size: var(--text-sm); color: var(--color-gris-700); line-height: 1.6; }
.delete-sub  { text-align: center; font-size: var(--text-xs); color: var(--color-gris-400); }

.field-input:disabled { background: var(--color-gris-100); color: var(--color-gris-400); cursor: not-allowed; }
</style>
