<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
})
</script>

<template>
    <Head title="PIPEA — Secretaría Anticorrupción y Buen Gobierno · Chiapas" />

    <div class="public-root">

        <!-- ══ BANNER INSTITUCIONAL ══════════════════════════════ -->
        <div class="public-banner">
            <div class="public-banner-inner">
                <img src="/images/banner-sesaech.png" alt="SESAECH" class="banner-img" />
              </div>
        </div>

        <!-- ══ HEADER NAV ════════════════════════════════════════ -->
        <header class="public-header">
            <div class="public-header-inner">
                <span class="public-header-brand">SISTEMA PI-PEA</span>

                <nav class="public-nav">
                    <a href="#inicio"     class="public-nav-link">Inicio</a>
                    <a href="#que-es"     class="public-nav-link">¿Qué es?</a>
                    <a href="#objetivos"  class="public-nav-link">Objetivos</a>
                    <a href="#organismos" class="public-nav-link">Organismos</a>
                </nav>

                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="btn btn-primary btn-sm"
                >
                    Acceder al sistema
                </Link>
            </div>
        </header>

        <!-- ══ HERO ══════════════════════════════════════════════ -->
        <section id="inicio" class="public-hero">
            <div class="public-hero-inner">

                <div class="public-hero-logo">
                    <img src="/images/Logotipo%20nuevo.png" alt="SESAECH" class="public-hero-logo-img" />
                </div>

                <div class="public-hero-content">
                    <p class="public-hero-pre">GOBIERNO DE CHIAPAS · 2024–2030</p>
                    <h1 class="public-hero-title">
                        Sistema de Monitoreo<br />y Seguimiento
                        <span class="public-hero-accent">PI-PEA</span>
                    </h1>
                    <p class="public-hero-desc">
                        Plataforma institucional de la Secretaría Anticorrupción y Buen Gobierno
                        para la supervisión, evaluación y seguimiento de la Política Estatal
                        Anticorrupción de Chiapas.
                    </p>

                    <div class="public-hero-actions">
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="btn btn-primary btn-lg"
                        >
                            <svg style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Acceder al sistema
                        </Link>
                        <a href="#que-es" class="btn btn-outline btn-lg">
                            Conocer más
                        </a>
                    </div>
                </div>

            </div>

            <!-- Decoración fondo -->
            <div class="public-hero-deco" aria-hidden="true"></div>
        </section>

        <!-- ══ ¿QUÉ ES? ══════════════════════════════════════════ -->
        <section id="que-es" class="public-section public-section--light">
            <div class="public-section-inner">
                <div class="public-section-head">
                    <span class="public-section-tag">Sobre el programa</span>
                    <h2 class="public-section-title">¿Qué es el PI-PEA?</h2>
                </div>

                <div class="public-cards">
                    <div class="public-card">
                        <div class="public-card-ico public-card-ico--verde">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="public-card-title">Política Estatal Anticorrupción</h3>
                        <p class="public-card-desc">
                            El Programa de Implementación de la PEA establece las líneas de acción
                            que deben seguir las dependencias del Gobierno de Chiapas para combatir
                            la corrupción de manera sistemática.
                        </p>
                    </div>

                    <div class="public-card">
                        <div class="public-card-ico public-card-ico--vino">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="public-card-title">Organismos Implementadores</h3>
                        <p class="public-card-desc">
                            Las dependencias y entidades del gobierno estatal son responsables de
                            ejecutar y reportar el avance de las líneas de acción asignadas
                            conforme al programa.
                        </p>
                    </div>

                    <div class="public-card">
                        <div class="public-card-ico public-card-ico--magenta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="public-card-title">Monitoreo y Seguimiento</h3>
                        <p class="public-card-desc">
                            La SESAECH supervisa el cumplimiento del programa a través de esta
                            plataforma, registrando avances, evidencias y generando reportes
                            de cumplimiento institucional.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══ OBJETIVOS / EJES ══════════════════════════════════ -->
        <section id="objetivos" class="public-section">
            <div class="public-section-inner">
                <div class="public-section-head">
                    <span class="public-section-tag">Estructura del programa</span>
                    <h2 class="public-section-title">4 Ejes estratégicos</h2>
                    <p class="public-section-sub">
                        El PI-PEA se articula en 4 ejes, 10 objetivos específicos y 67 prioridades
                        que orientan la acción anticorrupción del Estado.
                    </p>
                </div>

                <div class="public-ejes">
                    <div class="public-eje">
                        <span class="public-eje-num">01</span>
                        <p class="public-eje-label">Prevención de la corrupción</p>
                    </div>
                    <div class="public-eje">
                        <span class="public-eje-num">02</span>
                        <p class="public-eje-label">Control interno y auditoría</p>
                    </div>
                    <div class="public-eje">
                        <span class="public-eje-num">03</span>
                        <p class="public-eje-label">Transparencia y rendición de cuentas</p>
                    </div>
                    <div class="public-eje">
                        <span class="public-eje-num">04</span>
                        <p class="public-eje-label">Participación ciudadana</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══ CTA FINAL ════════════════════════════════════════ -->
        <section id="organismos" class="public-cta">
            <div class="public-cta-inner">
                <h2 class="public-cta-title">¿Eres servidor público autorizado?</h2>
                <p class="public-cta-desc">
                    Accede al sistema para registrar y consultar el avance de tu organismo
                    en el cumplimiento del PI-PEA.
                </p>
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="btn btn-primary btn-lg"
                >
                    Acceder al sistema
                </Link>
            </div>
        </section>

        <!-- ══ FOOTER ═══════════════════════════════════════════ -->
        <footer class="public-footer">
            <div class="public-footer-inner">
                <p>Secretaría Anticorrupción y Buen Gobierno · Gobierno de Chiapas · 2024–2030</p>
                <p class="public-footer-sub">Sistema PI-PEA · Uso exclusivo para servidores públicos autorizados</p>
            </div>
        </footer>

    </div>
