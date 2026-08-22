<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const activeTab = ref('today')
const statusFilter = ref('All Statuses')
const searchQuery = ref('')
const selectedDate = ref('2026-08-07')

const appointments = [
    {
        id: '101',
        date: 'Aug 7, 2026',
        time: '10:00 AM',
        patientName: 'Habib Hossain',
        patientRef: '#MDF-9021',
        avatarBg: 'var(--lime)',
        avatarColor: 'var(--lime-text)',
        avatarInitials: 'HH',
        visitType: 'In-Person',
        status: 'confirmed',
        statusLabel: 'Confirmed',
        actionLabel: 'Manage',
        actionUrl: '/doctor/appointments/MDF-9021',
    },
    {
        id: '102',
        date: 'Aug 7, 2026',
        time: '11:30 AM',
        patientName: 'Tanjila Ahmed',
        patientRef: '#MDF-8812',
        avatarBg: '#E0F2FE',
        avatarColor: '#0369A1',
        avatarInitials: 'TA',
        visitType: 'Telehealth',
        status: 'pending',
        statusLabel: 'Pending',
        actionLabel: 'View',
        actionUrl: '/doctor/appointments/MDF-9022',
    },
    {
        id: '103',
        date: 'Aug 7, 2026',
        time: '02:00 PM',
        patientName: 'Karim Alam',
        patientRef: '#MDF-7419',
        avatarBg: '#FEF3C7',
        avatarColor: '#B45309',
        avatarInitials: 'KA',
        visitType: 'In-Person',
        status: 'completed',
        statusLabel: 'Completed',
        actionLabel: 'View',
        actionUrl: '/doctor/appointments/MDF-8810',
    },
]
</script>

<template>
    <Head title="My Appointments" />

    <!-- TOOLBAR ROW -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'today' }"
                @click="activeTab = 'today'"
            >
                Today <span class="tab-badge">6</span>
            </button>
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'upcoming' }"
                @click="activeTab = 'upcoming'"
            >
                Upcoming
            </button>
            <button
                class="tab-btn"
                :class="{ active: activeTab === 'past' }"
                @click="activeTab = 'past'"
            >
                Past
            </button>
        </div>

        <div class="filter-bar">
            <input v-model="selectedDate" type="date" class="select-input" />
            <select v-model="statusFilter" class="select-input">
                <option>All Statuses</option>
                <option>Confirmed</option>
                <option>Pending</option>
                <option>Completed</option>
            </select>
            <input v-model="searchQuery" type="text" class="search-input" placeholder="Search patient..." />
        </div>
    </div>

    <!-- DATA TABLE CARD SHELL -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Patient</th>
                        <th>Visit Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="app in appointments" :key="app.id">
                        <td>
                            <b>{{ app.date }}</b><br />
                            <span class="time-sub">{{ app.time }}</span>
                        </td>
                        <td>
                            <div class="patient-cell">
                                <div class="patient-avatar" :style="{ background: app.avatarBg, color: app.avatarColor }">
                                    {{ app.avatarInitials }}
                                </div>
                                <div class="patient-meta">
                                    <b>{{ app.patientName }}</b>
                                    <span>{{ app.patientRef }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ app.visitType }}</td>
                        <td>
                            <span
                                class="badge"
                                :class="{
                                    'badge-confirmed': app.status === 'confirmed',
                                    'badge-pending': app.status === 'pending',
                                    'badge-completed': app.status === 'completed'
                                }"
                            >
                                {{ app.statusLabel }}
                            </span>
                        </td>
                        <td>
                            <Link
                                :href="app.actionUrl"
                                class="btn-table-action"
                                :class="{ primary: app.status === 'confirmed' }"
                            >
                                {{ app.actionLabel }}
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.toolbar-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 16px;
}

.tab-group {
    display: flex;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 999px;
    padding: 4px;
    gap: 4px;
    box-shadow: var(--shadow-sm);
}

.tab-btn {
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    color: var(--ink-muted);
    transition: all 150ms ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    background: transparent;
    cursor: pointer;
}
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }

.tab-badge {
    font-family: var(--font-mono);
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 999px;
    background: rgba(22,24,15,0.08);
    color: inherit;
}
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

.filter-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.search-input { height: 40px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px; font-size: 13.5px; color: var(--ink); width: 240px; }
.select-input { height: 40px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px; font-size: 13.5px; color: var(--ink); cursor: pointer; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 20px 24px; border-bottom: 1px solid var(--line); font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }

.time-sub { font-family: var(--font-mono); font-size: 12px; color: var(--ink-muted); }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar { width: 40px; height: 40px; border-radius: 50%; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.patient-meta b { display: block; font-size: 14.5px; font-weight: 700; color: var(--ink); }
.patient-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }

.btn-table-action { height: 36px; padding: 0 16px; border-radius: var(--radius-sm); border: 1px solid var(--line); font-size: 13px; font-weight: 600; color: var(--ink); transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; }
.btn-table-action:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
.btn-table-action.primary { background: var(--forest); color: #fff; }
.btn-table-action.primary:hover { background: var(--forest-2); }
</style>
