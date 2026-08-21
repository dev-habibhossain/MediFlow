<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Holiday {
    id: number
    title: string
    date: string
}

const holidays = ref<Holiday[]>([
    {
        id: 1,
        title: 'National Independence Day',
        date: 'March 26, 2026',
    },
    {
        id: 2,
        title: 'Eid-ul-Fitr Institutional Break',
        date: 'May 22, 2026',
    },
])

const title = ref('')
const dateInput = ref('')
const showToast = ref(false)
const toastMsg = ref('')

function triggerToast(msg: string) {
    toastMsg.value = msg
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2500)
}

function handleAddHoliday() {
    if (!title.value || !dateInput.value) return

    const dateObj = new Date(dateInput.value)
    const formattedDate = !isNaN(dateObj.getTime())
        ? dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
        : dateInput.value

    holidays.value.unshift({
        id: Date.now(),
        title: title.value,
        date: formattedDate,
    })

    title.value = ''
    dateInput.value = ''
    triggerToast('Hospital holiday added successfully.')
}

function removeHoliday(id: number) {
    if (confirm('Remove this holiday closure?')) {
        holidays.value = holidays.value.filter((h) => h.id !== id)
        triggerToast('Holiday removed from calendar.')
    }
}
</script>

<template>
    <Head title="Hospital Holidays Calendar — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Hospital Holiday Calendar</h1>
            <p class="text-xs text-[var(--ink-muted)]">Block out institutional holidays and non-working dates across the hospital</p>
        </div>
    </div>

    <!-- ADD HOLIDAY CARD -->
    <div class="card-shell mb-6">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Add New Hospital Holiday
        </div>

        <form @submit.prevent="handleAddHoliday">
            <div class="form-grid">
                <div class="form-group">
                    <label for="holidayTitle">Holiday Name / Description</label>
                    <input id="holidayTitle" v-model="title" type="text" class="form-control" required placeholder="e.g. Independence Day Closure" />
                </div>

                <div class="form-group">
                    <label for="holidayDate">Holiday Date</label>
                    <input id="holidayDate" v-model="dateInput" type="date" class="form-control" required />
                </div>

                <div>
                    <button type="submit" class="btn-primary">Add Holiday</button>
                </div>
            </div>
        </form>
    </div>

    <!-- HOLIDAYS TABLE CARD -->
    <div class="card-shell" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Holiday Title</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="holiday in holidays" :key="holiday.id">
                        <td><b>{{ holiday.title }}</b></td>
                        <td style="font-family: var(--font-mono); font-size: 13px;">{{ holiday.date }}</td>
                        <td><span style="color: #15803D; font-weight: 700; font-size: 12px;">● Active Closure</span></td>
                        <td><button class="btn-delete-holiday" @click="removeHoliday(holiday.id)">Remove</button></td>
                    </tr>

                    <tr v-if="holidays.length === 0">
                        <td colspan="4" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No hospital holidays configured.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        <span>{{ toastMsg }}</span>
    </div>
</template>

<style>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: flex-end; }
@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }

.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 24px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; white-space: nowrap; }
.btn-primary:hover { background: var(--forest-2); }

.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.btn-delete-holiday { height: 34px; padding: 0 14px; border-radius: var(--radius-sm); border: 1px solid #FCA5A5; background: #FEF2F2; color: #DC2626; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all 150ms ease; }
.btn-delete-holiday:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
