<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const activeTab = ref<'general' | 'scheduling' | 'notifications' | 'holidays' | 'security'>('general')

// General state
const hospitalName = ref('MediFlow Hospital & Research Center')
const supportEmail = ref('support@mediflow.com')
const contactPhone = ref('+1 (800) 555-MEDI')
const timezone = ref('UTC')
const address = ref('104 Medical Plaza Drive, Health Sciences District, Suite 400')

// Scheduling state
const slotDuration = ref('30')
const bufferTime = ref('5')
const advanceBookingDays = ref('30')
const cancellationWindow = ref('24')

// Notifications state
const templateTrigger = ref('appointment_booked')
const emailSubject = ref('Your MediFlow Consultation is Confirmed — #[appointment_id]')

// Security state
const sessionTimeout = ref('60')
const require2FA = ref(true)
const passwordMinLength = ref('8')

const showToast = ref(false)
const toastMsg = ref('')

function triggerToast(msg: string) {
    toastMsg.value = msg
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}

function saveSettings() {
    triggerToast('Settings updated successfully!')
}
</script>

<template>
    <Head title="System Settings — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">System Settings Control Center</h1>
            <p class="text-xs text-[var(--ink-muted)]">Configure hospital identity, scheduling policies, notification triggers, and security controls</p>
        </div>

        <div class="flex gap-2">
            <Link href="/admin/settings/general" class="btn-sub-link">Full General View</Link>
            <Link href="/admin/settings/scheduling" class="btn-sub-link">Full Scheduling View</Link>
        </div>
    </div>

    <!-- SETTINGS MODULE NAVIGATION CARDS -->
    <div class="settings-nav-grid mb-8">
        <div class="nav-card" :class="{ active: activeTab === 'general' }" @click="activeTab = 'general'">
            <div class="card-icon" style="background: var(--forest); color: var(--lime);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
            </div>
            <div class="card-meta">
                <h3>General Settings</h3>
                <p>Branding, contact info & timezone</p>
            </div>
        </div>

        <div class="nav-card" :class="{ active: activeTab === 'scheduling' }" @click="activeTab = 'scheduling'">
            <div class="card-icon" style="background: #E0F2FE; color: #0369A1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
                </svg>
            </div>
            <div class="card-meta">
                <h3>Scheduling & Slots</h3>
                <p>Durations, buffers & cancellations</p>
            </div>
        </div>

        <div class="nav-card" :class="{ active: activeTab === 'notifications' }" @click="activeTab = 'notifications'">
            <div class="card-icon" style="background: #FEF3C7; color: #B45309;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                </svg>
            </div>
            <div class="card-meta">
                <h3>Notifications</h3>
                <p>Email & SMS broadcast triggers</p>
            </div>
        </div>

        <div class="nav-card" :class="{ active: activeTab === 'holidays' }" @click="activeTab = 'holidays'">
            <div class="card-icon" style="background: #DCFCE7; color: #15803D;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
            </div>
            <div class="card-meta">
                <h3>Hospital Holidays</h3>
                <p>Calendar closures & exceptions</p>
            </div>
        </div>

        <div class="nav-card" :class="{ active: activeTab === 'security' }" @click="activeTab = 'security'">
            <div class="card-icon" style="background: #FEE2E2; color: #DC2626;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
            <div class="card-meta">
                <h3>Security & Access</h3>
                <p>Authentication & session rules</p>
            </div>
        </div>
    </div>

    <!-- MAIN FORM PANEL -->
    <div class="card-shell">
        <!-- GENERAL TAB -->
        <div v-if="activeTab === 'general'">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
                Hospital Branding & Information
            </div>

            <form @submit.prevent="saveSettings">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="hospitalName">Hospital Name</label>
                        <input id="hospitalName" v-model="hospitalName" type="text" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="supportEmail">Official Support Email</label>
                        <input id="supportEmail" v-model="supportEmail" type="email" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="contactPhone">Contact Phone Number</label>
                        <input id="contactPhone" v-model="contactPhone" type="text" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="timezone">System Timezone</label>
                        <select id="timezone" v-model="timezone" class="form-control" required>
                            <option value="UTC">UTC (Coordinated Universal Time)</option>
                            <option value="EST">Eastern Standard Time (EST)</option>
                            <option value="PST">Pacific Standard Time (PST)</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Physical Address</label>
                        <textarea id="address" v-model="address" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save General Settings</button>
                </div>
            </form>
        </div>

        <!-- SCHEDULING TAB -->
        <div v-else-if="activeTab === 'scheduling'">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
                </svg>
                Appointment Slot & Booking Parameters
            </div>

            <form @submit.prevent="saveSettings">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="slotDuration">Default Slot Duration</label>
                        <select id="slotDuration" v-model="slotDuration" class="form-control" required>
                            <option value="15">15 Minutes</option>
                            <option value="30">30 Minutes</option>
                            <option value="45">45 Minutes</option>
                            <option value="60">60 Minutes (1 Hour)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bufferTime">Between-Slot Buffer Time</label>
                        <select id="bufferTime" v-model="bufferTime" class="form-control" required>
                            <option value="0">0 Minutes (No buffer)</option>
                            <option value="5">5 Minutes</option>
                            <option value="10">10 Minutes</option>
                            <option value="15">15 Minutes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="advanceBookingDays">Max Advance Booking Window</label>
                        <select id="advanceBookingDays" v-model="advanceBookingDays" class="form-control" required>
                            <option value="14">14 Days Ahead</option>
                            <option value="30">30 Days Ahead</option>
                            <option value="60">60 Days Ahead</option>
                            <option value="90">90 Days Ahead</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cancellationWindow">Min Hours Before Free Cancellation</label>
                        <select id="cancellationWindow" v-model="cancellationWindow" class="form-control" required>
                            <option value="2">2 Hours Prior</option>
                            <option value="12">12 Hours Prior</option>
                            <option value="24">24 Hours Prior</option>
                            <option value="48">48 Hours Prior</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save Scheduling Parameters</button>
                </div>
            </form>
        </div>

        <!-- NOTIFICATIONS TAB -->
        <div v-else-if="activeTab === 'notifications'">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                </svg>
                Broadcast & Automated Email Templates
            </div>

            <form @submit.prevent="saveSettings">
                <div class="form-group">
                    <label for="templateSelect">Select Template Trigger</label>
                    <select id="templateSelect" v-model="templateTrigger" class="form-control">
                        <option value="appointment_booked">Appointment Confirmation (Email)</option>
                        <option value="appointment_reminder">Appointment Reminder (SMS)</option>
                        <option value="password_reset">Password Reset (Email)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="emailSubject">Subject Line</label>
                    <input id="emailSubject" v-model="emailSubject" type="text" class="form-control" required />
                </div>

                <div class="form-actions">
                    <Link href="/admin/settings/notifications" class="btn-primary">Open Full Template Editor</Link>
                </div>
            </form>
        </div>

        <!-- HOLIDAYS TAB -->
        <div v-else-if="activeTab === 'holidays'">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                Hospital Holidays & Closures
            </div>

            <p class="text-sm text-[var(--ink-muted)] mb-6">Manage non-working days and national holiday closures for all hospital departments.</p>

            <div class="form-actions" style="border: none; padding: 0;">
                <Link href="/admin/settings/holidays" class="btn-primary">Manage Holiday Calendar →</Link>
            </div>
        </div>

        <!-- SECURITY TAB -->
        <div v-else-if="activeTab === 'security'">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Authentication & System Security
            </div>

            <form @submit.prevent="saveSettings">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="sessionTimeout">Admin Session Timeout (Minutes)</label>
                        <input id="sessionTimeout" v-model="sessionTimeout" type="number" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="passwordMinLength">Minimum Password Length</label>
                        <select id="passwordMinLength" v-model="passwordMinLength" class="form-control" required>
                            <option value="8">8 Characters</option>
                            <option value="12">12 Characters</option>
                            <option value="16">16 Characters</option>
                        </select>
                    </div>

                    <div class="form-group full-width" style="display: flex; align-items: center; gap: 10px;">
                        <input id="require2FA" v-model="require2FA" type="checkbox" style="width: 18px; height: 18px; accent-color: var(--forest);" />
                        <label for="require2FA" style="margin: 0; cursor: pointer;">Require Two-Factor Authentication (2FA) for Administrative Users</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save Security Parameters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        <span>{{ toastMsg }}</span>
    </div>
</template>

<style>
.btn-sub-link { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 999px; background: var(--card); border: 1px solid var(--line); font-size: 12.5px; font-weight: 700; color: var(--forest); text-decoration: none; transition: all 150ms ease; }
.btn-sub-link:hover { background: var(--forest); color: #fff; border-color: var(--forest); }

.settings-nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
@media (max-width: 1100px) { .settings-nav-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .settings-nav-grid { grid-template-columns: 1fr; } }

.nav-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 16px; cursor: pointer; transition: all 150ms ease; display: flex; flex-direction: column; gap: 12px; }
.nav-card:hover { border-color: var(--forest); transform: translateY(-2px); box-shadow: var(--shadow-card); }
.nav-card.active { border-color: var(--forest); background: var(--cream); box-shadow: var(--shadow-lift); }

.card-icon { width: 40px; height: 40px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.card-icon svg { width: 20px; height: 20px; }

.card-meta h3 { font-size: 13.5px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.card-meta p { font-size: 11px; color: var(--ink-muted); margin: 0; line-height: 1.3; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: 90px; padding: 12px 16px; resize: vertical; }

.form-actions { display: flex; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--line); }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 28px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; text-decoration: none; }
.btn-primary:hover { background: var(--forest-2); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