</template>

<style scoped>
/* Usa variables de app.css + estilos propios de la página pública */

.public-root {
    min-height: 100vh;
    background: var(--color-arena-lt);
    font-family: var(--font-body);
}

/* ── Banner ─────────────────────────────────────────────── */
.banner {
    height: var(--banner-h);
    padding: 0;
}
.banner-img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

/* ── Header nav ─────────────────────────────────────────── */
.public-header {
    background: #1a1210;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
}

.public-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0.85rem 1.5rem;
}

.public-header-brand {
    font-family: var(--font-display);
    font-size: var(--text-sm);
    font-weight: 700;
    letter-spacing: 0.15em;
    color: var(--color-arena);
}

.public-nav {
    display: flex;
    gap: 2rem;
}

.public-nav-link {
    font-size: var(--text-sm);
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    transition: color var(--transition-base);
    letter-spacing: 0.04em;
}

.public-nav-link:hover { color: var(--color-verde); }

/* ── Hero ───────────────────────────────────────────────── */
.public-hero {
    position: relative;
    background: var(--color-blanco);
    overflow: hidden;
    padding: 5rem 1.5rem;
}

.public-hero-inner {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 4rem;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.public-hero-logo {
    display: flex;
    justify-content: center;
    align-items: center;
}

.public-hero-logo-img {
    width: 320px;
    max-width: 100%;
    height: auto;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,0.1));
}

.public-hero-pre {
    font-size: var(--text-xs);
    font-weight: 700;
    letter-spacing: 0.2em;
    color: var(--color-verde);
    text-transform: uppercase;
    margin-bottom: 1rem;
}

.public-hero-title {
    font-family: var(--font-display);
    font-size: 2.4rem;
    line-height: 1.15;
    color: var(--color-gris-800);
    margin-bottom: 1.25rem;
    text-transform: uppercase;
}

.public-hero-accent {
    display: block;
    color: var(--color-vino);
}

