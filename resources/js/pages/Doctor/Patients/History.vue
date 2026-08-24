<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    patient?: {
        id: string
        db_id?: number
        name: string
        initials: string
        avatarBg: string
        avatarColor: string
        metaText: string
        bloodType: string
        allergy: string
        condition: string
        visitsCount: number
        activePrescriptionsCount: number
        labReportsCount: number
        lastBp: string
    }
    historyItems?: Array<any>
}>()

const activeCategory = ref('all')
const searchQuery = ref('')

const patientData = computed(() => props.patient ?? {
    id: 'MDF-9021',
    name: 'Habib Hossain',
    initials: 'HH',
    avatarBg: 'var(--lime)',
    avatarColor: 'var(--lime-text)',
    metaText: 'Patient Record ID: #MDF-9021 · Age: 28 · DOB: April 12, 1998 · Gender: Male',
    bloodType: 'O+',
    allergy: 'Penicillin',
    condition: 'Hypertension (Controlled)',
    visitsCount: 14,
    activePrescriptionsCount: 2,
    labReportsCount: 5,
    lastBp: '120/80',
})

const rawHistoryItems = computed(() => props.historyItems && props.historyItems.length > 0 ? props.historyItems : [
    {
        id: 'REC-301',
        category: 'consultation',
        categoryLabel: 'Consultation Record',
        dateStr: 'July 14, 2026 · Consultation #REC-301',
        title: 'Cardiology Follow-Up & ECG Assessment',
        primaryDiag: 'Primary Diagnosis: Essential Hypertension (Controlled)',
        icdCode: 'ICD-10: I10',
        notes: 'Blood pressure remains stable (120/80 mmHg). Resting ECG demonstrated normal sinus rhythm at 72 bpm.',
        doctor: 'Dr. Sarah Jenkins · Cardiology Department',
        doctorAvatar: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
        actionLabel: 'Edit / Amend Record →',
        actionUrl: '/doctor/records/1/edit',
        searchTerms: 'cardiology ecg hypertension amlodipine chest tightness dr sarah jenkins rec-301',
    },
    {
        id: 'RX-401',
        category: 'prescription',
        categoryLabel: 'Active Prescription',
        dateStr: 'July 14, 2026 · Prescription #RX-401',
        title: 'Prescription Regimen Issued (90 Days Supply)',
        medications: [
            {
                name: 'Amlodipine Besylate 5 mg Tablet',
                directions: 'Take 1 tablet daily in the morning',
                refills: '2 Refills Left',
            },
            {
                name: 'Atorvastatin Calcium 20 mg Tablet',
                directions: 'Take 1 tablet daily at bedtime',
                refills: '2 Refills Left',
            },
        ],
        doctor: 'Dr. Sarah Jenkins · Cardiology Department',
        doctorAvatar: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
        actionLabel: 'Correct / Supersede Rx →',
        actionUrl: '/doctor/prescriptions/401/supersede',
        searchTerms: 'rx 401 amlodipine besylate atorvastatin calcium dr sarah jenkins',
    },
    {
        id: 'LAB-9201',
        category: 'lab',
        categoryLabel: 'Diagnostic Lab Report',
        dateStr: 'June 28, 2026 · Lab Test #LAB-9201',
        title: 'Comprehensive Blood Count (CBC) & Lipid Panel',
        notes: 'Total Cholesterol: 185 mg/dL (Desirable), HDL: 52 mg/dL, Triglycerides: 130 mg/dL. All blood count values within standard physiological reference ranges.',
        attribution: 'MediFlow Central Diagnostic Laboratory · Verified by Dr. Emily Watson',
        isLab: true,
        searchTerms: 'lipid panel cbc blood count laboratory dr emily watson lab-9201',
    },
])

const filteredItems = computed(() => {
    const query = searchQuery.value.toLowerCase().trim()
    return rawHistoryItems.value.filter((item: any) => {
        const matchesCategory = activeCategory.value === 'all' || item.category === activeCategory.value
        const matchesSearch = !query || (item.searchTerms && item.searchTerms.includes(query)) || (item.title && item.title.toLowerCase().includes(query))
        return matchesCategory && matchesSearch
    })
})

function viewLabPdf() {
    window.print()
}
</script>

