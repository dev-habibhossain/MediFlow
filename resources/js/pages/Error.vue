<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    status: number
}>()

const searchQuery = ref('')

function handleSearch() {
    if (searchQuery.value.trim()) {
        window.location.href = `/doctors?search=${encodeURIComponent(searchQuery.value.trim())}`
    }
}

function reloadPage() {
    window.location.reload()
}
</script>

<template>
    <PublicLayout :title="status === 403 ? 'Access Forbidden (403) — MediFlow' : status === 500 ? 'Server Error (500) — MediFlow' : 'Page Not Found (404) — MediFlow'">
        <main class="py-12 flex-1 flex items-center justify-center">
            <div class="wrap">
                <!-- 403 FORBIDDEN ERROR CARD -->
                <div v-if="status === 403" class="error-card error-403">
                    <span class="pill pill-danger mb-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Access Restricted
                    </span>

                    <div class="error-code text-[#B71C1C]"><b>403</b></div>
                    <h1><b>Access Forbidden</b></h1>
                    <p class="lead">You do not have permission to view this page or access this clinical resource. Your account role may not have the required permissions.</p>

                    <div class="error-actions">
                        <Link href="/login" class="btn btn-primary">Sign in with Another Account</Link>
                        <Link href="/" class="btn btn-outline">Return to Homepage</Link>
                    </div>

                    <!-- SECURITY NOTE BOX -->
                    <div class="security-note-box">
                        <h4>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Why am I seeing this?
                        </h4>
                        <p>Certain clinical dashboards (such as Doctor Schedule Editors or Admin System Logs) require elevated authorization. If you believe you should have access to this module, please contact your MediFlow system administrator.</p>
                    </div>

                    <!-- HELPFUL DIRECTORY LINKS -->
                    <div class="helpful-links">
                        <h5>Navigate to public areas</h5>
                        <div class="links-grid">
                            <Link href="/departments" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/></svg> Departments
                            </Link>
                            <Link href="/doctors" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Doctors
                            </Link>
                            <Link href="/faq" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> FAQ Center
                            </Link>
                            <Link href="/contact" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Contact Us
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- 500 SERVER ERROR CARD -->
                <div v-else-if="status === 500 || status === 503" class="error-card error-500">
                    <span class="pill pill-warning mb-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Unexpected System Error
                    </span>

                    <div class="error-code text-[#D97706]"><b>{{ status }}</b></div>
                    <h1><b>Internal Server Error</b></h1>
                    <p class="lead">Something went wrong on our server while processing your request. Our technical operations team has been notified automatically.</p>

                    <div class="error-actions">
                        <button @click="reloadPage" class="btn btn-primary" type="button">Try Refreshing Page</button>
                        <Link href="/" class="btn btn-outline">Return to Homepage</Link>
                    </div>

                    <!-- TECHNICAL NOTE BOX -->
                    <div class="tech-note-box">
                        <h4>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            What should I do next?
                        </h4>
                        <p>This is usually a temporary issue. Try refreshing the page in a few moments. If you were attempting to confirm an appointment booking or submit an urgent medical form, please call our front desk directly at <b>(555) 340-2199</b>.</p>
                    </div>

                    <!-- HELPFUL DIRECTORY LINKS -->
                    <div class="helpful-links">
                        <h5>Explore main clinic sections</h5>
                        <div class="links-grid">
                            <Link href="/departments" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/></svg> Departments
                            </Link>
                            <Link href="/doctors" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Doctors
                            </Link>
                            <Link href="/faq" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> FAQ Center
                            </Link>
                            <Link href="/contact" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Contact Us
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- 404 NOT FOUND ERROR CARD -->
                <div v-else class="error-card error-404">
                    <span class="pill pill-default mb-4">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Error Code
                    </span>

                    <div class="error-code text-[#16301F]"><b>404</b></div>
                    <h1><b>Page Not Found</b></h1>
                    <p class="lead">The link you followed may be broken, expired, or the page may have been moved. Let's get you back to the right place.</p>

                    <div class="error-actions">
                        <Link href="/" class="btn btn-primary">Return to Homepage</Link>
                        <Link href="/doctors" class="btn btn-outline">Find a Doctor</Link>
                    </div>

                    <!-- QUICK SEARCH BOX -->
                    <div class="quick-search-box">
                        <h4><b>Looking for something specific?</b></h4>
                        <div class="search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search departments, specialists, or services..."
                                @keydown.enter="handleSearch"
                            />
                        </div>
                    </div>

                    <!-- HELPFUL DIRECTORY LINKS -->
                    <div class="helpful-links">
                        <h5>Or visit one of our main sections</h5>
                        <div class="links-grid">
                            <Link href="/departments" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/></svg> Departments
                            </Link>
                            <Link href="/doctors" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Doctors
                            </Link>
                            <Link href="/faq" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> FAQ Center
                            </Link>
                            <Link href="/contact" class="link-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Contact Us
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.wrap { max-width: 1320px; margin-inline: auto; padding-inline: 32px; position: relative; width: 100%; }
@media (max-width: 640px) { .wrap { padding-inline: 20px; } }

