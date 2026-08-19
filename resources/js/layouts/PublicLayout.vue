<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps<{
    title?: string
}>()

const page = usePage()
const user = computed(() => page.props.auth?.user)
const currentPath = computed(() => page.url)

function isActive(path: string): boolean {
    if (path === '/' && currentPath.value === '/') {
        return true
    }
    if (path !== '/' && currentPath.value.startsWith(path)) {
        return true
    }
    return false
}
</script>

<template>
    <div class="min-h-screen flex flex-col font-sans bg-[#F8F6EF] text-[#16180F] antialiased relative overflow-x-hidden">
        <!-- Ambient background blobs -->
        <div class="blob blob-1" aria-hidden="true"></div>
        <div class="blob blob-2" aria-hidden="true"></div>

        <!-- Sticky Header -->
        <header class="site-header sticky top-0 z-40 pt-5">
            <div class="wrap">
                <div class="nav-shell">
                    <Link href="/" class="logo">
                        <span class="logo-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z" />
                            </svg>
                        </span>
                        MediFlow
                    </Link>

                    <nav class="main-nav" aria-label="Primary">
                        <Link href="/" :class="{ active: isActive('/') }">Home</Link>
                        <Link href="/about" :class="{ active: isActive('/about') }">About</Link>
                        <Link href="/departments" :class="{ active: isActive('/departments') }">Departments</Link>
                        <Link href="/doctors" :class="{ active: isActive('/doctors') }">Doctors</Link>
                        <Link href="/faq" :class="{ active: isActive('/faq') }">FAQ</Link>
                        <Link href="/contact" :class="{ active: isActive('/contact') }">Contact</Link>
                    </nav>

                    <div class="header-right">
                        <div class="phone-block">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span><b>(555) 340-2199</b><span>120 Harbor Ave</span></span>
                        </div>

                        <template v-if="user">
                            <Link href="/dashboard" class="btn btn-primary btn-sm">Dashboard</Link>
                        </template>
                        <template v-else>
                            <Link href="/login" class="btn btn-outline btn-sm">Log in</Link>
                            <Link href="/register" class="btn btn-primary btn-sm">Book now</Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="footer pt-18 pb-8 relative z-10 border-t border-[#E7E3D3]">
            <div class="wrap">
                <div class="footer-grid">
                    <div>
                        <Link href="/" class="logo">
                            <span class="logo-mark" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z" />
                                </svg>
                            </span>
                            MediFlow
                        </Link>
                        <p>Online appointment booking and family care coordination — one account for every specialist your household needs.</p>
                    </div>
                    <div>
                        <h5>Product</h5>
                        <ul>
                            <li><Link href="/departments">Departments</Link></li>
                            <li><Link href="/doctors">Doctors</Link></li>
                            <li><Link href="/register">Book an appointment</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5>Hospital</h5>
                        <ul>
                            <li><Link href="/about">About us</Link></li>
                            <li><Link href="/contact">Contact</Link></li>
                            <li><Link href="/faq">FAQ</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h5>Legal</h5>
                        <ul>
                            <li><Link href="/privacy-policy">Privacy policy</Link></li>
                            <li><Link href="/terms-of-service">Terms of service</Link></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>© 2026 MediFlow General Hospital. All rights reserved.</span>
                    <span>Modern Healthcare Management System.</span>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

.blob { position: fixed; border-radius: 50%; filter: blur(70px); pointer-events: none; z-index: 0; }
.blob-1 { top: -120px; right: -140px; width: 420px; height: 420px; background: radial-gradient(circle, #C7E86B 0%, transparent 70%); opacity: 0.55; }
.blob-2 { bottom: 10%; left: -160px; width: 380px; height: 380px; background: radial-gradient(circle, #B7DD5E 0%, transparent 70%); opacity: 0.4; }
@media (max-width: 900px) { .blob { display: none; } }

.nav-shell { display: flex; align-items: center; justify-content: space-between; background: rgba(248,246,239,0.9); backdrop-filter: blur(12px); border: 1px solid rgba(22,24,15,0.06); border-radius: 999px; padding: 8px 8px 8px 22px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.logo { display: flex; align-items: center; gap: 9px; font-weight: 800; font-size: 19px; letter-spacing: -0.01em; color: #16180F; text-decoration: none; }
.logo-mark { width: 32px; height: 32px; border-radius: 9px; background: #16301F; display: flex; align-items: center; justify-content: center; }
.logo-mark svg { width: 17px; height: 17px; color: #DDF15C; }
.main-nav { display: flex; align-items: center; gap: 4px; }
.main-nav a { font-size: 14px; font-weight: 500; color: #62655A; padding: 10px 15px; border-radius: 999px; transition: background-color 150ms ease, color 150ms ease; text-decoration: none; }
.main-nav a:hover { background: rgba(22,24,15,0.05); color: #16180F; }
.main-nav a.active { background: #DDF15C; color: #3B4A12; font-weight: 600; }
.header-right { display: flex; align-items: center; gap: 10px; }
.phone-block { display: flex; align-items: center; gap: 8px; padding: 0 6px; font-size: 13px; }
.phone-block svg { width: 16px; height: 16px; color: #62655A; }
.phone-block b { display: block; font-size: 13.5px; color: #16180F; }
.phone-block span { color: #62655A; font-size: 11.5px; }
@media (max-width: 980px) { .main-nav, .phone-block { display: none; } }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; text-decoration: none; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }
.btn-sm { height: 46px; padding: 0 22px; font-size: 14px; }

.footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 32px; padding-bottom: 40px; }
@media (max-width: 800px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 480px) { .footer-grid { grid-template-columns: 1fr; } }
.footer-grid p { font-size: 13.5px; color: #62655A; max-width: 260px; line-height: 1.6; margin-top: 12px; }
footer h5 { font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 16px; color: #62655A; }
footer ul { list-style: none; padding: 0; margin: 0; }
footer ul li { margin-bottom: 10px; }
footer ul a { font-size: 14px; color: #16180F; text-decoration: none; transition: opacity 150ms ease; }
footer ul a:hover { opacity: 0.6; }
.footer-bottom { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #E7E3D3; padding-top: 24px; font-size: 13px; color: #62655A; flex-wrap: wrap; gap: 12px; }
</style>