<template>
    <Head :title="`Patient Clinical History - ${patientData.name}`" />

    <!-- TOP BAR NAVIGATION -->
    <div class="top-nav-row">
        <Link href="/doctor/appointments" class="back-btn">
            ← Back to Appointments List
        </Link>
        <button class="back-btn" @click="viewLabPdf">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
            </svg>
            Print Medical Record
        </button>
    </div>

    <!-- PATIENT PROFILE HEADER CARD -->
    <div class="patient-header-card">
        <div class="patient-info-left">
            <div class="patient-avatar-xl" :style="{ background: patientData.avatarBg, color: patientData.avatarColor }">
                {{ patientData.initials }}
            </div>
            <div class="patient-details-heading">
                <h1>{{ patientData.name }}</h1>
                <p>{{ patientData.metaText }}</p>

                <div class="health-pills-row">
                    <span class="health-pill blood">Blood Type: {{ patientData.bloodType }}</span>
                    <span class="health-pill allergy">Allergy: {{ patientData.allergy }}</span>
                    <span class="health-pill condition">Condition: {{ patientData.condition }}</span>
                </div>
            </div>
        </div>

        <div class="patient-header-stats">
            <div class="stat-box">
                <span class="stat-val">{{ patientData.visitsCount }}</span>
                <span class="stat-lbl">Visits Recorded</span>
            </div>
            <div class="stat-box">
                <span class="stat-val green">{{ patientData.activePrescriptionsCount }}</span>
                <span class="stat-lbl">Active Rxs</span>
            </div>
            <div class="stat-box">
                <span class="stat-val blue">{{ patientData.labReportsCount }}</span>
                <span class="stat-lbl">Lab Scans</span>
            </div>
            <div class="stat-box">
                <span class="stat-val amber">{{ patientData.lastBp }}</span>
                <span class="stat-lbl">Last Vitals BP</span>
            </div>
        </div>
        
        <div class="header-action-group">
            <Link href="/doctor/appointments/101/medical-record/create" class="btn-action-lg primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Create Record
            </Link>
            <Link href="/doctor/appointments/101/prescriptions/create" class="btn-action-lg lime">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
                New Rx
            </Link>
        </div>
    </div>

    <!-- METRICS OVERVIEW STRIP -->
    <div class="metrics-grid">
        <div class="metric-tile">
            <div class="metric-info">
                <label>Total Hospital Visits</label>
                <b>{{ patientData.visitsCount }}</b>
                <span>Since Nov 2024</span>
            </div>
            <div class="metric-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="metric-tile">
            <div class="metric-info">
                <label>Active Prescriptions</label>
                <b>{{ patientData.activePrescriptionsCount }}</b>
                <span>Amlodipine, Atorvastatin</span>
            </div>
            <div class="metric-icon icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
            </div>
        </div>

        <div class="metric-tile">
            <div class="metric-info">
                <label>Lab Reports On File</label>
                <b>{{ patientData.labReportsCount }}</b>
                <span>Latest: Lipid & CBC</span>
            </div>
            <div class="metric-icon icon-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>

        <div class="metric-tile">
            <div class="metric-info">
                <label>Last Measured BP</label>
                <b>{{ patientData.lastBp }}</b>
                <span>mmHg (Normal)</span>
            </div>
            <div class="metric-icon icon-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- TOOLBAR ROW -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeCategory === 'all' }" @click="activeCategory = 'all'">
                All Activity <span class="tab-count">3</span>
            </button>
            <button class="tab-btn" :class="{ active: activeCategory === 'consultation' }" @click="activeCategory = 'consultation'">
                Consultations <span class="tab-count">1</span>
            </button>
            <button class="tab-btn" :class="{ active: activeCategory === 'prescription' }" @click="activeCategory = 'prescription'">
                Prescriptions <span class="tab-count">1</span>
            </button>
            <button class="tab-btn" :class="{ active: activeCategory === 'lab' }" @click="activeCategory = 'lab'">
                Lab Tests <span class="tab-count">1</span>
            </button>
        </div>

        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search diagnosis or Rx..." />
        </div>
    </div>

    <!-- CLINICAL FEED -->
    <div class="history-feed">
        <div v-for="item in filteredItems" :key="item.id" class="history-card">
            <div class="card-header-row">
                <div class="card-date-meta">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    {{ item.dateStr }}
                </div>
                <span
                    class="badge-tag"
                    :class="{
                        'tag-consultation': item.category === 'consultation',
                        'tag-prescription': item.category === 'prescription',
                        'tag-lab': item.category === 'lab'
                    }"
                >
                    {{ item.categoryLabel }}
                </span>
            </div>

            <div class="history-title">{{ item.title }}</div>

            <!-- DIAGNOSIS BLOCK -->
            <div v-if="item.primaryDiag" class="diag-box">
                <div class="diag-header">
                    <b>{{ item.primaryDiag }}</b>
                    <span v-if="item.icdCode" class="icd-code">{{ item.icdCode }}</span>
                </div>
                <p class="notes-text">{{ item.notes }}</p>
            </div>

            <!-- MEDICATION LIST -->
            <div v-if="item.medications" class="med-list-mini">
                <div v-for="med in item.medications" :key="med.name" class="med-item-mini">
                    <div>
                        <b>{{ med.name }}</b>
                        <div class="med-sub">{{ med.directions }}</div>
                    </div>
                    <span class="refill-tag">{{ med.refills }}</span>
                </div>
            </div>

            <!-- LAB NOTES -->
            <p v-if="item.isLab" class="notes-text">{{ item.notes }}</p>

            <div class="card-footer-row">
                <div class="doc-attribution">
                    <img v-if="item.doctorAvatar" :src="item.doctorAvatar" :alt="item.doctor" />
                    {{ item.doctor || item.attribution }}
                </div>
                <Link v-if="item.actionUrl" :href="item.actionUrl" class="card-action-btn">
                    {{ item.actionLabel }}
                </Link>
                <button v-else-if="item.isLab" class="card-action-btn" @click="viewLabPdf">
                    View Lab PDF →
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.top-nav-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--forest);
    background: var(--cream);
    border: 1px solid var(--line);
    padding: 6px 14px;
    border-radius: 999px;
    text-decoration: none;
    cursor: pointer;
    transition: all 150ms ease;
}
.back-btn:hover { background: var(--card); border-color: var(--forest); }