.public-hero-desc {
    font-size: var(--text-md);
    line-height: 1.75;
    color: var(--color-gris-600);
    margin-bottom: 2rem;
    max-width: 480px;
}

.public-hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Decoración hero */
.public-hero-deco {
    position: absolute;
    top: -80px;
    right: -80px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0,152,135,0.06) 0%, transparent 70%);
    pointer-events: none;
}

/* ── Secciones ──────────────────────────────────────────── */
.public-section {
    padding: 5rem 1.5rem;
}

.public-section--light {
    background: var(--color-arena-lt);
}

.public-section-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.public-section-head {
    text-align: center;
    margin-bottom: 3rem;
}

.public-section-tag {
    display: inline-block;
    font-size: var(--text-xs);
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--color-verde);
    margin-bottom: 0.75rem;
}

.public-section-title {
    font-family: var(--font-display);
    font-size: var(--text-2xl);
    color: var(--color-gris-800);
    margin-bottom: 0.75rem;
}

.public-section-sub {
    font-size: var(--text-md);
    color: var(--color-gris-500);
    max-width: 560px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Cards */
.public-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.public-card {
    background: var(--color-blanco);
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--color-gris-200);
    transition: box-shadow var(--transition-base), transform var(--transition-base);
}

.public-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-3px);
}

.public-card-ico {
    width: 52px; height: 52px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
}

.public-card-ico svg { width: 26px; height: 26px; }
.public-card-ico--verde   { background: var(--color-verde-lt);   color: var(--color-verde); }
.public-card-ico--vino    { background: var(--color-vino-lt);    color: var(--color-vino); }
.public-card-ico--magenta { background: var(--color-magenta-lt); color: var(--color-magenta); }

.public-card-title {
    font-family: var(--font-display);
    font-size: var(--text-md);
    color: var(--color-gris-800);
    margin-bottom: 0.75rem;
}

.public-card-desc {
    font-size: var(--text-sm);
    color: var(--color-gris-500);
    line-height: 1.75;
}

/* Ejes */
.public-ejes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
}

.public-eje {
    background: var(--color-blanco);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    border-left: 4px solid var(--color-verde);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.public-eje-num {
    font-family: var(--font-display);
    font-size: var(--text-2xl);
    font-weight: 700;
    color: var(--color-verde);
    line-height: 1;
}

.public-eje-label {
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--color-gris-700);
    line-height: 1.4;
}

/* CTA */
.public-cta {
    background: linear-gradient(135deg, #003d35 0%, #005a4e 50%, #007a6d 100%);
    padding: 5rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.public-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        repeating-linear-gradient(45deg, rgba(255,255,255,0.03) 0, rgba(255,255,255,0.03) 1px, transparent 0, transparent 50%),
        repeating-linear-gradient(-45deg, rgba(255,255,255,0.03) 0, rgba(255,255,255,0.03) 1px, transparent 0, transparent 50%);
    background-size: 20px 20px;
}

.public-cta-inner {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.public-cta-title {
    font-family: var(--font-display);
    font-size: var(--text-2xl);
    color: var(--color-blanco);
}

.public-cta-desc {
    font-size: var(--text-md);
    color: rgba(255,255,255,0.65);
    line-height: 1.7;
    margin-bottom: 0.5rem;
}

/* Footer */
.public-footer {
    background: #1a1210;
    padding: 1.75rem 1.5rem;
    text-align: center;
}

.public-footer-inner {
    font-size: var(--text-xs);
    color: rgba(255,255,255,0.35);
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.public-footer-sub { color: rgba(255,255,255,0.2); }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .public-hero-inner { grid-template-columns: 1fr; text-align: center; gap: 2rem; }
    .public-hero-logo { order: -1; }
    .public-hero-logo-img { width: 200px; }
    .public-hero-desc { margin: 0 auto 2rem; }
    .public-hero-actions { justify-content: center; }
    .public-hero-title { font-size: 1.75rem; }
    .public-nav { display: none; }
    .public-banner-title { font-size: 0.7rem; }
}
</style>
