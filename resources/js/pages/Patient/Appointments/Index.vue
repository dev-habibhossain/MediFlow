<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface Appointment {
    id: number
    appointment_code: string
    doctorName: string
    doctorTitle: string
    department: string
    avatar: string
    dateTime: string
    rawDate: string
    mode: string
    location: string
    status: string
    category: 'upcoming' | 'past' | 'cancelled'
    canReview: boolean
}

const props = defineProps<{
    appointments: Appointment[]
}>()

const activeTab = ref<'upcoming' | 'past' | 'cancelled'>('upcoming')
const searchQuery = ref('')
const selectedMode = ref('all')

const filteredAppointments = computed(() => {
    return props.appointments.filter((apt) => {
        if (apt.category !== activeTab.value) return false
        const matchesQuery = !searchQuery.value ||
            apt.doctorName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            apt.department.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchesMode = selectedMode.value === 'all' || apt.mode === selectedMode.value
        return matchesQuery && matchesMode
    })
})

const upcomingCount = computed(() => props.appointments.filter((a) => a.category === 'upcoming').length)
const pastCount = computed(() => props.appointments.filter((a) => a.category === 'past').length)
const cancelledCount = computed(() => props.appointments.filter((a) => a.category === 'cancelled').length)

function resetFilters() {
    searchQuery.value = ''
    selectedMode.value = 'all'
}
</script>

<template>
    <Head title="My Appointments" />

    <!-- TAB CONTROLS & FILTER TOOLBAR -->
    <div class="tab-controls-row">
        <!-- TABS (Upcoming / Past / Cancelled) -->
        <div class="tabs-group" aria-label="Appointment category tabs">
            <button class="tab-btn" :class="{ active: activeTab === 'upcoming' }" @click="activeTab = 'upcoming'">
                Upcoming <span class="tab-count">{{ upcomingCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'past' }" @click="activeTab = 'past'">
                Past Visits <span class="tab-count">{{ pastCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'cancelled' }" @click="activeTab = 'cancelled'">
                Cancelled <span class="tab-count">{{ cancelledCount }}</span>
            </button>
        </div>

        <!-- SEARCH & TYPE FILTER -->
        <div class="filter-bar">
            <div class="search-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input v-model="searchQuery" type="text" placeholder="Search doctor name or specialty..." />
            </div>

            <select v-model="selectedMode" class="filter-select">
                <option value="all">All Modes</option>
                <option value="In-Person">In-Person Visit</option>
                <option value="Telehealth">Telehealth Call</option>
            </select>
        </div>
    </div>

    <!-- APPOINTMENTS CARDS LIST -->
    <div v-if="filteredAppointments.length > 0" class="appointments-list">
        <div v-for="apt in filteredAppointments" :key="apt.id" class="appointment-card">
            <div class="doc-card-info">
                <img :src="apt.avatar" :alt="apt.doctorName" class="doc-avatar" />
                <div class="doc-details">
                    <h3>{{ apt.doctorName }}</h3>
                    <p>{{ apt.doctorTitle }}</p>
                    <span class="dept-tag">{{ apt.department }}</span>
                </div>
            </div>

            <div class="schedule-block">
                <div class="meta-item">
                    <label>Date & Time</label>
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        {{ apt.dateTime }}
                    </span>
                </div>
                <div class="meta-item">
                    <label>Consultation Mode</label>
                    <span>
                        <svg v-if="apt.mode === 'In-Person'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                        </svg>
                        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        {{ apt.location }}
                    </span>
                </div>
            </div>

            <div class="action-column">
                <span class="badge" :class="{
                    'badge-confirmed': apt.status === 'Confirmed',
                    'badge-pending': apt.status === 'Pending',
                    'badge-completed': apt.status === 'Completed',
                    'badge-cancelled': apt.status === 'Cancelled'
                }">
                    {{ apt.status === 'Pending' ? 'Pending Approval' : apt.status }}
                </span>

                <div class="btn-group">
                    <template v-if="apt.category === 'upcoming'">
                        <Link :href="`/patient/appointments/${apt.id}`" class="btn-action-sm">View Details</Link>
                        <Link :href="`/patient/appointments/${apt.id}/reschedule`" class="btn-action-sm">Reschedule</Link>
                    </template>

                    <template v-else-if="apt.category === 'past'">
                        <Link :href="`/patient/appointments/${apt.id}`" class="btn-action-sm">View Summary</Link>
                        <Link v-if="apt.canReview" :href="`/patient/appointments/${apt.id}/review`" class="btn-action-sm btn-review">★ Leave Review</Link>
                    </template>

                    <template v-else>
                        <Link href="/appointments/book" class="btn-action-sm">Rebook Visit</Link>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- EMPTY STATE -->
    <div v-else class="empty-state">
        <div class="empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
        </div>
        <h3>No Appointments Found</h3>
        <p>There are no appointments matching your search query or selected mode filter.</p>
        <button class="btn-action-sm" style="margin: 0 auto;" @click="resetFilters">Clear Filters</button>
    </div>
</template>

<style scoped>
.tab-controls-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 16px;
}