/* PATIENT HEADER CARD */
.patient-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    box-shadow: var(--shadow-card);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
    margin-bottom: 24px;
}

.patient-info-left { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.patient-avatar-xl {
    width: 76px;
    height: 72px;
    border-radius: 50%;
    font-weight: 800;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.patient-details-heading h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-bottom: 2px; }
.patient-details-heading p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.health-pills-row { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.health-pill { font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 999px; border: 1px solid var(--line); background: var(--cream); color: var(--ink); }
.health-pill.allergy { background: #FEE2E2; color: #991B1B; border-color: #FCA5A5; }
.health-pill.blood { background: #DCFCE7; color: #15803D; border-color: #BBF7D0; }

.header-action-group { display: flex; gap: 10px; flex-wrap: wrap; }
.btn-action-lg {
    height: 42px;
    padding: 0 18px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 700;
    border: 1px solid var(--line);
    transition: all 150ms ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-action-lg.primary { background: var(--forest); color: #fff; }
.btn-action-lg.primary:hover { background: var(--forest-2); }
.btn-action-lg.lime { background: var(--lime); color: var(--lime-text); border-color: #c4dc3c; }
.btn-action-lg.lime:hover { background: #d2e85a; }

/* METRICS STRIP */
.metrics-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
@media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-tile {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.metric-info label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.metric-info b { font-family: var(--font-mono); font-size: 22px; font-weight: 800; color: var(--forest); display: block; }
.metric-info span { font-size: 12px; color: var(--ink-muted); display: block; }

.metric-icon { width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--cream); color: var(--forest); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.metric-icon svg { width: 20px; height: 20px; }
.metric-icon.icon-green { background: #DCFCE7; color: #15803D; }
.metric-icon.icon-amber { background: #FEF3C7; color: #B45309; }
.metric-icon.icon-blue { background: #E0F2FE; color: #0369A1; }

/* TOOLBAR & FILTER TABS */
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }

.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); }
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
.tab-count { font-family: var(--font-mono); font-size: 11px; padding: 2px 6px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-count { background: rgba(255,255,255,0.2); color: #fff; }

.search-box { position: relative; width: 260px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-box input { width: 100%; height: 38px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13px; color: var(--ink); transition: border-color 150ms ease; }
.search-box input:focus { border-color: var(--forest); outline: none; }

/* CHRONOLOGICAL CLINICAL HISTORY FEED */
.history-feed { display: flex; flex-direction: column; gap: 20px; }

.history-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px;
    box-shadow: var(--shadow-card);
    transition: box-shadow 150ms ease;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.history-card:hover { box-shadow: var(--shadow-lift); }

.card-header-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid var(--line); padding-bottom: 14px; }
.card-date-meta { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; color: var(--ink-muted); display: flex; align-items: center; gap: 8px; }

.badge-tag { font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.03em; }
.tag-consultation { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.tag-prescription { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.tag-lab { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }

.history-title { font-size: 17px; font-weight: 800; color: var(--forest); }

.diag-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; }
.diag-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; flex-wrap: wrap; gap: 8px; }
.diag-header b { font-size: 14.5px; font-weight: 800; color: var(--ink); }
.icd-code { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; padding: 2px 8px; border-radius: var(--radius-sm); }

.notes-text { font-size: 13.5px; color: var(--ink); line-height: 1.6; }

.med-list-mini { display: flex; flex-direction: column; gap: 8px; }
.med-item-mini { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
.med-item-mini b { font-size: 13.5px; font-weight: 700; color: var(--forest); }
.med-sub { font-size: 12px; color: var(--ink-muted); }
.refill-tag { font-family: var(--font-mono); font-weight: 700; font-size: 12px; color: var(--forest); }

.card-footer-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding-top: 12px; border-top: 1px dashed var(--line); }
.doc-attribution { display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; color: var(--ink-muted); }
.doc-attribution img { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }

.card-action-btn { height: 32px; padding: 0 14px; border-radius: 999px; border: 1px solid var(--line); background: var(--cream); font-size: 12.5px; font-weight: 600; color: var(--forest); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; cursor: pointer; }
.card-action-btn:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
