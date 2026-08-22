<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface PatientItem {
    id: number
    code: string
    name: string
    initials: string
    avatar_url?: string | null
    phone: string
    email: string
    age: number
    gender: string
    blood_group: string
    visits_count: number
    status: 'active' | 'inactive'
    avatar_bg: string
    avatar_color: string
}

const props = withDefaults(
    defineProps<{
        patients?: PatientItem[]
    }>(),
    {
        patients: () => [
            {
                id: 9021,
                code: 'MDF-9021',
                name: 'Habib Hossain',
                initials: 'HH',
                avatar_url: null,
                phone: '(555) 340-2199',
                email: 'habib@example.com',
                age: 28,
                gender: 'Male',
                blood_group: 'O+',
                visits_count: 14,
                status: 'active',
                avatar_bg: 'var(--lime)',
                avatar_color: 'var(--lime-text)',
            },
            {
                id: 8812,
                code: 'MDF-8812',
                name: 'Tanjila Ahmed',
                initials: 'TA',
                avatar_url: null,
                phone: '(555) 291-8840',
                email: 'tanjila@example.com',
                age: 26,
                gender: 'Female',
                blood_group: 'A+',
                visits_count: 6,
                status: 'active',
                avatar_bg: '#E0F2FE',
                avatar_color: '#0369A1',
            },
            {
                id: 7701,
                code: 'MDF-7701',
                name: 'Robert Fox',
                initials: 'RF',
                avatar_url: null,
                phone: '(555) 492-1029',
                email: 'robert@example.com',
                age: 42,
                gender: 'Male',
                blood_group: 'B+',
                visits_count: 2,
                status: 'inactive',
                avatar_bg: '#FEF3C7',
                avatar_color: '#B45309',
            },
        ],
    }
)

const activeTab = ref<string>('all')
const searchQuery = ref<string>('')
const selectedBlood = ref<string>('all')

const filteredPatients = computed(() => {
    return props.patients.filter((p) => {
        const matchesTab = activeTab.value === 'all' || p.status === activeTab.value
        const matchesBlood = selectedBlood.value === 'all' || p.blood_group === selectedBlood.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery =
            !q ||
            p.name.toLowerCase().includes(q) ||
            p.code.toLowerCase().includes(q) ||
            p.phone.toLowerCase().includes(q) ||
            p.email.toLowerCase().includes(q)

        return matchesTab && matchesBlood && matchesQuery
    })
})

const countAll = computed(() => props.patients.length)
const countActive = computed(() => props.patients.filter((p) => p.status === 'active').length)
const countInactive = computed(() => props.patients.filter((p) => p.status === 'inactive').length)
</script>

<template>
    <Head title="Patients Registry — Admin Portal" />

    <!-- FILTER BAR -->
    <div class="filter-card mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="tabs-group">
                <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                    All Patients <span class="tab-count">{{ countAll }}</span>
                </button>
                <button class="tab-btn" :class="{ active: activeTab === 'active' }" @click="activeTab = 'active'">
                    Active Patients <span class="tab-count count-active">{{ countActive }}</span>
                </button>
                <button class="tab-btn" :class="{ active: activeTab === 'inactive' }" @click="activeTab = 'inactive'">
                    Inactive <span class="tab-count count-inactive">{{ countInactive }}</span>
                </button>
            </div>

            <div class="filter-controls">
                <select v-model="selectedBlood" class="select-filter">
                    <option value="all">All Blood Groups</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>

                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input v-model="searchQuery" type="text" class="search-input" placeholder="Search name, phone, or ID..." />
                </div>
            </div>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient Name & ID</th>
                        <th>Contact Info</th>
                        <th>Age / Gender</th>
                        <th>Blood Group</th>
                        <th>Completed Visits</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="patient in filteredPatients" :key="patient.id">
                        <td>
                            <div class="patient-cell">
                                <div v-if="patient.avatar_url" class="patient-avatar-wrap">
                                    <img :src="patient.avatar_url" :alt="patient.name" class="patient-avatar-fit" />
                                </div>
                                <div v-else class="patient-avatar" :style="{ background: patient.avatar_bg, color: patient.avatar_color }">
                                    {{ patient.initials }}
                                </div>
                                <div class="patient-meta">
                                    <b>{{ patient.name }}</b>
                                    <span class="ref-code">#{{ patient.code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <b>{{ patient.phone }}</b>
                            <span class="block text-xs text-[#62655A]">{{ patient.email }}</span>
                        </td>
                        <td>{{ patient.age }} Yrs · {{ patient.gender }}</td>
                        <td><b>{{ patient.blood_group }}</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ patient.visits_count }} Visits</td>
                        <td>
                            <span class="status-badge" :class="patient.status === 'active' ? 'status-active' : 'status-inactive'">
                                {{ patient.status === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <Link :href="`/admin/patients/${patient.id}`" class="btn-action-icon" title="View Patient Details">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredPatients.length === 0">
                        <td colspan="7" class="empty-state">
                            No patient records match the selected filter criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.filter-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 18px 24px; box-shadow: var(--shadow-sm); }
.tabs-group { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.tab-btn { display: inline-flex; align-items: center; gap: 6px; height: 38px; padding: 0 14px; border-radius: 999px; font-size: 13px; font-weight: 700; color: var(--ink-muted); background: transparent; border: 1px solid transparent; transition: all 150ms ease; cursor: pointer; }
.tab-btn:hover { background: var(--cream); color: var(--forest); }
.tab-btn.active { background: var(--forest); color: #fff; border-color: var(--forest); }

.tab-count { font-family: var(--font-mono); font-size: 11px; padding: 2px 7px; border-radius: 999px; background: rgba(255, 255, 255, 0.2); }
.tab-btn:not(.active) .tab-count { background: var(--cream-alt); color: var(--ink); }

.filter-controls { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.select-filter { height: 38px; padding: 0 12px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); font-size: 13px; color: var(--ink); font-weight: 600; outline: none; transition: border-color 150ms ease; }
.select-filter:focus { border-color: var(--forest); background: var(--card); }

.search-box { display: flex; align-items: center; gap: 8px; height: 38px; padding: 0 12px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); min-width: 240px; transition: border-color 150ms ease; }
.search-box:focus-within { border-color: var(--forest); background: var(--card); }
.search-box svg { color: var(--ink-muted); flex-shrink: 0; }
.search-input { width: 100%; border: 0; background: transparent; font-size: 13px; color: var(--ink); outline: none; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 20px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 20px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar-wrap { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 1px solid var(--line); flex-shrink: 0; }
.patient-avatar-fit { width: 100%; height: 100%; object-fit: cover; }
.patient-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13.5px; font-family: var(--font-mono); flex-shrink: 0; }
.patient-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--forest); }
.ref-code { font-family: var(--font-mono); font-size: 11.5px; color: var(--ink-muted); }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-inactive { background: #F3F4F6; color: #6B7280; border: 1px solid #E5E7EB; }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); color: var(--forest); display: inline-flex; align-items: center; justify-content: center; transition: all 150ms ease; text-decoration: none; }
.btn-action-icon:hover { background: var(--forest); color: #fff; border-color: var(--forest); }

.empty-state { text-align: center; padding: 48px; font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }
</style>
