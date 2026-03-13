<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const page      = usePage()
const usuario   = computed(() => page.props.auth?.user)
const organismo = computed(() => usuario.value?.organismo?.nombre ?? 'Mi Organismo')

const sidebarAbierto = ref(true)
const toggleSidebar  = () => { sidebarAbierto.value = !sidebarAbierto.value }
const cerrarSesion   = () => router.post(route('logout'))

// Helper seguro — si la ruta no existe en Ziggy devuelve '#'
const safeRoute = (name) => {
    try { return route(name) } catch { return '#' }
}

const esActiva = (name) => {
    try { return route().current(name) } catch { return false }
}

const menu = [
    {
        seccion: 'Principal',
        items: [
            { label: 'Dashboard', ruta: 'organismo.dashboard', icono: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
        ],
    },
    {
        seccion: 'Seguimiento',
        items: [
            { label: 'Mis líneas de acción', ruta: 'organismo.lineas.index',  icono: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' },
            { label: 'Registrar avance',     ruta: 'organismo.avances.create', icono: 'M12 4v16m8-8H4' },
            { label: 'Mis evidencias',       ruta: 'organismo.evidencias.index', icono: 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13' },
        ],
    },
    {
        seccion: 'Reportes',
        items: [
            { label: 'Reporte de avance', ruta: 'organismo.reportes.avance', icono: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
        ],
    },
    {
        seccion: 'Cuenta',
        items: [
            { label: 'Mi perfil', ruta: 'organismo.perfil', icono: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
        ],
    },
]
</script>

<template>
    <div class="layout-root">

        <!-- Banner -->
        <header class="banner">
            <div class="banner-inner">
                <div class="banner-side">
                    <img src="/images/banner-sesaech.png" alt="SESAECH" class="banner-img" />
                </div>
                <div class="banner-center">
                    <span class="banner-title">Sistema de Seguimiento y Evaluación de la PEA</span>
                </div>
                <div class="banner-side banner-side--right">
                    <img src="/images/logo-humanismo.png" alt="Humanismo que Transforma" class="banner-img" />
                </div>
            </div>
        </header>

        <div class="layout-body">

            <!-- Sidebar -->
            <aside class="sidebar" :class="{ 'sidebar--collapsed': !sidebarAbierto }">

                <button class="sidebar-toggle" @click="toggleSidebar"
                        :title="sidebarAbierto ? 'Colapsar' : 'Expandir'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path v-if="sidebarAbierto" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                        <path v-else               d="M13 5l7 7-7 7M6 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Usuario -->
                <div class="sidebar-user" :class="{ 'sidebar-user--col': !sidebarAbierto }">
                    <div class="sidebar-avatar">
                        {{ usuario?.nombre?.charAt(0) }}{{ usuario?.primer_apellido?.charAt(0) }}
                    </div>
                    <div class="sidebar-user-info" v-show="sidebarAbierto">
                        <p class="sidebar-user-name">{{ usuario?.nombre }} {{ usuario?.primer_apellido }}</p>
                        <p class="sidebar-user-org">{{ organismo }}</p>
                    </div>
                </div>

                <!-- Nav -->
                <nav class="sidebar-nav">
                    <template v-for="seccion in menu" :key="seccion.seccion">
                        <p class="sidebar-seccion" v-show="sidebarAbierto">{{ seccion.seccion }}</p>
                        <div class="sidebar-divider" v-show="!sidebarAbierto"></div>

                        <Link
                            v-for="item in seccion.items" :key="item.ruta"
                            :href="safeRoute(item.ruta)"
                            class="sidebar-item"
                            :class="{ 'sidebar-item--active': esActiva(item.ruta), 'sidebar-item--col': !sidebarAbierto }"
                            :title="!sidebarAbierto ? item.label : ''"
                        >
                            <svg class="sidebar-ico" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path :d="item.icono"/>
                            </svg>
                            <span class="sidebar-item-label" v-show="sidebarAbierto">{{ item.label }}</span>
                        </Link>
                    </template>
                </nav>

                <!-- Logout -->
                <div class="sidebar-footer">
                    <button class="sidebar-logout" :class="{ 'sidebar-logout--col': !sidebarAbierto }"
                            @click="cerrarSesion" title="Cerrar sesión">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span v-show="sidebarAbierto">Cerrar sesión</span>
                    </button>
                </div>

            </aside>

            <!-- Contenido -->
            <main class="main-content" :class="{ 'main-content--expanded': !sidebarAbierto }">
                <slot />
            </main>

        </div>
    </div>
</template>

<style scoped>
.layout-root { display: flex; flex-direction: column; min-height: 100vh; }

.banner {
    position: fixed; top: 0; left: 0; right: 0;
    height: var(--banner-h);
    background: var(--color-arena);
    border-bottom: 2px solid rgba(0,122,109,0.2);
    box-shadow: var(--shadow-sm);
    z-index: 100;
}

.banner-inner { display: flex; align-items: center; justify-content: space-between; height: 100%; padding: 0 1.25rem; }
.banner-side { display: flex; align-items: center; height: 100%; padding: 8px 0; }
.banner-side--right { justify-content: flex-end; }
.banner-img { height: 100%; width: auto; object-fit: contain; }
.banner-center { flex: 1; text-align: center; padding: 0 1rem; }
.banner-title { font-family: var(--font-display); font-size: 0.95rem; font-weight: 700; color: var(--color-verde-dk); letter-spacing: 0.02em; white-space: nowrap; }

.layout-body { display: flex; margin-top: var(--banner-h); min-height: calc(100vh - var(--banner-h)); }

.sidebar {
    position: fixed; top: var(--banner-h); left: 0; bottom: 0;
    width: var(--sidebar-w);
    background: #0d2b27;
    display: flex; flex-direction: column;
    transition: width var(--transition-slow);
    z-index: 90; overflow: hidden;
}

.sidebar--collapsed { width: var(--sidebar-c); }

.sidebar-toggle {
    display: flex; align-items: center; justify-content: center;
    width: 100%; height: 40px;
    background: rgba(255,255,255,0.04); border: none;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    cursor: pointer; color: rgba(255,255,255,0.35);
    transition: color var(--transition-base), background var(--transition-base);
    flex-shrink: 0;
}
.sidebar-toggle:hover { color: #4ecdc4; background: rgba(0,152,135,0.1); }
.sidebar-toggle svg { width: 16px; height: 16px; }

.sidebar-user { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; min-height: 72px; }
.sidebar-user--col { justify-content: center; padding: 1rem 0; }

.sidebar-avatar { width: 36px; height: 36px; border-radius: var(--radius-md); background: var(--color-verde); font-size: var(--text-xs); font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: 0.05em; color: white; }

.sidebar-user-info { overflow: hidden; white-space: nowrap; }
.sidebar-user-name { font-size: var(--text-sm); font-weight: 600; color: rgba(255,255,255,0.85); overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
.sidebar-user-org  { font-size: var(--text-xs); color: rgba(255,255,255,0.4); overflow: hidden; text-overflow: ellipsis; }

.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 0.75rem 0; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.08) transparent; }

.sidebar-seccion { font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(255,255,255,0.22); padding: 0.9rem 0.85rem 0.3rem; white-space: nowrap; }
.sidebar-divider { height: 1px; background: rgba(255,255,255,0.07); margin: 0.6rem 0.85rem; }

.sidebar-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.55rem 0.85rem; margin: 1px 0.5rem; border-radius: var(--radius-md); text-decoration: none; color: rgba(255,255,255,0.5); font-size: var(--text-sm); font-weight: 500; transition: background var(--transition-base), color var(--transition-base); white-space: nowrap; overflow: hidden; border-left: 2px solid transparent; }
.sidebar-item--col { justify-content: center; padding: 0.55rem 0; margin: 1px 0.4rem; }
.sidebar-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.sidebar-item--active { background: rgba(0,152,135,0.25); color: #4ecdc4; border-left-color: var(--color-verde); }

.sidebar-ico { width: 18px; height: 18px; flex-shrink: 0; }
.sidebar-item-label { overflow: hidden; text-overflow: ellipsis; }

.sidebar-footer { padding: 0.75rem 0.5rem; border-top: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }

.sidebar-logout { display: flex; align-items: center; gap: 0.7rem; width: 100%; padding: 0.55rem 0.85rem; border-radius: var(--radius-md); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.35); font-size: var(--text-sm); font-weight: 500; font-family: var(--font-body); transition: background var(--transition-base), color var(--transition-base); white-space: nowrap; }
.sidebar-logout--col { justify-content: center; padding: 0.55rem 0; }
.sidebar-logout:hover { background: rgba(174,25,34,0.15); color: #e8707a; }
.sidebar-logout svg { width: 18px; height: 18px; flex-shrink: 0; }

.main-content { flex: 1; margin-left: var(--sidebar-w); padding: 1.75rem; transition: margin-left var(--transition-slow); min-height: calc(100vh - var(--banner-h)); overflow-x: hidden; }
.main-content--expanded { margin-left: var(--sidebar-c); }
</style>