.tabs-group {
    display: flex;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 999px;
    padding: 4px;
    gap: 4px;
    box-shadow: var(--shadow-sm);
}
.tab-btn {
    padding: 8px 20px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ink-muted);
    transition: all 150ms ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
}
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }

.tab-count {
    font-family: var(--font-mono);
    font-size: 11.5px;
    padding: 2px 7px;
    border-radius: 999px;
    background: rgba(22,24,15,0.08);
    color: inherit;
}
.tab-btn.active .tab-count { background: rgba(255,255,255,0.2); color: #fff; }

.filter-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; width: 100%; max-width: 520px; }
.search-input-wrap { position: relative; flex: 1; min-width: 240px; }
.search-input-wrap svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input-wrap input { width: 100%; height: 42px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); }
.search-input-wrap input:focus { outline: none; border-color: var(--forest); }

.filter-select { height: 42px; padding: 0 16px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--card); font-size: 13.5px; font-weight: 500; color: var(--ink); cursor: pointer; }
.filter-select:focus { outline: none; border-color: var(--forest); }

.appointments-list { display: flex; flex-direction: column; gap: 16px; }

.appointment-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-card);
    transition: transform 150ms ease, box-shadow 150ms ease;
    display: grid;
    grid-template-columns: 280px 1fr auto;
    gap: 24px;
    align-items: center;
}
.appointment-card:hover { box-shadow: var(--shadow-lift); }
@media (max-width: 1024px) { .appointment-card { grid-template-columns: 1fr; gap: 16px; } }

.doc-card-info { display: flex; align-items: center; gap: 16px; }
.doc-avatar { width: 64px; height: 64px; border-radius: var(--radius-md); object-fit: cover; background: var(--cream-alt); flex-shrink: 0; }
.doc-details h3 { font-size: 16px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.doc-details p { font-size: 13px; color: var(--ink-muted); font-weight: 500; margin: 0; }
.dept-tag { display: inline-block; font-size: 11.5px; font-weight: 600; background: var(--lime-soft); color: var(--lime-text); padding: 2px 8px; border-radius: 999px; margin-top: 6px; }

.schedule-block { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 14px 18px; }
@media (max-width: 600px) { .schedule-block { grid-template-columns: 1fr; } }

.meta-item label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.meta-item span { font-size: 13.5px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 6px; }
.meta-item span svg { width: 14px; height: 14px; color: var(--forest); }

.action-column { display: flex; flex-direction: column; align-items: flex-end; gap: 12px; }
@media (max-width: 1024px) { .action-column { align-items: flex-start; flex-direction: row; justify-content: space-between; width: 100%; border-top: 1px solid var(--line); padding-top: 16px; } }

.badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-pending { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.badge-cancelled { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }

.btn-group { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-action-sm { height: 38px; padding: 0 16px; border-radius: 999px; font-size: 13px; font-weight: 600; border: 1px solid var(--line); background: var(--card); color: var(--ink); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; }
.btn-action-sm:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
.btn-action-sm.btn-review { background: var(--lime); color: var(--lime-text); border-color: #c4dc3c; }
.btn-action-sm.btn-review:hover { background: #d2e85a; }

.empty-state { background: var(--card); border: 1px dashed var(--line); border-radius: var(--radius-xl); padding: 60px 24px; text-align: center; }
.empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--cream); color: var(--ink-muted); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.empty-icon svg { width: 28px; height: 28px; }
.empty-state h3 { font-size: 18px; font-weight: 800; color: var(--forest); margin: 0 0 6px 0; }
.empty-state p { font-size: 14px; color: var(--ink-muted); max-width: 40ch; margin: 0 auto 20px; }
</style>
