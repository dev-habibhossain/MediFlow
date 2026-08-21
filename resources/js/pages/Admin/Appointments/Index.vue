<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface AppointmentItem {
    id: number | string
    code: string
    patient_name: string
    doctor_name: string
    department: string
    date_time: string
    status: 'confirmed' | 'progress' | 'completed' | 'cancelled'
}

const props = withDefaults(
    defineProps<{
        appointments?: AppointmentItem[]
    }>(),
    {
        appointments: () => [
            {
                id: 101,
                code: 'MDF-101',
                patient_name: 'Habib Hossain',
                doctor_name: 'Dr. Sarah Jenkins',
                department: 'Cardiology',
                date_time: 'Aug 7, 2026 · 10:00 AM',
                status: 'confirmed',
            },
            {
                id: 102,
                code: 'MDF-102',
                patient_name: 'Tanjila Ahmed',
                doctor_name: 'Dr. Marcus Vance',
                department: 'Neurology',
                date_time: 'Aug 7, 2026 · 11:30 AM',
                status: 'progress',
            },
            {
                id: 881,
                code: 'MDF-881',
                patient_name: 'Robert Fox',
                doctor_name: 'Dr. Emily Watson',
                department: 'Pediatrics',
                date_time: 'July 14, 2026 · 02:30 PM',
                status: 'completed',
            },
            {
                id: 720,
                code: 'MDF-720',
                patient_name: 'Alicia Keys',
                doctor_name: 'Dr. Alan Grant',
                department: 'Orthopedics',
                date_time: 'June 20, 2026 · 09:00 AM',
                status: 'cancelled',
            },
        ],
    }
)

const activeTab = ref<string>('all')
const selectedDept = ref<string>('all')
const searchQuery = ref<string>('')

const filteredAppointments = computed(() => {
    return props.appointments.filter((app) => {
        const matchesTab = activeTab.value === 'all' || app.status === activeTab.value
        const matchesDept = selectedDept.value === 'all' || app.department === selectedDept.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery =
            !q ||
            app.code.toLowerCase().includes(q) ||
            app.patient_name.toLowerCase().includes(q) ||
            app.doctor_name.toLowerCase().includes(q) ||
            app.department.toLowerCase().includes(q)

        return matchesTab && matchesDept && matchesQuery
    })
})

const countAll = computed(() => props.appointments.length)
const countConfirmed = computed(() => props.appointments.filter((a) => a.status === 'confirmed').length)
const countProgress = computed(() => props.appointments.filter((a) => a.status === 'progress').length)
const countCompleted = computed(() => props.appointments.filter((a) => a.status === 'completed').length)
const countCancelled = computed(() => props.appointments.filter((a) => a.status === 'cancelled').length)
</script>

<template>
    <Head title="All Appointments Oversight — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Hospital Appointments Oversight</h1>
            <p class="text-xs text-[var(--ink-muted)]">Monitor, filter, and oversee all hospital-wide consultation bookings</p>
        </div>
    </div>

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                All <span class="tab-badge">{{ countAll }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'confirmed' }" @click="activeTab = 'confirmed'">
                Confirmed <span class="tab-badge">{{ countConfirmed }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'progress' }" @click="activeTab = 'progress'">
                In Progress <span class="tab-badge">{{ countProgress }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'completed' }" @click="activeTab = 'completed'">
                Completed <span class="tab-badge">{{ countCompleted }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'cancelled' }" @click="activeTab = 'cancelled'">
                Cancelled <span class="tab-badge">{{ countCancelled }}</span>
            </button>
        </div>

        <div class="filter-controls">
            <select v-model="selectedDept" class="select-filter">
                <option value="all">All Departments</option>
                <option value="Cardiology">Cardiology</option>
                <option value="Neurology">Neurology</option>
                <option value="Pediatrics">Pediatrics</option>
                <option value="Orthopedics">Orthopedics</option>
            </select>

            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input v-model="searchQuery" type="text" class="search-input" placeholder="Search ref, patient, or doctor..." />
            </div>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ref ID</th>
                        <th>Patient Name</th>
                        <th>Assigned Doctor</th>
                        <th>Department</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="app in filteredAppointments" :key="app.id">
                        <td><span class="ref-code">#{{ app.code }}</span></td>
                        <td><b>{{ app.patient_name }}</b></td>
                        <td>{{ app.doctor_name }}</td>
                        <td>{{ app.department }}</td>
                        <td style="font-family: var(--font-mono); font-size: 12.5px;">{{ app.date_time }}</td>
                        <td>
                            <span
                                class="status-badge"
                                :class="{
                                    'status-confirmed': app.status === 'confirmed',
                                    'status-progress': app.status === 'progress',
                                    'status-completed': app.status === 'completed',
                                    'status-cancelled': app.status === 'cancelled',
                                }"
                            >
                                {{ app.status.charAt(0).toUpperCase() + app.status.slice(1) }}
                            </span>
                        </td>
                        <td>
                            <Link :href="`/admin/appointments/${app.id}`" class="btn-action-icon" title="View & Reassign Appointment">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                </svg>
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredAppointments.length === 0">
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No appointments found matching your search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style>
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }
.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); }
.tab-btn { padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: 0; background: transparent; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 6px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

.filter-controls { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.search-box { position: relative; width: 260px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); transition: border-color 150ms ease; outline: none; }
.search-input:focus { border-color: var(--forest); }
.select-filter { height: 40px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px; font-size: 13.5px; font-weight: 600; color: var(--ink); cursor: pointer; outline: none; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 20px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 20px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.ref-code { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; color: var(--forest); }
.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-progress { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.status-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.status-cancelled { background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); display: inline-flex; align-items: center; justify-content: center; color: var(--forest); transition: all 150ms ease; text-decoration: none; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
