<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const emailInput = ref('')
const isSubscribed = ref(false)

function handleSubscribe() {
    if (emailInput.value.trim()) {
        isSubscribed.value = true
    }
}
</script>

<template>
    <PublicLayout title="Scheduled System Maintenance — MediFlow">
        <main class="maintenance-page">
            <div class="wrap">
                <div class="maintenance-card">
                    <!-- STATUS BADGE -->
                    <div class="badge-row">
                        <span class="status-pill warning-pill">
                            <span class="pulse-amber"></span>
                            Scheduled System Maintenance
                        </span>
                    </div>

                    <!-- HERO HEADLINE -->
                    <h1 class="main-title">MediFlow Infrastructure Upgrade in Progress</h1>
                    <p class="subtitle font-sans">
                        We are currently performing routine database indexing, security patching, and server capacity expansion to optimize your healthcare experience.
                    </p>

                    <!-- PROGRESS ESTIMATE BAR -->
                    <div class="progress-box">
                        <div class="progress-header">
                            <span class="progress-label">System Upgrade Progress</span>
                            <span class="progress-val">85% Complete</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width: 85%;"></div>
                        </div>
                        <div class="progress-footer">
                            <span>Estimated Downtime Remaining: <b>~25 Minutes</b></span>
                            <span>Target Completion: <b>03:00 AM UTC</b></span>
                        </div>
                    </div>

                    <!-- URGENT MEDICAL CARE EMERGENCY CALLOUT -->
                    <div class="emergency-callout">
                        <div class="emergency-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <div class="emergency-text">
                            <h4>Need Immediate Urgent Care?</h4>
                            <p>Our hospital physical emergency rooms, front desks, and triage units remain <b>100% operational 24/7</b>.</p>
                            <div class="contact-chips">
                                <a href="tel:5553402199" class="phone-chip">
                                    📞 Call Front Desk: (555) 340-2199
                                </a>
                                <span class="location-chip">
                                    📍 ER Campus: Harbor Ave Building B (24/7)
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- SERVICE MATRIX STATUS breakdown -->
                    <div class="service-matrix">
                        <h4 class="matrix-title">Current System Sub-Service Status</h4>
                        <div class="matrix-grid">
                            <div class="matrix-item">
                                <div class="matrix-info">
                                    <b>Patient Records Database</b>
                                    <span>Database Indexing</span>
                                </div>
                                <span class="matrix-badge bg-amber">Under Maintenance</span>
                            </div>

                            <div class="matrix-item">
                                <div class="matrix-info">
                                    <b>Emergency Reception & Triage</b>
                                    <span>Physical Clinic Desk</span>
                                </div>
                                <span class="matrix-badge bg-green">100% Operational</span>
                            </div>

                            <div class="matrix-item">
                                <div class="matrix-info">
                                    <b>Telehealth Video Server</b>
                                    <span>Live Video Consultations</span>
                                </div>
                                <span class="matrix-badge bg-green">100% Operational</span>
                            </div>

                            <div class="matrix-item">
                                <div class="matrix-info">
                                    <b>Pharmacy & Dispensing</b>
                                    <span>Hospital In-House Dispensary</span>
                                </div>
                                <span class="matrix-badge bg-green">100% Operational</span>
                            </div>
                        </div>
                    </div>

                    <!-- NOTIFY FORM -->
                    <div class="notify-box">
                        <h4 class="notify-title">Get notified when online</h4>
                        <p v-if="!isSubscribed">Enter your email address to receive an instant automated ping when MediFlow web services resume full operation.</p>

                        <div v-if="!isSubscribed" class="notify-form" @submit.prevent="handleSubscribe">
                            <input v-model="emailInput" type="email" placeholder="Enter your email address..." class="notify-input" required />
                            <button type="button" class="btn-notify" @click="handleSubscribe">Notify Me</button>
                        </div>

                        <div v-else class="subscribe-success font-sans">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Thank you! We will notify <b>{{ emailInput }}</b> the moment maintenance completes.</span>
                        </div>
                    </div>

                    <div class="card-bottom">
                        <Link href="/" class="home-link font-sans">← Return to Public Homepage</Link>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.maintenance-page { padding: 48px 0 80px 0; background: var(--cream); min-height: 80vh; display: flex; align-items: center; }
.wrap { max-width: 860px; margin: 0 auto; padding: 0 24px; width: 100%; }

.maintenance-card { background: var(--card); border: 1px solid var(--line); border-radius: 32px; padding: 52px 48px; box-shadow: var(--shadow-card); position: relative; overflow: hidden; }
@media (max-width: 640px) { .maintenance-card { padding: 36px 24px; } }

