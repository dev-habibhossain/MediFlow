<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface DoctorItem {
    id: number
    name: string
    title: string
    license_code: string
    department: string
    fee: string
    rating: number
    reviews_count: number
    status: 'active' | 'leave' | 'inactive'
    avatar: string
}

const props = withDefaults(
    defineProps<{
        doctors?: DoctorItem[]
    }>(),
    {
        doctors: () => [
            {
                id: 901,
                name: 'Dr. Sarah Jenkins',
                title: 'MD, FACC · 12 Yrs Exp',
                license_code: 'MD-90412',
                department: 'Cardiology',
                fee: '$120.00',
                rating: 4.9,
                reviews_count: 148,
                status: 'active',
                avatar: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150',
            },
            {
                id: 902,
                name: 'Dr. Marcus Vance',
                title: 'MD, PhD · 15 Yrs Exp',
                license_code: 'MD-88210',
                department: 'Neurology',
                fee: '$140.00',
                rating: 4.8,
                reviews_count: 92,
                status: 'active',
                avatar: 'https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150',
            },
            {
                id: 903,
                name: 'Dr. Emily Watson',
                title: 'MD, FAAP · 8 Yrs Exp',
                license_code: 'MD-77102',
                department: 'Pediatrics',
                fee: '$110.00',
                rating: 4.9,
                reviews_count: 210,
                status: 'leave',
                avatar: 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=150',
            },
            {
                id: 904,
                name: 'Dr. Alan Grant',
                title: 'MD, FACS · 20 Yrs Exp',
                license_code: 'MD-61209',
                department: 'Orthopedics',
                fee: '$150.00',
                rating: 4.7,
                reviews_count: 64,
                status: 'inactive',
                avatar: 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=150',
            },
        ],
    }
)

const activeStatus = ref<string>('all')
const searchQuery = ref<string>('')
const selectedDepartment = ref<string>('all')

const filteredDoctors = computed(() => {
    return props.doctors.filter((doc) => {
        const matchesStatus = activeStatus.value === 'all' || doc.status === activeStatus.value
        const matchesDept = selectedDepartment.value === 'all' || doc.department === selectedDepartment.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesQuery = !q || doc.name.toLowerCase().includes(q) || doc.license_code.toLowerCase().includes(q) || doc.department.toLowerCase().includes(q)

        return matchesStatus && matchesDept && matchesQuery
    })
})

const countAll = computed(() => props.doctors.length)
const countActive = computed(() => props.doctors.filter((d) => d.status === 'active').length)
const countLeave = computed(() => props.doctors.filter((d) => d.status === 'leave').length)
const countInactive = computed(() => props.doctors.filter((d) => d.status === 'inactive').length)
</script>

<template>
    <Head title="Physicians Directory — Admin Portal" />

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeStatus === 'all' }" @click="activeStatus = 'all'">
                All Doctors <span class="tab-badge">{{ countAll }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeStatus === 'active' }" @click="activeStatus = 'active'">
                Active <span class="tab-badge">{{ countActive }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeStatus === 'leave' }" @click="activeStatus = 'leave'">
                On Leave <span class="tab-badge">{{ countLeave }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeStatus === 'inactive' }" @click="activeStatus = 'inactive'">
                Inactive <span class="tab-badge">{{ countInactive }}</span>
            </button>
        </div>

        <div class="filter-controls">
            <select v-model="selectedDepartment" class="select-filter">
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
                <input v-model="searchQuery" type="text" class="search-input" placeholder="Search name or license..." />
            </div>

            <Link href="/admin/doctors/create" class="btn-primary-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Onboard New Doctor
            </Link>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Physician Details</th>
                        <th>License No</th>
                        <th>Department</th>
                        <th>Consultation Fee</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="doc in filteredDoctors" :key="doc.id">
                        <td>
                            <div class="doctor-cell">
                                <img :src="doc.avatar" :alt="doc.name" class="doctor-avatar" />
                                <div class="doctor-meta">
                                    <b>{{ doc.name }}</b>
                                    <span>{{ doc.title }}</span>
                                </div>
                            </div>
                        </td>
                        <td><span class="license-code">{{ doc.license_code }}</span></td>
                        <td><b>{{ doc.department }}</b></td>
                        <td style="font-family: var(--font-mono); font-weight: 700;">{{ doc.fee }}</td>
                        <td>
                            <span class="rating-tag">★ {{ doc.rating }} <small style="color: var(--ink-muted); font-weight: normal;">({{ doc.reviews_count }})</small></span>
                        </td>
                        <td>
                            <span
                                class="status-badge"
                                :class="{
                                    'status-active': doc.status === 'active',
                                    'status-leave': doc.status === 'leave',
                                    'status-inactive': doc.status === 'inactive'
                                }"
                            >
                                {{ doc.status === 'active' ? 'Active' : doc.status === 'leave' ? 'On Leave' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <Link :href="`/admin/doctors/${doc.id}`" class="btn-action-icon" title="View / Edit Profile">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </Link>
                            <Link :href="`/admin/doctors/${doc.id}/schedule`" class="btn-action-icon" title="Admin Schedule Override">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />
                                </svg>
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredDoctors.length === 0">
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No physicians found matching your search or filters.
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
.search-box { position: relative; width: 240px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); transition: border-color 150ms ease; outline: none; }
.search-input:focus { border-color: var(--forest); }
.select-filter { height: 40px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px; font-size: 13.5px; font-weight: 600; color: var(--ink); cursor: pointer; outline: none; }

.btn-primary-add { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 20px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 13.5px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; text-decoration: none; }
.btn-primary-add:hover { background: var(--forest-2); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 18px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }

.doctor-cell { display: flex; align-items: center; gap: 12px; }
.doctor-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; background: var(--cream-alt); flex-shrink: 0; }
.doctor-meta b { display: block; font-size: 14.5px; font-weight: 700; color: var(--forest); }
.doctor-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.license-code { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; color: var(--ink); background: var(--cream); padding: 2px 8px; border-radius: var(--radius-sm); border: 1px solid var(--line); }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-leave { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.status-inactive { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }

.rating-tag { font-weight: 700; color: #D97706; display: flex; align-items: center; gap: 4px; font-size: 13px; }

.btn-action-icon { width: 34px; height: 34px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); display: inline-flex; align-items: center; justify-content: center; color: var(--forest); transition: all 150ms ease; margin-right: 4px; text-decoration: none; }
.btn-action-icon:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
