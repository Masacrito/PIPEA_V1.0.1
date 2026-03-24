<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const page    = usePage()
const usuario = computed(() => page.props.auth?.user)
const rol     = computed(() => usuario.value?.rol?.rol)

const esSuperAdmin = computed(() => usuario.value?.id === 1)

const sidebarAbierto = ref(true)
const toggleSidebar  = () => { sidebarAbierto.value = !sidebarAbierto.value }
const cerrarSesion   = () => router.post(route('logout'))

const safeRoute = (name) => { try { return route(name) } catch { return '#' } }
const esActiva  = (name) => { try { return route().current(name) } catch { return false } }

const menu = [
    {
        seccion: 'Principal',
        items: [
            {
                label: 'Dashboard',
                ruta:  'admin.dashboard',
                icono: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            },
        ],
    },
    {
        seccion: 'PI-PEA',
        items: [
            {
                label: 'Líneas de acción',
                ruta:  'admin.lineas.index',
                icono: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            },
        ],
    },
    {
        seccion: 'Catálogos PI-PEA',
        items: [
            { label: 'Ejes',        ruta: 'admin.catalogos.ejes.index',        icono: 'M4 6h16M4 10h16M4 14h16M4 18h16' },
            { label: 'Objetivos',   ruta: 'admin.catalogos.objetivos.index',   icono: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
            { label: 'Prioridades', ruta: 'admin.catalogos.prioridades.index', icono: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z' },
            { label: 'Estrategias', ruta: 'admin.catalogos.estrategias.index', icono: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7' },
            { label: 'Plazos',      ruta: 'admin.catalogos.plazos.index',      icono: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
            { label: 'Frecuencias', ruta: 'admin.catalogos.frecuencias.index', icono: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
        ],
    },
    {
        seccion: 'Gestión',
        items: [
            { label: 'Organismos', ruta: 'admin.organismos.index', icono: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
            { label: 'Usuarios',   ruta: 'admin.usuarios.index',   icono: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
        ],
    },
]

const menuFiltrado = computed(() => menu)
</script>

<template>
    <div class="layout-root">

        <header class="banner">
            <img src="/images/banner-sesaech.png" alt="SESAECH" class="banner-img" />
        </header>

        <div class="layout-body">

            <aside class="sidebar" :class="{ 'sidebar--collapsed': !sidebarAbierto }">

                <button class="sidebar-toggle" @click="toggleSidebar" :title="sidebarAbierto ? 'Colapsar' : 'Expandir'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path v-if="sidebarAbierto" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
                        <path v-else               d="M13 5l7 7-7 7M6 5l7 7-7 7"/>
                    </svg>
                </button>

                <div class="sidebar-user" :class="{ 'sidebar-user--col': !sidebarAbierto }">
                    <div class="sidebar-avatar sidebar-avatar--admin">
                        {{ usuario?.nombre?.charAt(0) }}{{ usuario?.primer_apellido?.charAt(0) }}
                    </div>
                    <div class="sidebar-user-info" v-show="sidebarAbierto">
                        <p class="sidebar-user-name">{{ usuario?.nombre }} {{ usuario?.primer_apellido }}</p>
                        <span v-if="esSuperAdmin" class="badge-rol badge-rol--superadmin">Super Admin</span>
                        <span v-else class="badge-rol badge-rol--admin">Administrador</span>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <template v-for="seccion in menuFiltrado" :key="seccion.seccion">
                        <p class="sidebar-seccion" v-show="sidebarAbierto">{{ seccion.seccion }}</p>
                        <div class="sidebar-divider" v-show="!sidebarAbierto"></div>
                        <Link
                            v-for="item in seccion.items" :key="item.ruta"
                            :href="safeRoute(item.ruta)"
                            class="sidebar-item"
                            :class="{ 'sidebar-item--active': esActiva(item.ruta), 'sidebar-item--col': !sidebarAbierto }"
                            :title="!sidebarAbierto ? item.label : ''"
                        >
                            <svg class="sidebar-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path :d="item.icono"/>
                            </svg>
                            <span class="sidebar-item-label" v-show="sidebarAbierto">{{ item.label }}</span>
                        </Link>
                    </template>
                </nav>

                <div class="sidebar-footer">
                    <button class="sidebar-logout" :class="{ 'sidebar-logout--col': !sidebarAbierto }" @click="cerrarSesion" title="Cerrar sesión">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span v-show="sidebarAbierto">Cerrar sesión</span>
                    </button>
                </div>

            </aside>

            <main class="main-content" :class="{ 'main-content--expanded': !sidebarAbierto }">
                <slot />
            </main>

        </div>
    </div>
</template>

<style scoped>
/* ─── Layout base ─── */
.layout-root { display: flex; flex-direction: column; min-height: 100vh; }

/* ─── Banner ─── */
.banner {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: var(--banner-h);
    z-index: 110;
    background: var(--color-arena);
    border-bottom: 2px solid rgba(174,25,34,0.15);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 0;
}
.banner-img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    margin: 0 auto;
}
/* ─── Body (sidebar + contenido) ─── */
.layout-body { display: flex; margin-top: var(--banner-h); min-height: calc(100vh - var(--banner-h)); }

/* ─── Sidebar ─── */
.sidebar { position: fixed; top: var(--banner-h); left: 0; bottom: 0; width: var(--sidebar-w); background: #1a1210; display: flex; flex-direction: column; transition: width var(--transition-slow); z-index: 90; overflow: hidden; }
.sidebar--collapsed { width: var(--sidebar-c); }
.sidebar-toggle { display: flex; align-items: center; justify-content: center; width: 100%; height: 40px; background: rgba(255,255,255,0.04); border: none; border-bottom: 1px solid rgba(255,255,255,0.06); cursor: pointer; color: rgba(255,255,255,0.35); transition: color var(--transition-base), background var(--transition-base); flex-shrink: 0; }
.sidebar-toggle:hover { color: var(--color-verde); background: rgba(0,152,135,0.1); }
.sidebar-toggle svg { width: 16px; height: 16px; }
.sidebar-user { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; min-height: 72px; }
.sidebar-user--col { justify-content: center; padding: 1rem 0; }
.sidebar-avatar { width: 36px; height: 36px; border-radius: var(--radius-md); font-size: var(--text-xs); font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: 0.05em; color: white; }
.sidebar-avatar--admin { background: var(--color-vino); }
.sidebar-user-info { overflow: hidden; white-space: nowrap; }
.sidebar-user-name { font-size: var(--text-sm); font-weight: 600; color: rgba(255,255,255,0.85); overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
.badge-rol { display: inline-block; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.08em; padding: 2px 7px; border-radius: var(--radius-sm); text-transform: uppercase; }
.badge-rol--superadmin { background: rgba(174,25,34,0.25); color: #e8a0a5; }
.badge-rol--admin      { background: rgba(0,152,135,0.2);  color: #4ecdc4; }
.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 0.75rem 0; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.08) transparent; }
.sidebar-seccion { font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: rgba(255,255,255,0.22); padding: 0.9rem 0.85rem 0.3rem; white-space: nowrap; }
.sidebar-divider { height: 1px; background: rgba(255,255,255,0.07); margin: 0.6rem 0.85rem; }
.sidebar-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.55rem 0.85rem; margin: 1px 0.5rem; border-radius: var(--radius-md); text-decoration: none; color: rgba(255,255,255,0.5); font-size: var(--text-sm); font-weight: 500; transition: background var(--transition-base), color var(--transition-base); white-space: nowrap; overflow: hidden; border-left: 2px solid transparent; }
.sidebar-item--col { justify-content: center; padding: 0.55rem 0; margin: 1px 0.4rem; }
.sidebar-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
.sidebar-item--active { background: rgba(0,152,135,0.18); color: #4ecdc4; border-left-color: var(--color-verde); }
.sidebar-ico { width: 18px; height: 18px; flex-shrink: 0; }
.sidebar-item-label { overflow: hidden; text-overflow: ellipsis; }
.sidebar-footer { padding: 0.75rem 0.5rem; border-top: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }
.sidebar-logout { display: flex; align-items: center; gap: 0.7rem; width: 100%; padding: 0.55rem 0.85rem; border-radius: var(--radius-md); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.35); font-size: var(--text-sm); font-weight: 500; font-family: var(--font-body); transition: background var(--transition-base), color var(--transition-base); white-space: nowrap; }
.sidebar-logout--col { justify-content: center; padding: 0.55rem 0; }
.sidebar-logout:hover { background: rgba(174,25,34,0.15); color: #e8707a; }
.sidebar-logout svg { width: 18px; height: 18px; flex-shrink: 0; }

/* ─── Main content ─── */
.main-content { flex: 1; margin-left: var(--sidebar-w); padding: 1.75rem; transition: margin-left var(--transition-slow); min-height: calc(100vh - var(--banner-h)); overflow-x: hidden; }
.main-content--expanded { margin-left: var(--sidebar-c); }
</style>