.badge-row { display: flex; justify-content: center; margin-bottom: 20px; }
.status-pill { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; padding: 6px 16px; border-radius: 999px; }
.warning-pill { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.pulse-amber { width: 8px; height: 8px; border-radius: 50%; background: #D97706; box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.2); animation: pulse 1.8s infinite; }
@keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(217, 119, 6, 0); } 100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); } }

.main-title { font-size: clamp(1.8rem, 1.4rem + 1.5vw, 2.5rem); font-weight: 800; color: var(--forest); text-align: center; margin: 0 0 12px 0; letter-spacing: -0.02em; line-height: 1.2; }
.subtitle { font-size: 15.5px; color: var(--ink-muted); text-align: center; max-width: 600px; margin: 0 auto 36px; line-height: 1.6; }

/* PROGRESS BOX */
.progress-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; margin-bottom: 32px; }
.progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 13.5px; font-weight: 700; }
.progress-label { color: var(--forest); }
.progress-val { font-family: var(--font-mono); color: #B45309; }

.progress-track { height: 10px; background: var(--card); border-radius: 999px; overflow: hidden; border: 1px solid var(--line); margin-bottom: 12px; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #B45309 0%, var(--lime) 100%); border-radius: 999px; transition: width 400ms ease; }

.progress-footer { display: flex; justify-content: space-between; font-size: 12.5px; color: var(--ink-muted); flex-wrap: wrap; gap: 8px; }
.progress-footer b { color: var(--forest); font-family: var(--font-mono); }

/* EMERGENCY CALLOUT */
.emergency-callout { background: #FEF2F2; border: 1px solid #FCA5A5; border-radius: var(--radius-xl); padding: 24px; display: flex; gap: 18px; align-items: flex-start; margin-bottom: 32px; }
.emergency-icon { width: 44px; height: 44px; border-radius: 50%; background: #DC2626; color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.emergency-icon svg { width: 22px; height: 22px; }

.emergency-text h4 { font-size: 16px; font-weight: 800; color: #991B1B; margin: 0 0 4px 0; }
.emergency-text p { font-size: 13.5px; color: #7F1D1D; margin: 0 0 14px 0; line-height: 1.45; }

.contact-chips { display: flex; flex-wrap: wrap; gap: 10px; }
.phone-chip { background: #DC2626; color: #ffffff; padding: 6px 14px; border-radius: 999px; font-size: 12.5px; font-weight: 700; text-decoration: none; transition: background 150ms ease; }
.phone-chip:hover { background: #B91C1C; }
.location-chip { background: rgba(220, 38, 38, 0.1); color: #991B1B; padding: 6px 14px; border-radius: 999px; font-size: 12.5px; font-weight: 600; border: 1px solid rgba(220, 38, 38, 0.2); }

/* SERVICE MATRIX */
.service-matrix { margin-bottom: 36px; }
.matrix-title { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); margin: 0 0 14px 0; }
.matrix-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
@media (max-width: 600px) { .matrix-grid { grid-template-columns: 1fr; } }

.matrix-item { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
.matrix-info b { font-size: 13.5px; color: var(--forest); display: block; }
.matrix-info span { font-size: 11.5px; color: var(--ink-muted); display: block; }

.matrix-badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; white-space: nowrap; }
.bg-amber { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.bg-green { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

/* NOTIFY BOX */
.notify-box { background: var(--forest); color: #ffffff; border-radius: var(--radius-xl); padding: 28px; text-align: center; margin-bottom: 24px; }
.notify-title { font-size: 17px; font-weight: 800; margin: 0 0 6px 0; }
.notify-box p { font-size: 13.5px; opacity: 0.85; margin: 0 0 18px 0; max-width: 500px; margin-inline: auto; }

.notify-form { display: flex; gap: 8px; max-width: 480px; margin: 0 auto; }
.notify-input { flex: 1; height: 44px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); padding: 0 18px; font-size: 14px; color: #ffffff; outline: none; }
.notify-input::placeholder { color: rgba(255,255,255,0.6); }
.btn-notify { height: 44px; padding: 0 22px; border-radius: 999px; background: var(--lime); color: var(--lime-text); font-size: 13.5px; font-weight: 700; border: none; cursor: pointer; transition: all 150ms ease; flex-shrink: 0; }
.btn-notify:hover { background: #d2e85a; transform: translateY(-1px); }

.subscribe-success { display: inline-flex; align-items: center; gap: 10px; background: rgba(221,241,92,0.15); border: 1px solid rgba(221,241,92,0.3); color: var(--lime); padding: 10px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; }

.card-bottom { text-align: center; }
.home-link { font-size: 13.5px; font-weight: 700; color: var(--forest); text-decoration: none; }
.home-link:hover { text-decoration: underline; }
</style>
