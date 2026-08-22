<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const selectedStatus = ref('all')
const searchQuery = ref('')
const selectedDate = ref('2026-08-22')

const appointments = [
    {
        id: 'MDF-9021',
        patient: 'Habib Hossain',
        avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=150',
        phone: '+1 (555) 234-5678',
        date: 'Aug 22, 2026',
        time: '09:30 AM',
        mode: 'In-Person (Room 302)',
        type: 'Hypertension Follow-up',
        fee: '$120.00',
        status: 'in-progress',
        statusLabel: 'In Progress',
    },
    {
        id: 'MDF-9022',
        patient: 'Tanjila Ahmed',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=150',
        phone: '+1 (555) 876-5432',
        date: 'Aug 22, 2026',
        time: '10:15 AM',
        mode: 'Telehealth (Virtual)',
        type: 'Chest Tightness Consultation',
        fee: '$120.00',
        status: 'checked-in',
        statusLabel: 'Checked-In',
    },
    {
        id: 'MDF-8810',
        patient: 'Robert Chen',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150',
        phone: '+1 (555) 345-6789',
        date: 'Aug 22, 2026',
        time: '11:00 AM',
        mode: 'In-Person (Room 302)',
        type: 'Routine Cardiac Screening',
        fee: '$120.00',
        status: 'confirmed',
        statusLabel: 'Confirmed',
    },
    {
        id: 'MDF-8742',
        patient: 'Sophia Martinez',
        avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=150',
        phone: '+1 (555) 987-6543',
        date: 'Aug 22, 2026',
        time: '08:45 AM',
        mode: 'In-Person (Room 302)',
        type: 'Post-op Lipid Panel Review',
        fee: '$120.00',
        status: 'completed',
        statusLabel: 'Completed',
    },
    {
        id: 'MDF-8690',
        patient: 'Michael Vance',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=150',
        phone: '+1 (555) 456-7890',
        date: 'Aug 23, 2026',
        time: '09:00 AM',
        mode: 'In-Person (Room 302)',
        type: 'General Consultation',
        fee: '$120.00',
        status: 'confirmed',
        statusLabel: 'Confirmed',
    },
]

const filteredAppointments = computed(() => {
    return appointments.filter((app) => {
        const matchesStatus = selectedStatus.value === 'all' || app.status === selectedStatus.value
        const matchesSearch =
            app.patient.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            app.id.toLowerCase().includes(searchQuery.value.toLowerCase())
        return matchesStatus && matchesSearch
    })
})
</script>

<template>
    <Head title="My Appointments" />

    <!-- PAGE HEADER -->
    <div class="page-title-row">
        <div>
            <h2>My Physician Appointments</h2>
            <p>View and manage all patient consultations, schedules, and clinical visits</p>
        </div>

        <Link href="/doctor/schedule" class="btn btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Manage Schedule & Slots
        </Link>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-card">
        <div class="filter-group">
            <div class="search-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input v-model="searchQuery" type="text" placeholder="Search by patient name or ID..." class="form-input" />
            </div>

            <div class="select-wrap">
                <select v-model="selectedStatus" class="form-select">
                    <option value="all">All Statuses</option>
                    <option value="in-progress">In Progress</option>
                    <option value="checked-in">Checked-In</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="date-wrap">
                <input v-model="selectedDate" type="date" class="form-input" />
            </div>
        </div>
    </div>

    <!-- APPOINTMENTS TABLE -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Details</th>
                        <th>Date & Time</th>
                        <th>Visit Mode</th>
                        <th>Type / Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="app in filteredAppointments" :key="app.id">
                        <td>
                            <b class="mono-id">{{ app.id }}</b>
                        </td>
                        <td>
                            <div class="patient-cell">
                                <img :src="app.avatar" :alt="app.patient" class="patient-avatar" />
                                <div>
                                    <b>{{ app.patient }}</b>
                                    <span>{{ app.phone }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="time-cell">
                                <b>{{ app.date }}</b>
                                <span>{{ app.time }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="mode-pill">{{ app.mode }}</span>
                        </td>
                        <td>
                            <span class="reason-text">{{ app.type }}</span>
                        </td>
                        <td>
                            <span class="status-pill-badge" :class="app.status">
                                {{ app.statusLabel }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <Link :href="`/doctor/patients/${app.id}/history`" class="btn-sm btn-outline">History</Link>
                                <Link :href="`/doctor/appointments/${app.id}`" class="btn-sm btn-primary">Details</Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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

.filter-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 16px 20px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.search-input-wrap {
    flex: 1;
    min-width: 260px;
    position: relative;
    display: flex;
    align-items: center;
}
.search-input-wrap svg {
    position: absolute;
    left: 14px;
    color: var(--ink-muted);
}
.search-input-wrap input { padding-left: 40px; }

.form-input, .form-select {
    height: 42px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 13.5px;
    color: var(--ink);
    width: 100%;
}

.table-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}

.table-responsive { width: 100%; overflow-x: auto; }

.data-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.data-table th {
    background: var(--cream);
    padding: 14px 20px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-muted);
    border-bottom: 1px solid var(--line);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
    font-size: 13.5px;
    vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }

.mono-id { font-family: var(--font-mono); font-size: 13px; color: var(--forest); }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
.patient-cell b { display: block; font-weight: 700; color: var(--forest); }
.patient-cell span { display: block; font-size: 11.5px; color: var(--ink-muted); }

.time-cell b { display: block; font-size: 13px; color: var(--forest); }
.time-cell span { display: block; font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.mode-pill { font-size: 12px; font-weight: 600; background: var(--cream); border: 1px solid var(--line); padding: 4px 10px; border-radius: 999px; }
.reason-text { font-size: 13px; color: var(--ink); }

.status-pill-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
}
.status-pill-badge.in-progress { background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; }
.status-pill-badge.checked-in { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.status-pill-badge.confirmed { background: var(--cream-alt); color: var(--ink); border: 1px solid var(--line); }
.status-pill-badge.completed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.action-btn-group { display: flex; gap: 8px; }

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 42px;
    padding: 0 20px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 150ms ease;
}

.btn-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 150ms ease;
}
.btn-outline { background: transparent; color: var(--forest); border: 1px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
