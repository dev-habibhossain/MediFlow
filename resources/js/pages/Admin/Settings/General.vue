<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const hospitalName = ref('MediFlow Hospital & Research Center')
const supportEmail = ref('support@mediflow.com')
const contactPhone = ref('+1 (800) 555-MEDI')
const timezone = ref('UTC')
const address = ref('104 Medical Plaza Drive, Health Sciences District, Suite 400')

const showToast = ref(false)

function handleSave() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}
</script>

<template>
    <Head title="General Settings — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">General Hospital Settings</h1>
            <p class="text-xs text-[var(--ink-muted)]">Manage core hospital branding, contact details, and location metadata</p>
        </div>
    </div>

    <div class="card-shell">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
            </svg>
            Hospital Information & Branding
        </div>

        <form @submit.prevent="handleSave">
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

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        General settings updated successfully!
    </div>
</template>

<style>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); max-width: 960px; margin: 0 auto; }
@media (max-width: 600px) { .card-shell { padding: 20px; } }

.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: 90px; padding: 12px 16px; resize: vertical; }

.form-actions { display: flex; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--line); }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 28px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; }
.btn-primary:hover { background: var(--forest-2); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
