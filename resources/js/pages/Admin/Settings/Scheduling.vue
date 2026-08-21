<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const slotDuration = ref('30')
const bufferTime = ref('5')
const advanceBookingDays = ref('30')
const cancellationWindow = ref('24')

const showToast = ref(false)

function handleSave() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}
</script>

<template>
    <Head title="Scheduling Settings — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Scheduling & Booking Policies</h1>
            <p class="text-xs text-[var(--ink-muted)]">Configure appointment slot durations, buffer intervals, and cancellation rules</p>
        </div>
    </div>

    <div class="card-shell">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
            </svg>
            Appointment Slot & Cancellation Parameters
        </div>

        <form @submit.prevent="handleSave">
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
                <button type="submit" class="btn-primary">Save Scheduling Policies</button>
            </div>
        </form>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        Scheduling policies updated successfully!
    </div>
</template>

<style>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); max-width: 960px; margin: 0 auto; }
@media (max-width: 600px) { .card-shell { padding: 20px; } }

.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }

.form-actions { display: flex; justify-content: flex-end; padding-top: 20px; border-top: 1px solid var(--line); }
.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 28px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; }
.btn-primary:hover { background: var(--forest-2); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
