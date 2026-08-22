<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const slotDuration = ref('30')
const bufferTime = ref('5')
const autoConfirm = ref(true)

const schedule = ref([
    { day: 'Monday', active: true, startTime: '08:30', endTime: '17:00', maxPatients: 16 },
    { day: 'Tuesday', active: true, startTime: '08:30', endTime: '17:00', maxPatients: 16 },
    { day: 'Wednesday', active: true, startTime: '08:30', endTime: '13:00', maxPatients: 8 },
    { day: 'Thursday', active: true, startTime: '08:30', endTime: '17:00', maxPatients: 16 },
    { day: 'Friday', active: true, startTime: '08:30', endTime: '17:00', maxPatients: 16 },
    { day: 'Saturday', active: false, startTime: '09:00', endTime: '13:00', maxPatients: 8 },
    { day: 'Sunday', active: false, startTime: '09:00', endTime: '13:00', maxPatients: 8 },
])

function saveSchedule() {
    alert('Weekly recurring schedule saved successfully!')
}
</script>

<template>
    <Head title="Availability & Schedule" />

    <!-- PAGE HEADER -->
    <div class="page-title-row">
        <div>
            <h2>Weekly Availability & Working Hours</h2>
            <p>Configure recurring office hours, consultation slot duration, and booking parameters</p>
        </div>

        <Link href="/doctor/schedule/exceptions" class="btn btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><line x1="9" y1="14" x2="15" y2="14"/>
            </svg>
            Schedule Exceptions & Leave
        </Link>
    </div>

    <!-- MAIN SCHEDULE CARD -->
    <div class="form-card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Recurring Weekly Hours
            </h3>
        </div>

        <form @submit.prevent="saveSchedule">
            <div class="days-list">
                <div v-for="item in schedule" :key="item.day" class="day-row" :class="{ inactive: !item.active }">
                    <div class="day-label-col">
                        <label class="switch-toggle">
                            <input v-model="item.active" type="checkbox" />
                            <span class="slider"></span>
                        </label>
                        <b>{{ item.day }}</b>
                    </div>

                    <div v-if="item.active" class="day-times-col">
                        <div class="time-group">
                            <label>Start Time</label>
                            <input v-model="item.startTime" type="time" class="form-control mono" />
                        </div>
                        <span>to</span>
                        <div class="time-group">
                            <label>End Time</label>
                            <input v-model="item.endTime" type="time" class="form-control mono" />
                        </div>
                    </div>
                    <div v-else class="off-label">
                        <span>Day Off / Unavailable</span>
                    </div>

                    <div v-if="item.active" class="max-patients-col">
                        <label>Max Patients</label>
                        <input v-model="item.maxPatients" type="number" class="form-control mono" style="width: 80px;" />
                    </div>
                </div>
            </div>

            <!-- SLOT DURATION & SETTINGS BLOCK -->
            <div class="settings-block">
                <h4 class="section-title">Consultation Slot Settings</h4>

                <div class="settings-grid">
                    <div class="form-group">
                        <label>Slot Duration (Minutes)</label>
                        <select v-model="slotDuration" class="form-control">
                            <option value="15">15 Minutes</option>
                            <option value="20">20 Minutes</option>
                            <option value="30">30 Minutes (Default)</option>
                            <option value="45">45 Minutes</option>
                            <option value="60">60 Minutes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Buffer Between Appointments</label>
                        <select v-model="bufferTime" class="form-control">
                            <option value="0">No Buffer</option>
                            <option value="5">5 Minutes (Recommended)</option>
                            <option value="10">10 Minutes</option>
                        </select>
                    </div>

                    <div class="form-group flex-row">
                        <label class="switch-toggle">
                            <input v-model="autoConfirm" type="checkbox" />
                            <span class="slider"></span>
                        </label>
                        <div>
                            <b>Auto-Confirm Bookings</b>
                            <span style="font-size: 11.5px; color: var(--ink-muted); display: block;">Automatically accept patient bookings if slot is open</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Recurring Schedule Settings</button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.page-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}
.page-title-row h2 { font-size: 22px; font-weight: 800; color: var(--forest); }
.page-title-row p { font-size: 13px; color: var(--ink-muted); }

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow-card);
}

.card-header {
    border-bottom: 1px solid var(--line);
    padding-bottom: 16px;
    margin-bottom: 24px;
}
.card-header h3 { font-size: 18px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.days-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }

.day-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-radius: var(--radius-lg);
    background: var(--cream);
    border: 1px solid var(--line);
    transition: all 150ms ease;
    flex-wrap: wrap;
    gap: 16px;
}
.day-row.inactive { background: var(--card); opacity: 0.7; }

.day-label-col { display: flex; align-items: center; gap: 14px; width: 140px; }
.day-label-col b { font-size: 15px; font-weight: 700; color: var(--forest); }

.day-times-col { display: flex; align-items: center; gap: 12px; }
.time-group label { font-size: 11px; font-weight: 700; color: var(--ink-muted); text-transform: uppercase; display: block; margin-bottom: 2px; }
.off-label span { font-size: 13px; font-weight: 600; color: var(--ink-muted); font-style: italic; }

.max-patients-col label { font-size: 11px; font-weight: 700; color: var(--ink-muted); text-transform: uppercase; display: block; margin-bottom: 2px; }

.switch-toggle { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.switch-toggle input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--line); transition: .2s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .2s; border-radius: 50%; }
input:checked + .slider { background-color: var(--forest); }
input:checked + .slider:before { transform: translateX(20px); }

.settings-block { border-top: 1px solid var(--line); padding-top: 24px; margin-bottom: 24px; }
.section-title { font-size: 14px; font-weight: 800; text-transform: uppercase; color: var(--forest); margin-bottom: 16px; border-left: 3px solid var(--lime); padding-left: 10px; }

.settings-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    align-items: center;
}
@media (max-width: 900px) { .settings-grid { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group.flex-row { flex-direction: row; align-items: center; gap: 12px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }

.form-control {
    height: 42px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--card);
    padding: 0 14px;
    font-size: 13.5px;
    color: var(--ink);
}
.form-control.mono { font-family: var(--font-mono); }

.form-actions { display: flex; justify-content: flex-end; border-top: 1px solid var(--line); padding-top: 20px; }

.btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
