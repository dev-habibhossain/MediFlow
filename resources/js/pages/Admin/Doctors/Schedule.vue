<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = withDefaults(
    defineProps<{
        doctor?: {
            id: number
            name: string
        }
    }>(),
    {
        doctor: () => ({
            id: 901,
            name: 'Dr. Sarah Jenkins',
        }),
    }
)

const days = ref([
    { key: 'mon', name: 'Monday', active: true, start: '09:00', break: '13:00', end: '17:00' },
    { key: 'tue', name: 'Tuesday', active: true, start: '09:00', break: '13:00', end: '17:00' },
    { key: 'wed', name: 'Wednesday', active: true, start: '09:00', break: '13:00', end: '17:00' },
    { key: 'thu', name: 'Thursday', active: true, start: '09:00', break: '13:00', end: '17:00' },
    { key: 'fri', name: 'Friday', active: true, start: '09:00', break: '12:30', end: '16:00' },
    { key: 'sat', name: 'Saturday', active: false, start: '10:00', break: '13:00', end: '14:00' },
    { key: 'sun', name: 'Sunday', active: false, start: '10:00', break: '13:00', end: '14:00' },
])

const form = useForm({
    schedule: days.value,
})

function saveSchedule() {
    form.post(`/admin/doctors/${props.doctor.id}/schedule`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="`Admin Schedule Override — ${props.doctor.name}`" />

    <!-- BACK BUTTON -->
    <div class="mb-6">
        <Link :href="`/admin/doctors/${props.doctor.id}`" class="back-btn">← Back to Doctor Profile (#{{ props.doctor.id }})</Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="schedule-header-card mb-6">
        <div>
            <span class="ref-badge">Admin Schedule Override Console</span>
            <h1>Doctor Schedule Override: {{ props.doctor.name }}</h1>
        </div>
    </div>

    <!-- OVERRIDE NOTICE -->
    <div class="admin-override-notice mb-6">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        <div>
            <b>Administrator Schedule Override Privileges</b><br />
            Any modifications made here will directly override {{ props.doctor.name }}'s personal availability template and update live patient booking slots. Use with caution.
        </div>
    </div>

    <!-- SCHEDULE CARD -->
    <div class="card-shell">
        <div class="card-title">
            <div class="card-title-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />
                </svg>
                Weekly Recurring Working Hours Template (Admin View)
            </div>
        </div>

        <form @submit.prevent="saveSchedule">
            <div class="days-editor-list">
                <div v-for="day in days" :key="day.key" class="day-row" :class="{ off: !day.active }">
                    <div class="day-name">{{ day.name }}</div>
                    <div>
                        <label class="switch">
                            <input v-model="day.active" type="checkbox" />
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="time-box">
                        <label>Shift Start</label>
                        <input v-model="day.start" type="time" class="time-input" :disabled="!day.active" />
                    </div>
                    <div class="time-box">
                        <label>Break Time</label>
                        <input v-model="day.break" type="time" class="time-input" :disabled="!day.active" />
                    </div>
                    <div class="time-box">
                        <label>Shift End</label>
                        <input v-model="day.end" type="time" class="time-input" :disabled="!day.active" />
                    </div>
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link :href="`/admin/doctors/${props.doctor.id}`" class="btn btn-outline">Cancel</Link>
                <button type="submit" :disabled="form.processing" class="btn btn-primary">
                    {{ form.processing ? 'Saving...' : 'Save Admin Schedule Override' }}
                </button>
            </div>
        </form>
    </div>
</template>

<style>
.back-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 6px 14px; border-radius: 999px; transition: all 150ms ease; text-decoration: none; }
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.schedule-header-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px 32px; box-shadow: var(--shadow-card); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
.ref-badge { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.schedule-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; }

.admin-override-notice { background: #FEF3C7; border: 1px solid #FDE68A; border-radius: var(--radius-lg); padding: 18px 20px; color: #B45309; display: flex; align-items: flex-start; gap: 14px; font-size: 13.5px; line-height: 1.45; }
.admin-override-notice svg { width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; flex-wrap: wrap; gap: 10px; }
.card-title-text { display: flex; align-items: center; gap: 10px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.days-editor-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; }
.day-row { display: grid; grid-template-columns: 140px 100px 1fr 1fr 1fr; gap: 16px; align-items: center; padding: 14px 20px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); }
@media (max-width: 860px) { .day-row { grid-template-columns: 1fr 1fr; } }
.day-row.off { opacity: 0.65; background: var(--cream-alt); }
.day-name { font-size: 14.5px; font-weight: 800; color: var(--forest); }

.time-box { display: flex; flex-direction: column; gap: 4px; }
.time-box label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); }
.time-input { height: 38px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--card); padding: 0 10px; font-size: 13px; font-family: var(--font-mono); color: var(--ink); outline: none; }
.time-input:disabled { opacity: 0.5; cursor: not-allowed; }

.switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background-color: var(--line); border-radius: 999px; transition: .2s; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .2s; box-shadow: var(--shadow-sm); }
input:checked + .slider { background-color: var(--forest); }
input:checked + .slider:before { transform: translateX(20px); }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 46px; padding: 0 28px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; cursor: pointer; text-decoration: none; border: 0; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
