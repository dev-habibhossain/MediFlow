<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const templateTrigger = ref('appointment_booked')
const emailSubject = ref('Your MediFlow Consultation is Confirmed — #[appointment_id]')
const templateBody = ref(`Dear [patient_name],

Your consultation with Dr. [doctor_name] ([department_name]) has been successfully scheduled for [appointment_date] at [appointment_time].

Please arrive 10 minutes prior to your visit or log in to your patient portal for virtual consultations.

Regards,
MediFlow Hospital System`)

const showToast = ref(false)

function handleSave() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}
</script>

<template>
    <Head title="Notification Templates — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Notification Templates</h1>
            <p class="text-xs text-[var(--ink-muted)]">Customize email and SMS templates for automated patient consultations</p>
        </div>
    </div>

    <div class="card-shell">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
            </svg>
            Edit Template: Appointment Confirmation (Email)
        </div>

        <form @submit.prevent="handleSave">
            <div class="form-group">
                <label for="templateSelect">Select Template Trigger</label>
                <select id="templateSelect" v-model="templateTrigger" class="form-control">
                    <option value="appointment_booked">Appointment Confirmation (Email)</option>
                    <option value="appointment_reminder">Appointment Reminder (SMS)</option>
                    <option value="password_reset">Password Reset (Email)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="emailSubject">Message Subject Line</label>
                <input id="emailSubject" v-model="emailSubject" type="text" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="templateBody">Template Body Content</label>
                <textarea id="templateBody" v-model="templateBody" class="form-control" required></textarea>

                <div style="margin-top: 8px;">
                    <span style="font-size: 12px; font-weight: 700; color: var(--ink-muted);">Available Dynamic Variables:</span>
                    <div class="variables-pill-box">
                        <span class="var-pill">[patient_name]</span>
                        <span class="var-pill">[doctor_name]</span>
                        <span class="var-pill">[department_name]</span>
                        <span class="var-pill">[appointment_date]</span>
                        <span class="var-pill">[appointment_time]</span>
                        <span class="var-pill">[appointment_id]</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Template Changes</button>
            </div>
        </form>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        Notification template updated successfully!
    </div>
</template>

<style>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); max-width: 960px; margin: 0 auto; }
@media (max-width: 600px) { .card-shell { padding: 20px; } }

.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-group { margin-bottom: 20px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: 140px; padding: 14px 16px; resize: vertical; font-family: var(--font-mono); font-size: 13px; }

.variables-pill-box { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.var-pill { font-family: var(--font-mono); font-size: 11.5px; font-weight: 600; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 3px 8px; border-radius: var(--radius-sm); }

.form-actions { display: flex; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--line); }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 28px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; }
.btn-primary:hover { background: var(--forest-2); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
