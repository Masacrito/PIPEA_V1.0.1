<script setup>
import { Head } from '@inertiajs/vue3'
import { useLogin } from '@/Composables/useLogin'

defineProps({
    status: String,
})

const { form, showPassword, togglePassword, submit } = useLogin()
</script>

<template>
    <Head title="Iniciar sesión — SESAECH" />

    <div class="login-root">

        <!-- Panel izquierdo decorativo -->
        <div class="login-panel-left" aria-hidden="true">
            <div class="login-panel-bg"></div>
            <div class="login-panel-pattern"></div>

            <div class="login-panel-inner">
                <!-- Logo institucional PNG -->
                <div class="logo-block">
                    <img
                        src="/images/Logotipo%20nuevo.png"
                        alt="Secretaría Anticorrupción y Buen Gobierno — Gobierno de Chiapas"
                        class="logo-img"
                        style="filter: brightness(0) invert(1);"
                    />
                </div>


                <div class="login-tagline">
                    <p class="login-tagline-label">HUMANISMO QUE TRANSFORMA</p>
                    <p class="login-tagline-desc">
                        Plataforma de Implementación de la<br />
                        Política Estatal Anticorrupción
                    </p>
                </div>
            </div>

            <span class="login-panel-footer">SESAECH · PI-PEA · 2025</span>
        </div>

        <!-- Panel derecho: formulario -->
        <div class="login-panel-right">
            <div class="login-form-wrap">

                <!-- Logo móvil -->
                <div class="login-mobile-logo" aria-hidden="true">
                    <img src="/images/logo-sesaech.png" alt="SESAECH" class="login-mobile-logo-img" />
                </div>

                <!-- Encabezado -->
                <div class="login-head">
                    <h2 class="login-title">Acceso al sistema</h2>
                    <p class="login-subtitle text-muted">Ingresa tus credenciales institucionales</p>
                </div>

                <!-- Flash status -->
                <div v-if="status" class="flash-success" role="alert" style="margin-bottom: 1.25rem;">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" novalidate>

                    <!-- Email -->
                    <div class="login-field" :class="{ 'login-field--error': form.errors.email }">
                        <label for="email" class="field-label">Correo electrónico</label>
                        <div class="login-field-wrap">
                            <svg class="login-field-ico" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="field-input login-input"
                                :class="{ 'field-input--error': form.errors.email }"
                                placeholder="correo@dependencia.gob.mx"
                                autocomplete="username"
                                :disabled="form.processing"
                                required
                            />
                        </div>
                        <span v-if="form.errors.email" class="field-error-msg" role="alert">
              {{ form.errors.email }}
            </span>
                    </div>

                    <!-- Contraseña -->
                    <div class="login-field" :class="{ 'login-field--error': form.errors.password }">
                        <label for="password" class="field-label">Contraseña</label>
                        <div class="login-field-wrap">
                            <svg class="login-field-ico" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="field-input login-input login-input--pass"
                                :class="{ 'field-input--error': form.errors.password }"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                :disabled="form.processing"
                                required
                            />
                            <button
                                type="button"
                                class="login-pass-toggle"
                                @click="togglePassword"
                                :aria-label="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                            >
                                <!-- Ojo abierto -->
                                <svg v-if="!showPassword" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd"
                                          d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <!-- Ojo cerrado -->
                                <svg v-else viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                          clip-rule="evenodd"/>
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                </svg>
                            </button>
                        </div>
                        <span v-if="form.errors.password" class="field-error-msg" role="alert">
              {{ form.errors.password }}
            </span>
                    </div>

                    <!-- Recordar sesión -->
                    <div class="login-remember">
                        <label class="login-remember-label">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="login-remember-chk"
                                :disabled="form.processing"
                            />
                            <span>Mantener sesión iniciada</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary btn-lg login-btn" :disabled="form.processing">
                        <svg v-if="form.processing" class="spinner" style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                    stroke-dasharray="32" stroke-dashoffset="12"/>
                        </svg>
                        <span>{{ form.processing ? 'Verificando...' : 'Iniciar sesión' }}</span>
                    </button>

                </form>

                <p class="login-disclaimer text-muted">
                    Sistema de uso exclusivo para servidores públicos autorizados.<br />
                    Gobierno de Chiapas · 2024–2030
                </p>

            </div>
        </div>

    </div>