b { font-weight: 700; color: inherit; }

.pill { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; padding: 7px 16px 7px 12px; border-radius: 999px; }
.pill svg { width: 14px; height: 14px; }
.pill-default { background: #DDF15C; color: #3B4A12; }
.pill-danger { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
.pill-warning { background: #FFF8E1; color: #B45309; border: 1px solid #FFE082; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 54px; padding: 0 28px; border-radius: 999px; font-size: 15.5px; font-weight: 600; transition: transform 150ms ease, background-color 150ms ease, box-shadow 150ms ease; text-decoration: none; border: 0; cursor: pointer; }
.btn:active { transform: scale(0.97); }
.btn-primary { background: #16301F; color: #fff; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); }
.btn-primary:hover { background: #1E4029; }
.btn-outline { background: transparent; color: #16180F; border: 1.5px solid rgba(22,24,15,0.16); }
.btn-outline:hover { border-color: #16180F; }

.error-card { background: #FFFFFF; border: 1px solid #E7E3D3; border-radius: 32px; padding: 60px 48px; box-shadow: 0 1px 2px rgba(22,24,15,0.04), 0 8px 24px rgba(22,24,15,0.06); text-align: center; max-width: 860px; margin: 0 auto; position: relative; overflow: hidden; }
@media (max-width: 600px) { .error-card { padding: 40px 24px; } }

.error-404::before { content: ""; position: absolute; top: -60px; left: 50%; transform: translateX(-50%); width: 240px; height: 240px; border-radius: 50%; background: #DDF15C; opacity: 0.25; filter: blur(30px); pointer-events: none; }
.error-403::before { content: ""; position: absolute; top: -60px; left: 50%; transform: translateX(-50%); width: 240px; height: 240px; border-radius: 50%; background: #FFCDD2; opacity: 0.35; filter: blur(30px); pointer-events: none; }
.error-500::before { content: ""; position: absolute; top: -60px; left: 50%; transform: translateX(-50%); width: 240px; height: 240px; border-radius: 50%; background: #FFE082; opacity: 0.35; filter: blur(30px); pointer-events: none; }

.error-code { font-family: 'JetBrains Mono', monospace; font-size: clamp(4rem, 3rem + 4vw, 6.5rem); font-weight: 800; line-height: 1; letter-spacing: -0.03em; margin-bottom: 8px; position: relative; }

.error-card h1 { font-size: clamp(1.8rem, 1.4rem + 1.2vw, 2.4rem); font-weight: 800; letter-spacing: -0.015em; color: #16180F; margin-bottom: 12px; position: relative; }

.error-card p.lead { font-size: 16px; color: #62655A; max-width: 52ch; margin: 0 auto 32px; line-height: 1.6; position: relative; }

.error-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px; position: relative; }

.security-note-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; max-width: 580px; margin: 0 auto 36px; text-align: left; position: relative; }
.security-note-box h4 { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #16301F; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
.security-note-box h4 svg { width: 16px; height: 16px; color: #C62828; }
.security-note-box p { font-size: 13.5px; color: #62655A; line-height: 1.55; }

.tech-note-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; max-width: 580px; margin: 0 auto 36px; text-align: left; position: relative; }
.tech-note-box h4 { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #16301F; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
.tech-note-box h4 svg { width: 16px; height: 16px; color: #D97706; }
.tech-note-box p { font-size: 13.5px; color: #62655A; line-height: 1.55; }

.quick-search-box { background: #F8F6EF; border: 1px solid #E7E3D3; border-radius: 24px; padding: 24px; max-width: 580px; margin: 0 auto 40px; position: relative; }
.quick-search-box h4 { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #62655A; margin-bottom: 12px; }

.search-input-wrap { position: relative; width: 100%; }
.search-input-wrap svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: #62655A; pointer-events: none; }
.search-input-wrap input { width: 100%; height: 48px; border-radius: 999px; border: 1px solid #E7E3D3; background: #FFFFFF; padding-left: 44px; padding-right: 16px; font-size: 14.5px; outline: none; transition: border-color 150ms ease; }
.search-input-wrap input:focus { border-color: #16301F; }

.helpful-links { border-top: 1px solid #E7E3D3; padding-top: 32px; position: relative; }
.helpful-links h5 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #62655A; margin-bottom: 16px; }

.links-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
@media (max-width: 680px) { .links-grid { grid-template-columns: repeat(2, 1fr); } }

.link-pill { background: #F0EEE3; border: 1px solid #E7E3D3; padding: 10px 14px; border-radius: 999px; font-size: 13.5px; font-weight: 600; color: #16180F; transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; }
.link-pill:hover { background: #16301F; color: #fff; border-color: #16301F; }
.link-pill svg { width: 14px; height: 14px; }
</style>
