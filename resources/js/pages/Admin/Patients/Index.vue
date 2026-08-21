<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface PatientItem {
    id: number
    code: string
    name: string
    initials: string
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

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                All Patients <span class="tab-badge">{{ countAll }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'active' }" @click="activeTab = 'active'">
                Active <span class="tab-badge">{{ countActive }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'inactive' }" @click="activeTab = 'inactive'">
                Inactive <span class="tab-badge">{{ countInactive }}</span>
            </button>
        </div>

        <div class="filter-controls">
            <select v-model="selectedBlood" class="select-filter">
                <option value="all">All Blood Groups</option>
                <option value="O+">O+</option>
                <option value="A+">A+</option>
                <option value="B+">B+</option>
                <option value="AB+">AB+</option>
            </select>

            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input v-model="searchQuery" type="text" class="search-input" placeholder="Search name, phone, or ID..." />
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
                                <div class="patient-avatar" :style="{ background: patient.avatar_bg, color: patient.avatar_color }">
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
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />
                                </svg>
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredPatients.length === 0">
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No registered patients match your search query.
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
.tab-btn { padding: 8px 18px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; border: 0; background: transparent; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 6px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

.filter-controls { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.search-box { position: relative; width: 280px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); transition: border-color 150ms ease; outline: none; }
.search-input:focus { border-color: var(--forest); }
.select-filter { height: 40px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px; font-size: 13.5px; font-weight: 600; color: var(--ink); cursor: pointer; outline: none; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 18px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.patient-avatar { width: 40px; height: 40px; border-radius: 50%; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm); }
.patient-meta b { display: block; font-size: 14.5px; font-weight: 700; color: var(--forest); }

.ref-code { font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: var(--ink); background: var(--cream); padding: 2px 8px; border-radius: var(--radius-sm); border: 1px solid var(--line); }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-inactive { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); display: inline-flex; align-items: center; justify-content: center; color: var(--forest); transition: all 150ms ease; text-decoration: none; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
