<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { home } from '@/routes'

defineProps<{
    title?: string
    description?: string
}>()
</script>

<template>
    <div class="auth-page-root">
        <!-- Ambient Background Blobs -->
        <div class="blob blob-1" aria-hidden="true"></div>
        <div class="blob blob-2" aria-hidden="true"></div>

        <!-- Site Header -->
        <header class="site-header">
            <div class="wrap">
                <div class="nav-shell">
                    <Link :href="home()" class="logo">
                        <span class="logo-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/>
                            </svg>
                        </span>
                        MediFlow
                    </Link>
                    <Link :href="home()" class="header-link">← Back to website</Link>
                </div>
            </div>
        </header>

        <!-- Main Content Wrapper -->
        <main class="auth-main">
            <div class="auth-card">
                <div v-if="title || description" class="auth-header">
                    <div class="logo-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/>
                        </svg>
                    </div>
                    <h1 v-if="title">{{ title }}</h1>
                    <p v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
.auth-page-root {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #F8F6EF;
    color: #16180F;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    position: relative;
    overflow-x: hidden;
}

.wrap {
    max-width: 1320px;
    margin-inline: auto;
    padding-inline: 32px;
    position: relative;
    width: 100%;
}
@media (max-width: 640px) {
    .wrap { padding-inline: 20px; }
}

.site-header {
    padding-top: 20px;
    z-index: 10;
    position: relative;
}

.nav-shell {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(248, 246, 239, 0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(22, 24, 15, 0.06);
    border-radius: 999px;
    padding: 8px 12px 8px 22px;
    box-shadow: 0 1px 2px rgba(22, 24, 15, 0.04), 0 8px 24px rgba(22, 24, 15, 0.06);
}

.logo {
    display: flex;
    align-items: center;
    gap: 9px;
    font-weight: 800;
    font-size: 19px;
    letter-spacing: -0.01em;
    color: #16301F;
    text-decoration: none;
}

.logo-mark {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #16301F;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo-mark svg {
    width: 17px;
    height: 17px;
    color: #DDF15C;
}

.header-link {
    font-size: 14px;
    font-weight: 600;
    color: #16301F;
    padding: 8px 16px;
    border-radius: 999px;
    transition: background-color 150ms ease;
    text-decoration: none;
}

.header-link:hover {
    background: rgba(22, 24, 15, 0.05);
}

main.auth-main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 20px;
    position: relative;
    z-index: 5;
}

.blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(70px);
    pointer-events: none;
    z-index: 0;
}

.blob-1 {
    top: -100px;
    right: -120px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, #C7E86B 0%, transparent 70%);
    opacity: 0.5;
}

.blob-2 {
    bottom: 5%;
    left: -140px;
    width: 360px;
    height: 380px;
    background: radial-gradient(circle, #B7DD5E 0%, transparent 70%);
    opacity: 0.35;
}

@media (max-width: 900px) {
    .blob { display: none; }
}

.auth-card {
    background: #FFFFFF;
    border: 1px solid #E7E3D3;
    border-radius: 32px;
    padding: 40px 36px;
    max-width: 460px;
    width: 100%;
    box-shadow: 0 4px 10px rgba(22, 24, 15, 0.06), 0 16px 36px rgba(22, 24, 15, 0.10);
    position: relative;
    z-index: 1;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 32px 24px;
        border-radius: 24px;
    }
}

.auth-header {
    text-align: center;
    margin-bottom: 28px;
}

.auth-header .logo-mark {
    margin: 0 auto 16px;
    width: 44px;
    height: 44px;
    border-radius: 12px;
}

.auth-header .logo-mark svg {
    width: 24px;
    height: 24px;
}

.auth-header h1 {
    font-size: 24px;
    font-weight: 800;
    color: #16301F;
    letter-spacing: -0.015em;
    margin-bottom: 6px;
}

.auth-header p {
    font-size: 14px;
    color: #62655A;
}
</style>
