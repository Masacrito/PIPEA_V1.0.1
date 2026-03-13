<script setup>
import OrganismoLayout from  '@/Layouts/Organismolayout.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            lineasAsignadas:    0,
            avancesRegistrados: 0,
            observaciones:      0,
            porcentajeAvance:   0,
        }),
    },
    ultimosAvances: {
        type: Array,
        default: () => [],
    },
})

const page      = usePage()
const usuario   = computed(() => page.props.auth?.user)
const organismo = computed(() => usuario.value?.organismo?.nombre ?? 'Mi Organismo')

const saludo = computed(() => {
    const hora = new Date().getHours()
    if (hora < 12) return 'Buenos días'
    if (hora < 19) return 'Buenas tardes'
    return 'Buenas noches'
})

const tarjetas = computed(() => [
    {
        label: 'Líneas asignadas',
        valor: props.stats.lineasAsignadas,
        ico:   'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        color: 'verde',
    },
    {
        label: 'Avances registrados',
        valor: props.stats.avancesRegistrados,
        ico:   'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'magenta',
    },
    {
        label: 'Observaciones pendientes',
        valor: props.stats.observaciones,
        ico:   'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',
        color: 'vino',
    },
    {
        label: 'Avance general',
        valor: `${props.stats.porcentajeAvance}%`,
        ico:   'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        color: 'arena',
    },
])

const pctWidth = computed(() => Math.min(100, Math.max(0, props.stats.porcentajeAvance)))
</script>

<template>
    <OrganismoLayout>
        <div class="dash">

            <!-- Encabezado -->
            <div class="dash-head">
                <div>
                    <p class="dash-saludo">{{ saludo }}, {{ usuario?.nombre }}</p>
                    <h1 class="dash-title">{{ organismo }}</h1>
                </div>
                <span class="badge-rol badge-rol--usuario_organismo">
                    Organismo implementador
                </span>
            </div>

            <!-- Barra de avance global -->
            <div class="dash-avance-bar-wrap">
                <div class="dash-avance-bar-head">
                    <span class="dash-avance-bar-label">Avance general del organismo</span>
                    <span class="dash-avance-bar-pct">{{ stats.porcentajeAvance }}%</span>
                </div>
                <div class="dash-avance-bar-track">
                    <div class="dash-avance-bar-fill" :style="{ width: pctWidth + '%' }"></div>
                </div>
            </div>

            <!-- Tarjetas -->
            <div class="dash-grid">
                <div
                    v-for="t in tarjetas" :key="t.label"
                    class="stat-card"
                    :class="`stat-card--${t.color}`"
                >
                    <div class="stat-card-top">
                        <div class="stat-ico" :class="`stat-ico--${t.color}`">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path :d="t.ico"/>
                            </svg>
                        </div>
                        <p class="stat-valor">{{ t.valor }}</p>
                    </div>
                    <p class="stat-label">{{ t.label }}</p>
                </div>
            </div>

            <!-- Cuerpo -->
            <div class="dash-body">

                <!-- Accesos rápidos -->
                <div class="dash-panel">
                    <h2 class="dash-panel-title">Accesos rápidos</h2>
                    <div class="quick-list">

                        <div class="quick-item">
                            <div class="quick-ico quick-ico--verde">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div>
                                <p class="quick-label">Mis líneas de acción</p>
                                <p class="quick-sub">Consultar líneas asignadas a tu organismo</p>
                            </div>
                            <svg class="quick-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="quick-item">
                            <div class="quick-ico quick-ico--magenta">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="quick-label">Registrar avance</p>
                                <p class="quick-sub">Capturar progreso de una línea</p>
                            </div>
                            <svg class="quick-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="quick-item">
                            <div class="quick-ico quick-ico--vino">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <div>
                                <p class="quick-label">Mis evidencias</p>
                                <p class="quick-sub">Documentos y archivos subidos</p>
                            </div>
                            <svg class="quick-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="quick-item">
                            <div class="quick-ico quick-ico--arena">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                                </svg>
                            </div>
                            <div>
                                <p class="quick-label">Mi reporte de avance</p>
                                <p class="quick-sub">Resumen de cumplimiento</p>
                            </div>
                            <svg class="quick-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                    </div>
                </div>

                <!-- Últimos avances -->
                <div class="dash-panel">
                    <h2 class="dash-panel-title">Últimos avances registrados</h2>

                    <div v-if="ultimosAvances.length === 0" class="dash-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                             style="width:40px;height:40px;color:var(--color-gris-300);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Sin avances registrados aún.</p>
                        <p class="dash-empty-sub">Usa el menú lateral para registrar el progreso de tus líneas.</p>
                    </div>

                    <div v-else class="avance-list">
                        <div v-for="avance in ultimosAvances" :key="avance.id" class="avance-item">
                            <div class="avance-dot"></div>
                            <div class="avance-info">
                                <p class="avance-linea">{{ avance.linea }}</p>
                                <p class="avance-fecha">{{ avance.fecha }}</p>
                            </div>
                            <div class="avance-right">
                                <span class="avance-pct">{{ avance.porcentaje }}%</span>
                                <div class="avance-mini-bar">
                                    <div class="avance-mini-fill" :style="{ width: avance.porcentaje + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </OrganismoLayout>
