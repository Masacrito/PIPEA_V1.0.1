<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

const props = defineProps({
    stats:          { type: Object, default: () => ({ organismos: 0, usuarios: 0, lineas: 0, avance: 0 }) },
    ultimosAvances: { type: Array,  default: () => [] },
})

const page    = usePage()
const usuario = computed(() => page.props.auth?.user)
const rol     = computed(() => usuario.value?.rol?.rol)

const rolLabel = computed(() => ({
    SUPER_ADMIN:       'Super Admin',
    ADMIN_DEPENDENCIA: 'Admin Dependencia',
}[rol.value] ?? rol.value))

const saludo = computed(() => {
    const h = new Date().getHours()
    if (h < 12) return 'Buenos días'
    if (h < 19) return 'Buenas tardes'
    return 'Buenas noches'
})

const tarjetas = computed(() => [
    { label: 'Organismos registrados', valor: props.stats.organismos, color: 'verde',   ico: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { label: 'Usuarios activos',       valor: props.stats.usuarios,   color: 'vino',    ico: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
    { label: 'Líneas de acción',       valor: props.stats.lineas,     color: 'magenta', ico: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
    { label: 'Avance general PI-PEA',  valor: `${props.stats.avance}%`, color: 'arena', ico: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
])

const safeRoute = (name) => { try { return route(name) } catch { return null } }

const accesos = computed(() => [
    { label: 'Líneas de acción',          sub: 'Ver y gestionar el PI-PEA',       color: 'vino',    ruta: safeRoute('admin.lineas.index'),                    ico: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
    { label: 'Catálogos PI-PEA',          sub: 'Ejes, objetivos y prioridades',   color: 'verde',   ruta: safeRoute('admin.catalogos.ejes.index'),            ico: 'M4 6h16M4 10h16M4 14h16M4 18h16' },
    { label: 'Organismos implementadores',sub: 'Alta y gestión de dependencias',  color: 'magenta', ruta: safeRoute('admin.organismos.index'),                ico: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5' },
    { label: 'Usuarios del sistema',      sub: 'Crear y administrar cuentas',     color: 'arena',   ruta: safeRoute('admin.usuarios.index'),                  ico: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197' },
].filter(a => a.ruta !== null))
</script>

<template>
    <AdminLayout>
        <div class="dash">

            <div class="dash-head">
                <div>
                    <p class="dash-saludo">{{ saludo }}, {{ usuario?.nombre }}</p>
                    <h1 class="dash-title">Dashboard</h1>
                </div>
                <span :class="['badge-rol', `badge-rol--${rol?.toLowerCase()}`]">{{ rolLabel }}</span>
            </div>

            <!-- Tarjetas -->
            <div class="dash-grid">
                <div v-for="t in tarjetas" :key="t.label" class="stat-card" :class="`stat-card--${t.color}`">
                    <div class="stat-card-top">
                        <div class="stat-ico" :class="`stat-ico--${t.color}`">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path :d="t.ico"/>
                            </svg>
                        </div>
                        <p class="stat-valor">{{ t.valor }}</p>
                    </div>
                    <p class="stat-label">{{ t.label }}</p>
                </div>
            </div>

            <div class="dash-body">

                <!-- Accesos rápidos -->
                <div class="dash-panel">
                    <h2 class="dash-panel-title">Accesos rápidos</h2>
                    <div v-if="accesos.length === 0" class="dash-empty">
                        <p>Los módulos aparecerán aquí conforme se habiliten.</p>
                    </div>
                    <div v-else class="quick-list">
                        <Link v-for="a in accesos" :key="a.label" :href="a.ruta" class="quick-item">
                            <div class="quick-ico" :class="`quick-ico--${a.color}`">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="a.ico"/>
                                </svg>
                            </div>
                            <div>
                                <p class="quick-label">{{ a.label }}</p>
                                <p class="quick-sub">{{ a.sub }}</p>
                            </div>
                            <svg class="quick-arrow" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Actividad reciente -->
                <div class="dash-panel">
                    <h2 class="dash-panel-title">Avances recientes</h2>
                    <div v-if="ultimosAvances.length === 0" class="dash-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;color:var(--color-gris-300);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Sin avances registrados aún.</p>
                        <p class="dash-empty-sub">Los avances aparecerán aquí conforme los organismos los registren.</p>
                    </div>
                    <div v-else class="avance-list">
                        <div v-for="avance in ultimosAvances" :key="avance.id" class="avance-item">
                            <div class="avance-dot"></div>
                            <div class="avance-info">
                                <p class="avance-organismo">{{ avance.organismo }}</p>
                                <p class="avance-linea">{{ avance.linea }}</p>
                            </div>
                            <span class="avance-pct">{{ avance.porcentaje }}%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.dash { max-width: 1200px; }
.dash-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
.dash-saludo { font-size: var(--text-sm); color: var(--color-gris-500); margin-bottom: 0.2rem; }
.dash-title { font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-gris-800); }
.dash-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
.stat-card { background: var(--color-blanco); border-radius: var(--radius-lg); padding: 1.35rem; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gris-200); border-top: 3px solid transparent; transition: box-shadow var(--transition-base), transform var(--transition-base); display: flex; flex-direction: column; gap: 0.5rem; }
.stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
.stat-card--verde   { border-top-color: var(--color-verde); }
.stat-card--vino    { border-top-color: var(--color-vino); }
.stat-card--magenta { border-top-color: var(--color-magenta); }
.stat-card--arena   { border-top-color: var(--color-arena-dk); }
.stat-card-top { display: flex; align-items: center; justify-content: space-between; }
.stat-ico { width: 42px; height: 42px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; }
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
.quick-item { display: flex; align-items: center; gap: 1rem; padding: 0.85rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--color-gris-200); transition: background var(--transition-base), border-color var(--transition-base), transform var(--transition-base); }
.quick-item:hover { background: var(--color-gris-100); border-color: var(--color-gris-300); transform: translateX(3px); }
.quick-ico { width: 40px; height: 40px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.quick-ico svg { width: 20px; height: 20px; }
.quick-ico--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.quick-ico--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.quick-ico--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }
.quick-ico--arena   { background: var(--color-gris-200);   color: var(--color-gris-600); }
.quick-label { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); margin-bottom: 0.1rem; }
.quick-sub   { font-size: var(--text-xs); color: var(--color-gris-500); }
.quick-arrow { width: 16px; height: 16px; color: var(--color-gris-400); margin-left: auto; flex-shrink: 0; }
.dash-empty { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 2.5rem 1rem; text-align: center; color: var(--color-gris-500); font-size: var(--text-sm); }
.dash-empty-sub { font-size: var(--text-xs); color: var(--color-gris-400); }
.avance-list { display: flex; flex-direction: column; }
.avance-item { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid var(--color-gris-200); }
.avance-item:last-child { border-bottom: none; }
.avance-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--color-verde); flex-shrink: 0; }
.avance-info { flex: 1; overflow: hidden; }
.avance-organismo { font-size: var(--text-sm); font-weight: 600; color: var(--color-gris-700); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.avance-linea { font-size: var(--text-xs); color: var(--color-gris-500); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.avance-pct { font-family: var(--font-display); font-size: var(--text-sm); font-weight: 700; color: var(--color-verde); flex-shrink: 0; }
@media (max-width: 900px) { .dash-body { grid-template-columns: 1fr; } }
</style>
