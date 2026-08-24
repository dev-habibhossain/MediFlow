<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Filler,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
} from 'chart.js'
import { computed } from 'vue'
import { Bar, Doughnut, Line } from 'vue-chartjs'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    LineElement,
    PointElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    Filler
)

interface Stats {
    total_doctors: number
    doctors_this_month: number
    active_patients: number
    patients_this_month: number
    appointments_today: number
    completed_today: number
    pending_today: number
    monthly_revenue: string
    revenue_growth: number
}

interface MonthlyVolumeItem {
    month: string
    count: number
    height: string
    is_current: boolean
}

interface RecentAppointment {
    id: number
    code: string
    patient_name: string
    patient_avatar?: string | null
    patient_initials: string
    doctor_name: string
    department: string
    date_time: string
    status: string
}

interface DepartmentDist {
    id: number
    name: string
    doctors_count: number
    appointments_count: number
    percentage: number
}

interface DoctorSnippet {
    id: number
    name: string
    specialty: string
    rating: number
    fee: string
    status: string
    avatar?: string | null
    initials: string
}

interface SystemConfig {
    hospital_name: string
    slot_duration: string
    payment_gateway: string
}

const props = defineProps<{
    stats: Stats
    monthlyVolume: MonthlyVolumeItem[]
    recentAppointments?: RecentAppointment[]
    departmentDistribution?: DepartmentDist[]
    doctorsRoster?: DoctorSnippet[]
    systemConfig: SystemConfig
}>()

const currentDateStr = computed(() => {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
})

// 1. FULL-WIDTH CONSULTATION GROWTH TREND CURVE (LINE CHART)
const bigLineChartData = computed(() => {
    const labels = props.monthlyVolume.map((item) => item.month)
    const data = props.monthlyVolume.map((item) => item.count)

    return {
        labels,
        datasets: [
            {
                label: 'Monthly Consultations Trend',
                data,
                fill: true,
                tension: 0.4,
                borderColor: '#0B251C',
                borderWidth: 3,
                backgroundColor: (context: any) => {
                    const ctx = context.chart.ctx
                    const gradient = ctx.createLinearGradient(0, 0, 0, 260)
                    gradient.addColorStop(0, 'rgba(11, 37, 28, 0.18)')
                    gradient.addColorStop(1, 'rgba(194, 240, 194, 0.01)')
                    return gradient
                },
                pointBackgroundColor: '#0B251C',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            },
        ],
    }
})

const bigLineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#0B251C',
            titleFont: { family: 'Inter, sans-serif', weight: 'bold', size: 12 },
            bodyFont: { family: 'Inter, sans-serif', size: 12 },
            padding: 12,
            cornerRadius: 10,
            displayColors: false,
            callbacks: {
                label: (context: any) => ` Total Visits: ${context.parsed.y} consultations`,
            },
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#64748B', font: { family: 'Inter, sans-serif', size: 12, weight: 600 } },
        },
        y: {
            grid: { color: '#F1F5F9' },
            border: { dash: [4, 4] },
            ticks: { color: '#94A3B8', font: { family: 'Courier, monospace', size: 11 } },
        },
    },
}

// 2. MONTHLY PATIENT TRAFFIC DENSITY (BAR CHART)
const mediumPillarChartData = computed(() => {
    const labels = props.monthlyVolume.map((item) => item.month)
    const data = props.monthlyVolume.map((item) => item.count)
    const backgroundColors = props.monthlyVolume.map((item) =>
        item.is_current ? '#C2F0C2' : '#0B251C'
    )

    return {
        labels,
        datasets: [
            {
                label: 'Patient Traffic Density',
                backgroundColor: backgroundColors,
                borderRadius: 8,
                barThickness: 24,
                data,
            },
        ],
    }
})

const mediumPillarChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { backgroundColor: '#0B251C', cornerRadius: 8, padding: 10 },
    },
    scales: {
        x: { grid: { display: false }, ticks: { color: '#64748B', font: { family: 'Inter, sans-serif', size: 11, weight: 600 } } },
        y: { grid: { color: '#F1F5F9' }, ticks: { color: '#94A3B8', font: { family: 'Courier, monospace', size: 10 } } },
    },
}