</template>

<style scoped>
.dash { max-width: 1100px; }

.dash-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
.dash-saludo { font-size: var(--text-sm); color: var(--color-gris-500); margin-bottom: 0.2rem; }
.dash-title { font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-gris-800); }

.dash-avance-bar-wrap { background: var(--color-blanco); border-radius: var(--radius-lg); padding: 1.1rem 1.35rem; margin-bottom: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); }
.dash-avance-bar-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem; }
.dash-avance-bar-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-gris-500); }
.dash-avance-bar-pct { font-family: var(--font-display); font-size: var(--text-md); font-weight: 700; color: var(--color-verde); }
.dash-avance-bar-track { height: 8px; background: var(--color-gris-200); border-radius: var(--radius-full); overflow: hidden; }
.dash-avance-bar-fill { height: 100%; background: linear-gradient(90deg, var(--color-verde) 0%, #4ecdc4 100%); border-radius: var(--radius-full); transition: width 0.8s ease; }

.dash-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }

.stat-card { background: var(--color-blanco); border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); border-top: 3px solid transparent; transition: box-shadow var(--transition-base), transform var(--transition-base); display: flex; flex-direction: column; gap: 0.5rem; }
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-card--verde   { border-top-color: var(--color-verde); }
.stat-card--vino    { border-top-color: var(--color-vino); }
.stat-card--magenta { border-top-color: var(--color-magenta); }
.stat-card--arena   { border-top-color: var(--color-arena-dk); }

.stat-card-top { display: flex; align-items: center; justify-content: space-between; }
.stat-ico { width: 40px; height: 40px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
.stat-ico svg { width: 20px; height: 20px; }
.stat-ico--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.stat-ico--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.stat-ico--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }
.stat-ico--arena   { background: var(--color-gris-200);   color: var(--color-gris-600); }
.stat-valor { font-family: var(--font-display); font-size: var(--text-2xl); font-weight: 700; color: var(--color-gris-800); }
.stat-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--color-gris-500); }

.dash-body { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

.dash-panel { background: var(--color-blanco); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); }
.dash-panel-title { font-family: var(--font-display); font-size: var(--text-md); color: var(--color-gris-800); margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--color-gris-200); }

.quick-list { display: flex; flex-direction: column; gap: 0.5rem; }
.quick-item { display: flex; align-items: center; gap: 1rem; padding: 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--color-gris-200); cursor: pointer; transition: background var(--transition-base), border-color var(--transition-base), transform var(--transition-base); }
.quick-item:hover { background: var(--color-gris-100); border-color: var(--color-gris-300); transform: translateX(3px); }
.quick-ico { width: 38px; height: 38px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.quick-ico svg { width: 18px; height: 18px; }
.quick-ico--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.quick-ico--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.quick-ico--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }
.quick-ico--arena   { background: var(--color-gris-200);   color: var(--color-gris-600); }
.quick-label { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); margin-bottom: 0.1rem; }
.quick-sub   { font-size: var(--text-xs); color: var(--color-gris-500); }
.quick-arrow { width: 16px; height: 16px; color: var(--color-gris-400); margin-left: auto; flex-shrink: 0; }

.dash-empty { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 2rem 1rem; text-align: center; color: var(--color-gris-500); font-size: var(--text-sm); }
.dash-empty-sub { font-size: var(--text-xs); color: var(--color-gris-400); }

.avance-list { display: flex; flex-direction: column; }
.avance-item { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid var(--color-gris-200); }
.avance-item:last-child { border-bottom: none; }
.avance-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--color-verde); flex-shrink: 0; }
.avance-info { flex: 1; overflow: hidden; }
.avance-linea { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.avance-fecha { font-size: var(--text-xs); color: var(--color-gris-500); }
.avance-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.3rem; flex-shrink: 0; }
.avance-pct { font-family: var(--font-display); font-size: var(--text-sm); font-weight: 700; color: var(--color-verde); }
.avance-mini-bar { width: 60px; height: 4px; background: var(--color-gris-200); border-radius: var(--radius-full); overflow: hidden; }
.avance-mini-fill { height: 100%; background: var(--color-verde); border-radius: var(--radius-full); }

@media (max-width: 900px) {
    .dash-body { grid-template-columns: 1fr; }
}
</style>