</template>

<style scoped>
/* Solo CSS específico del login que no está en app.css */

.login-root {
    display: flex;
    min-height: 100vh;
    background: var(--color-arena-lt);
}

/* ── Panel izquierdo ─────────────────────────────────────── */
.login-panel-left {
    position: relative;
    width: 45%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.login-panel-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(150deg, #003d35 0%, #005a4e 40%, #007a6d 100%);
    z-index: 0;
}

.login-panel-pattern {
    position: absolute;
    inset: 0;
    z-index: 1;
    opacity: 0.04;
    background-image:
        repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%),
        repeating-linear-gradient(-45deg, #fff 0, #fff 1px, transparent 0, transparent 50%);
    background-size: 20px 20px;
}

/* Línea magenta derecha */
.login-panel-left::after {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, transparent, var(--color-magenta) 25%, var(--color-magenta) 75%, transparent);
    z-index: 3;
}

.login-panel-inner {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    padding: 2rem 2.5rem;
    width: 100%;
}

.login-logo-block {
    display: flex;
    justify-content: center;
    filter: brightness(0) invert(1);

}

.login-logo {
    width: 260px;
    max-width: 80%;
    height: auto;


}


.login-tagline {
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.12);
    padding-top: 1.5rem;
    width: 100%;
    max-width: 290px;
}

.login-tagline-label {
    font-family: var(--font-display);
    font-size: var(--text-xs);
    font-weight: 700;
    letter-spacing: 0.2em;
    color: var(--color-arena);
    margin-bottom: 0.55rem;
}

.login-tagline-desc {
    font-size: var(--text-sm);
    line-height: 1.7;
    color: rgba(255,255,255,0.45);
}

.login-panel-footer {
    position: absolute;
    bottom: 1.25rem;
    font-size: var(--text-xs);
    letter-spacing: 0.15em;
    color: rgba(255,255,255,0.2);
    z-index: 2;
}

/* ── Panel derecho ───────────────────────────────────────── */
.login-panel-right {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 2rem;
    background: var(--color-arena-lt);
}

.login-form-wrap {
    width: 100%;
    max-width: 400px;
}

/* Logo móvil */
.login-mobile-logo {
    display: none;
    justify-content: center;
    margin-bottom: 1.75rem;
}

.login-mobile-logo-img {
    width: 180px;
    max-width: 70%;
    height: auto;
}

/* Encabezado */
.login-head {
    margin-bottom: 1.75rem;
}

.login-title {
    font-size: var(--text-2xl);
    color: var(--color-vino);
    margin-bottom: 0.3rem;
}

.login-subtitle {
    font-size: var(--text-sm);
}

/* Campos */
.login-field {
    margin-bottom: 1.1rem;
}

.login-field-wrap {
    position: relative;
}

.login-field-ico {
    position: absolute;
    left: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    width: 15px;
    height: 15px;
    color: var(--color-gris-400);
    pointer-events: none;
}

.login-input {
    padding-left: 2.35rem;
}

.login-input--pass {
    padding-right: 2.75rem;
}

.login-pass-toggle {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--color-gris-400);
    padding: 0;
    display: flex;
    transition: color var(--transition-base);
}

.login-pass-toggle:hover { color: var(--color-verde); }
.login-pass-toggle svg { width: 17px; height: 17px; }

/* Recordar */
.login-remember {
    margin-bottom: 1.4rem;
}

.login-remember-label {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: var(--text-sm);
    color: var(--color-gris-500);
    cursor: pointer;
}

.login-remember-chk {
    width: 14px;
    height: 14px;
    accent-color: var(--color-verde);
    cursor: pointer;
}

/* Botón */
.login-btn {
    width: 100%;
}

/* Disclaimer */
.login-disclaimer {
    margin-top: 1.6rem;
    text-align: center;
    font-size: var(--text-xs);
    line-height: 1.7;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .login-root { flex-direction: column; }
    .login-panel-left { display: none; }
    .login-panel-right { padding: 3rem 1.5rem; align-items: flex-start; }
    .login-mobile-logo { display: flex; }
}
</style>