// 3. DEPARTMENT SPECIALIZATION SHARE (DOUGHNUT CHART)
const smallCircleChartData = computed(() => {
    const depts = props.departmentDistribution || []
    const labels = depts.map((d) => d.name)
    const data = depts.map((d) => d.percentage)

    return {
        labels: labels.length ? labels : ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics'],
        datasets: [
            {
                backgroundColor: ['#0B251C', '#C2F0C2', '#0284C7', '#D97706', '#10B981'],
                borderWidth: 3,
                borderColor: '#ffffff',
                data: data.length ? data : [35, 25, 20, 20],
            },
        ],
    }
})

const smallCircleChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
            labels: {
                boxWidth: 10,
                padding: 12,
                font: { family: 'Inter, sans-serif', size: 11, weight: 'bold' },
                color: '#0B251C',
            },
        },
        tooltip: { backgroundColor: '#0B251C', cornerRadius: 8, padding: 10 },
    },
    cutout: '72%',
}
</script>

<template>
    <Head title="Executive Dashboard — MediFlow Admin" />

    <!-- TOP HEADER BAR -->
    <div class="dash-header mb-6">
        <div>
            <h1 class="page-title">Executive Command Dashboard</h1>
            <p class="page-subtitle">Hospital operational metrics for {{ props.systemConfig.hospital_name }}</p>
        </div>

        <div class="header-right">
            <div class="status-badge font-mono">
                <span class="pulse-dot"></span>
                System Operational · Live
            </div>

            <div class="date-box font-mono">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span>{{ currentDateStr }}</span>
            </div>
        </div>
    </div>

    <!-- 1. TOP 4 KPI CARDS -->
    <div class="kpi-grid mb-8">
        <!-- CARD 1: ACTIVE DOCTORS -->
        <div class="kpi-card accent-green">
            <div class="kpi-top">
                <span class="kpi-title">Active Doctors</span>
                <div class="kpi-icon icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
            </div>
            <b class="kpi-val">{{ props.stats.total_doctors.toLocaleString() }}</b>
            <div class="kpi-foot">
                <span class="trend trend-green font-mono">↑ +{{ props.stats.doctors_this_month }}</span>
                <span class="kpi-desc">Onboarded this month</span>
            </div>
        </div>

        <!-- CARD 2: TOTAL PATIENTS -->
        <div class="kpi-card accent-sky">
            <div class="kpi-top">
                <span class="kpi-title">Total Patients</span>
                <div class="kpi-icon icon-sky">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" />
                    </svg>
                </div>
            </div>
            <b class="kpi-val">{{ props.stats.active_patients.toLocaleString() }}</b>
            <div class="kpi-foot">
                <span class="trend trend-sky font-mono">↑ +{{ props.stats.patients_this_month }}</span>
                <span class="kpi-desc">New registrations</span>
            </div>
        </div>

        <!-- CARD 3: APPOINTMENTS TODAY -->
        <div class="kpi-card accent-amber">
            <div class="kpi-top">
                <span class="kpi-title">Appointments Today</span>
                <div class="kpi-icon icon-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                </div>
            </div>
            <b class="kpi-val">{{ props.stats.appointments_today.toLocaleString() }}</b>
            <div class="kpi-foot">
                <span class="trend trend-amber font-mono">{{ props.stats.completed_today }} Done</span>
                <span class="kpi-desc">{{ props.stats.pending_today }} Pending</span>
            </div>
        </div>

        <!-- CARD 4: MONTHLY REVENUE -->
        <div class="kpi-card accent-lime highlight-bg">
            <div class="kpi-top">
                <span class="kpi-title">Monthly Revenue</span>
                <div class="kpi-icon icon-lime">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2" /><line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                </div>
            </div>
            <b class="kpi-val">${{ props.stats.monthly_revenue }}</b>
            <div class="kpi-foot">
                <span class="trend trend-lime font-mono">
                    {{ props.stats.revenue_growth >= 0 ? '↑ +' : '↓ ' }}{{ props.stats.revenue_growth }}%
                </span>
                <span class="kpi-desc">vs last month</span>
            </div>
        </div>
    </div>

    <!-- 2. SITE-RELEVANT CLINICAL ANALYTICS SECTION -->
    <!-- FULL-WIDTH CHART: CONSULTATION GROWTH & VOLUME -->
    <div class="analytics-card full-width-card mb-6">
        <div class="card-head-flex">
            <div>
                <h3 class="chart-title">Clinical Consultation Volume & Growth</h3>
                <p class="chart-sub">6-Month trailing trend curve of completed patient visits</p>
            </div>
            <span class="chart-size-badge font-mono">CONSULTATION TREND</span>
        </div>
        <div class="big-chart-box">
            <Line :data="bigLineChartData" :options="bigLineChartOptions" />
        </div>
    </div>

    <!-- SECONDARY ANALYTICS ROW: TRAFFIC DENSITY & DEPARTMENT SHARE -->
    <div class="secondary-analytics-row mb-8">
        <!-- BAR CHART: PATIENT TRAFFIC DENSITY -->
        <div class="analytics-card medium-card">
            <div class="card-head-flex">
                <div>
                    <h3 class="chart-title">Monthly Patient Traffic Density</h3>
                    <p class="chart-sub">Comparative monthly visit distribution & peak capacity</p>
                </div>
                <span class="chart-size-badge font-mono">PATIENT TRAFFIC</span>
            </div>
            <div class="medium-chart-box">
                <Bar :data="mediumPillarChartData" :options="mediumPillarChartOptions" />
            </div>
        </div>

        <!-- DOUGHNUT CHART: DEPARTMENT SPECIALIZATION SHARE -->
        <div class="analytics-card small-circle-card">
            <div class="card-head-flex">
                <div>
                    <h3 class="chart-title">Department Specialization Share</h3>
                    <p class="chart-sub">Breakdown of patient consultations by medical department</p>
                </div>
                <span class="chart-size-badge font-mono">DEPARTMENT SHARE</span>
            </div>
            <div class="small-chart-box">
                <Doughnut :data="smallCircleChartData" :options="smallCircleChartOptions" />
            </div>
        </div>
    </div>

    <!-- 3. LOWER DATA SECTION: LIVE CONSULTATIONS TABLE & SIDEBAR WIDGETS -->
    <div class="data-sections-grid">
        <!-- LEFT: RECENT PATIENT CONSULTATIONS TABLE -->
        <div class="data-col-left">
            <div class="content-card mb-6">
                <div class="card-head-flex">
                    <div>
                        <h3 class="section-heading">Live Patient Consultations</h3>
                        <p class="section-sub">Incoming patient appointment roster</p>
                    </div>
                    <Link href="/admin/patients" class="header-link">Patient Registry →</Link>
                </div>

                <div class="table-wrap">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="app in (props.recentAppointments || [])" :key="app.id">
                                <td>
                                    <div class="patient-cell">
                                        <img v-if="app.patient_avatar" :src="app.patient_avatar" :alt="app.patient_name" class="p-img" />
                                        <div v-else class="p-init">{{ app.patient_initials }}</div>
                                        <div>
                                            <b class="p-name">{{ app.patient_name }}</b>
                                            <span class="p-code font-mono">#{{ app.code }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><b>{{ app.doctor_name }}</b></td>
                                <td><span class="dept-pill">{{ app.department }}</span></td>
                                <td class="font-mono text-xs text-slate-500">{{ app.date_time }}</td>
                                <td>
                                    <span
                                        class="status-pill"
                                        :class="{
                                            'st-confirmed': app.status === 'confirmed' || app.status === 'scheduled',
                                            'st-completed': app.status === 'completed',
                                            'st-cancelled': app.status === 'cancelled'
                                        }"
                                    >
                                        ● {{ app.status }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <Link :href="`/admin/patients/${app.id}`" class="btn-table">
                                        View Profile
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="!props.recentAppointments || props.recentAppointments.length === 0">
                                <td colspan="6" class="text-center py-6 text-slate-500 text-sm">
                                    No recent appointment records found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT: TOP PHYSICIANS & DEPARTMENT CAPACITY LOAD -->
        <div class="data-col-right">
            <!-- TOP ACTIVE PHYSICIANS -->
            <div class="content-card mb-6">
                <div class="card-head-flex">
                    <div>
                        <h3 class="section-heading">Top Active Physicians</h3>
                        <p class="section-sub">Physician roster & ratings</p>
                    </div>
                    <Link href="/admin/doctors" class="header-link">All Doctors →</Link>
                </div>

                <div class="doctors-list">
                    <div v-for="doc in (props.doctorsRoster || [])" :key="doc.id" class="doc-row">
                        <div class="doc-avatar">
                            <img v-if="doc.avatar" :src="doc.avatar" :alt="doc.name" />
                            <div v-else class="doc-init">{{ doc.initials }}</div>
                        </div>
                        <div class="doc-meta">
                            <b>{{ doc.name }}</b>
                            <span>{{ doc.specialty }} · {{ doc.fee }}</span>
                        </div>
                        <div class="rating-badge font-mono">
                            ★ {{ doc.rating }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- DEPARTMENT CAPACITY LOAD -->
            <div class="content-card">
                <div class="card-head-flex">
                    <div>
                        <h3 class="section-heading">Department Capacity Load</h3>
                        <p class="section-sub">Volume share by medical unit</p>
                    </div>
                </div>

                <div class="dept-list">
                    <div v-for="dept in (props.departmentDistribution || [])" :key="dept.id" class="dept-item">
                        <div class="dept-head">
                            <span class="dept-name">{{ dept.name }}</span>
                            <span class="dept-pct font-mono">{{ dept.percentage }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" :style="{ width: `${dept.percentage}%` }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* HEADER */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.page-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--forest);
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
}

.page-subtitle { font-size: 13.5px; color: var(--ink-muted); margin: 0; }

.header-right { display: flex; align-items: center; gap: 12px; }

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #15803D;
    background: #DCFCE7;
    padding: 6px 14px;
    border-radius: 999px;
    border: 1px solid #BBF7D0;
}

.pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #15803D;
    box-shadow: 0 0 8px #15803D;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.3); }
    100% { opacity: 1; transform: scale(1); }
}

.date-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--card);
    border: 1px solid var(--line);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--forest);
}

/* 1. POLISHED KPI CARDS */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 1100px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 580px) { .kpi-grid { grid-template-columns: 1fr; } }

.kpi-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-top-width: 4px;
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: 0 4px 20px rgba(11, 37, 28, 0.03);
    transition: all 200ms ease;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(11, 37, 28, 0.08); }

.accent-green { border-top-color: #15803D; }
.accent-sky { border-top-color: #0369A1; }
.accent-amber { border-top-color: #B45309; }
.accent-lime { border-top-color: var(--lime-text); }

.highlight-bg { background: linear-gradient(180deg, #F7FCF7 0%, var(--card) 100%); }

.kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.kpi-title { font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); }

.kpi-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.kpi-icon svg { width: 19px; height: 19px; }

.icon-green { background: #DCFCE7; color: #15803D; }
.icon-sky { background: #E0F2FE; color: #0369A1; }
.icon-amber { background: #FEF3C7; color: #B45309; }
.icon-lime { background: var(--lime-soft); color: var(--lime-text); }

.kpi-val { font-family: var(--font-mono); font-size: 30px; font-weight: 800; color: var(--forest); line-height: 1.1; display: block; margin-bottom: 8px; }
.kpi-foot { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--ink-muted); }

.trend { font-size: 11.5px; font-weight: 800; padding: 2px 7px; border-radius: 6px; }
.trend-green { background: #DCFCE7; color: #15803D; }
.trend-sky { background: #E0F2FE; color: #0369A1; }
.trend-amber { background: #FEF3C7; color: #B45309; }
.trend-lime { background: var(--lime); color: var(--lime-text); }
.kpi-desc { font-weight: 500; }

/* 2. ANALYTICS CARDS & HIERARCHY */
.analytics-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(11, 37, 28, 0.03);
    overflow: hidden;
}

.full-width-card { width: 100%; }

.secondary-analytics-row {
    display: grid;
    grid-template-columns: 1.8fr 1fr;
    gap: 20px;
}
@media (max-width: 1024px) { .secondary-analytics-row { grid-template-columns: 1fr; } }

.card-head-flex {
    padding: 18px 22px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chart-title { font-size: 16px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.chart-sub { font-size: 12px; color: var(--ink-muted); margin: 0; }
.chart-size-badge { font-size: 10.5px; font-weight: 800; color: var(--forest); background: var(--cream); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--line); }

.big-chart-box { padding: 24px; height: 280px; position: relative; }
.medium-chart-box { padding: 20px; height: 230px; position: relative; }
.small-chart-box { padding: 20px; height: 230px; position: relative; }

/* 3. LOWER DATA SECTION */
.data-sections-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 1100px) { .data-sections-grid { grid-template-columns: 1fr; } }

.content-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 18px;
    box-shadow: 0 4px 20px rgba(11, 37, 28, 0.03);
    overflow: hidden;
}

.section-heading { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.section-sub { font-size: 11.5px; color: var(--ink-muted); margin: 0; }

.header-link { font-size: 12.5px; font-weight: 700; color: var(--forest); text-decoration: none; }
.header-link:hover { text-decoration: underline; }

/* TABLE */
.table-wrap { width: 100%; overflow-x: auto; }
.clean-table { width: 100%; border-collapse: collapse; text-align: left; }
.clean-table th { background: #F8FAFC; padding: 12px 22px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.clean-table td { padding: 14px 22px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.patient-cell { display: flex; align-items: center; gap: 12px; }
.p-img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 1px solid var(--line); flex-shrink: 0; }
.p-init { width: 34px; height: 34px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-weight: 800; font-size: 11.5px; font-family: var(--font-mono); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

.p-name { display: block; font-size: 13.5px; color: var(--forest); }
.p-code { display: block; font-size: 11.5px; color: var(--ink-muted); }

.dept-pill { font-size: 12px; font-weight: 600; color: var(--forest); background: var(--cream); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--line); }

.status-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 700; padding: 3px 8px; border-radius: 999px; text-transform: capitalize; }
.st-confirmed { background: #DCFCE7; color: #15803D; }
.st-completed { background: #E0F2FE; color: #0369A1; }
.st-cancelled { background: #FEE2E2; color: #DC2626; }

.btn-table { font-size: 12px; font-weight: 700; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 4px 12px; border-radius: 6px; text-decoration: none; transition: all 150ms ease; }
.btn-table:hover { background: var(--forest); color: #ffffff; border-color: var(--forest); }

/* SIDEBAR LISTS */
.doctors-list { padding: 18px 22px; display: flex; flex-direction: column; gap: 14px; }
.doc-row { display: flex; align-items: center; justify-content: space-between; }
.doc-avatar { width: 38px; height: 38px; border-radius: 50%; overflow: hidden; border: 1px solid var(--line); flex-shrink: 0; }
.doc-avatar img { width: 100%; height: 100%; object-fit: cover; }
.doc-init { width: 38px; height: 38px; border-radius: 50%; background: var(--forest); color: var(--lime); font-weight: 800; font-size: 12px; font-family: var(--font-mono); display: flex; align-items: center; justify-content: center; }
.doc-meta { flex: 1; margin: 0 12px; }
.doc-meta b { font-size: 13.5px; color: var(--forest); display: block; }
.doc-meta span { font-size: 12px; color: var(--ink-muted); display: block; }
.rating-badge { font-size: 12px; font-weight: 800; color: var(--forest); background: var(--cream); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--line); }

.dept-list { padding: 18px 22px; display: flex; flex-direction: column; gap: 14px; }
.dept-item {}
.dept-head { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px; }
.dept-name { font-weight: 700; color: var(--forest); }
.dept-pct { font-size: 12px; color: var(--ink-muted); }
.progress-track { height: 7px; background: #F1F5F9; border-radius: 999px; overflow: hidden; }
.progress-fill { height: 100%; background: var(--forest); border-radius: 999px; }
</style>
