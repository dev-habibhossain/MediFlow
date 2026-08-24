<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const preferences = ref({
    apptReminders: { email: true, sms: true, push: true },
    apptBookings: { email: true, sms: true, push: true },
    rxUploaded: { email: true, sms: false, push: true },
    labReady: { email: true, sms: true, push: true },
    healthNews: { email: false, sms: false, push: false },
})

const showToast = ref(false)

function handleSavePreferences() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}
</script>

<template>
    <Head title="Profile Settings — Notifications" />

    <!-- SETTINGS NAVIGATION TABS -->
    <div class="settings-tabs-row">
        <nav class="settings-nav">
            <Link href="/patient/settings/profile" class="settings-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Personal Info
            </Link>
            <Link href="/patient/settings/security" class="settings-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Security & Password
            </Link>
        </nav>
    </div>

    <!-- MAIN NOTIFICATION PREFERENCES CARD -->
    <div class="settings-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            Notification Channels & Alerts
        </div>

        <form @submit.prevent="handleSavePreferences">
            <!-- GROUP 1: APPOINTMENTS -->
            <div class="pref-group">
                <div class="group-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    Appointments & Consultations
                </div>

                <div class="pref-row">
                    <div class="pref-info">
                        <b>Appointment Reminders</b>
                        <span>Get notified 24h and 2h before your scheduled visit</span>
                    </div>
                    <div class="channel-switches">
                        <div class="channel-item">
                            Email
                            <label class="switch"><input v-model="preferences.apptReminders.email" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            SMS
                            <label class="switch"><input v-model="preferences.apptReminders.sms" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            Push
                            <label class="switch"><input v-model="preferences.apptReminders.push" type="checkbox" /><span class="slider"></span></label>
                        </div>
                    </div>
                </div>

                <div class="pref-row">
                    <div class="pref-info">
                        <b>Booking Confirmations & Changes</b>
                        <span>Instant alerts when a visit is booked, rescheduled, or cancelled</span>
                    </div>
                    <div class="channel-switches">
                        <div class="channel-item">
                            Email
                            <label class="switch"><input v-model="preferences.apptBookings.email" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            SMS
                            <label class="switch"><input v-model="preferences.apptBookings.sms" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            Push
                            <label class="switch"><input v-model="preferences.apptBookings.push" type="checkbox" /><span class="slider"></span></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GROUP 2: PRESCRIPTIONS & LABS -->
            <div class="pref-group">
                <div class="group-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    Medical Records & Prescriptions
                </div>

                <div class="pref-row">
                    <div class="pref-info">
                        <b>New Prescription Uploaded</b>
                        <span>Receive digital prescription copy as soon as doctor issues it</span>
                    </div>
                    <div class="channel-switches">
                        <div class="channel-item">
                            Email
                            <label class="switch"><input v-model="preferences.rxUploaded.email" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            SMS
                            <label class="switch"><input v-model="preferences.rxUploaded.sms" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            Push
                            <label class="switch"><input v-model="preferences.rxUploaded.push" type="checkbox" /><span class="slider"></span></label>
                        </div>
                    </div>
                </div>

                <div class="pref-row">
                    <div class="pref-info">
                        <b>Lab Results Ready</b>
                        <span>Alerts when diagnostic test reports are published to your portal</span>
                    </div>
                    <div class="channel-switches">
                        <div class="channel-item">
                            Email
                            <label class="switch"><input v-model="preferences.labReady.email" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            SMS
                            <label class="switch"><input v-model="preferences.labReady.sms" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            Push
                            <label class="switch"><input v-model="preferences.labReady.push" type="checkbox" /><span class="slider"></span></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GROUP 3: MARKETING & TIPS -->
            <div class="pref-group">
                <div class="group-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                    Health Tips & Updates
                </div>

                <div class="pref-row">
                    <div class="pref-info">
                        <b>Seasonal Health News & Articles</b>
                        <span>Monthly wellness newsletter and preventive care guidelines</span>
                    </div>
                    <div class="channel-switches">
                        <div class="channel-item">
                            Email
                            <label class="switch"><input v-model="preferences.healthNews.email" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            SMS
                            <label class="switch"><input v-model="preferences.healthNews.sms" type="checkbox" /><span class="slider"></span></label>
                        </div>
                        <div class="channel-item">
                            Push
                            <label class="switch"><input v-model="preferences.healthNews.push" type="checkbox" /><span class="slider"></span></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="btn-row">
                <button type="button" class="btn btn-outline" @click="location.reload()">Reset Defaults</button>
                <button type="submit" class="btn btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Notification preferences saved successfully!
    </div>
</template>

<style scoped>
.settings-tabs-row { border-bottom: 1px solid var(--line); padding-bottom: 12px; margin-bottom: 24px; }
.settings-nav { display: flex; gap: 8px; flex-wrap: wrap; }
.settings-link { padding: 10px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; color: var(--ink-muted); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 8px; }
.settings-link:hover { color: var(--ink); background: var(--card); }
.settings-link.active { background: var(--forest); color: #fff; }

.settings-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); }
@media (max-width: 600px) { .settings-card { padding: 24px; } }

.card-title { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 14px; }
.card-title svg { width: 20px; height: 20px; color: var(--forest); }

.pref-group { margin-bottom: 28px; }
.pref-group:last-child { margin-bottom: 0; }
.group-title { font-size: 14px; font-weight: 800; color: var(--forest); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }

.pref-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); margin-bottom: 10px; gap: 16px; flex-wrap: wrap; }
.pref-row:last-child { margin-bottom: 0; }

.pref-info { flex: 1; min-width: 240px; }
.pref-info b { font-size: 14.5px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 2px; }
.pref-info span { font-size: 12.5px; color: var(--ink-muted); display: block; }

.channel-switches { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.channel-item { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--ink-muted); }

.switch { position: relative; display: inline-block; width: 42px; height: 24px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background-color: var(--line); border-radius: 999px; transition: .2s; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s; box-shadow: var(--shadow-sm); }
input:checked + .slider { background-color: var(--forest); }
input:checked + .slider:before { transform: translateX(18px); }

.btn-row { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; }
</style>
