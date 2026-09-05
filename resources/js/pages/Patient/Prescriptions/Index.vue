<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface PrescriptionMedication {
    name: string
    dose: string
    freq: string
}

interface Prescription {
    id: number
    code: string
    doctorName: string
    doctorDept: string
    avatar: string
    issuedDate: string
    status: 'active' | 'expired'
    refillsText: string
    medications: PrescriptionMedication[]
    special_instructions: string | null
}

interface PrescriptionStats {
    active_count: number
    expired_count: number
    total_count: number
}

const props = defineProps<{
    prescriptions: Prescription[]
    stats: PrescriptionStats
}>()

const activeTab = ref('all')
const searchQuery = ref('')

const activeCount = computed(() => props.stats.active_count)
const expiredCount = computed(() => props.stats.expired_count)

const filteredPrescriptions = computed(() => {
    return props.prescriptions.filter((p) => {
        const matchesTab = activeTab.value === 'all' || p.status === activeTab.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesSearch = !q || p.code.toLowerCase().includes(q) || p.doctorName.toLowerCase().includes(q) || p.medications.some((m) => m.name.toLowerCase().includes(q))
        return matchesTab && matchesSearch
    })
})
</script>

<template>
    <Head title="My Prescriptions" />

    <!-- METRICS OVERVIEW STRIP -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <label>Active Prescriptions</label>
                <b>{{ activeCount }}</b>
                <span>Currently in use</span>
            </div>
            <div class="metric-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Available Refills</label>
                <b>3</b>
                <span>Authorized by doctor</span>
            </div>
            <div class="metric-icon green-bg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Total Prescriptions Issued</label>
                <b>{{ props.stats.total_count }}</b>
                <span>Lifetime record</span>
            </div>
            <div class="metric-icon gray-bg">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- TOOLBAR ROW -->
    <div class="toolbar-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: activeTab === 'all' }" @click="activeTab = 'all'">
                All <span class="tab-badge">{{ props.stats.total_count }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'active' }" @click="activeTab = 'active'">
                Active <span class="tab-badge">{{ activeCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: activeTab === 'expired' }" @click="activeTab = 'expired'">
                Completed <span class="tab-badge">{{ expiredCount }}</span>
            </button>
        </div>

        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search medication or doctor..." />
        </div>
    </div>

    <!-- PRESCRIPTIONS LIST -->
    <div class="prescriptions-list">
        <div v-for="rx in filteredPrescriptions" :key="rx.id" class="rx-card">
            <div class="rx-meta-col">
                <span class="rx-code-badge">{{ rx.code }}</span>

                <div class="doc-info-sm">
                    <img :src="rx.avatar" :alt="rx.doctorName" />
                    <div>
                        <b>{{ rx.doctorName }}</b>
                        <span>{{ rx.doctorDept }}</span>
                    </div>
                </div>

                <div class="issued-txt">
                    Issued: <strong>{{ rx.issuedDate }}</strong>
                </div>
            </div>

            <div class="rx-meds-col">
                <h4>Prescribed Medications ({{ rx.medications.length }})</h4>
                <div class="med-pills-list">
                    <div v-for="med in rx.medications" :key="med.name" class="med-item-box">
                        <div>
                            <div class="med-title">{{ med.name }}</div>
                            <div class="med-dose">{{ med.dose }}</div>
                        </div>
                        <span class="med-freq">{{ med.freq }}</span>
                    </div>
                </div>
            </div>

            <div class="rx-action-col">
                <div>
                    <span v-if="rx.status === 'active'" class="status-tag status-active">Active</span>
                    <span v-else class="status-tag status-expired">Course Completed</span>

                    <div class="refill-badge" :class="{ muted: rx.status === 'expired' }">
                        <svg v-if="rx.status === 'active'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                        </svg>
                        {{ rx.refillsText }}
                    </div>
                </div>

                <div class="action-btns">
                    <Link :href="`/patient/prescriptions/${rx.id}`" class="btn-action" :class="{ primary: rx.status === 'active' }">
                        View Detail →
                    </Link>
                    <button class="btn-action" title="Download PDF" @click="alert(`Downloading ${rx.code} PDF...`)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
@media (max-width: 900px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; }
.metric-info label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.metric-info b { font-family: var(--font-mono); font-size: 24px; font-weight: 800; color: var(--forest); display: block; }
.metric-info span { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

.metric-icon { width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.metric-icon.green-bg { background: #DCFCE7; color: #15803D; }
.metric-icon.gray-bg { background: var(--cream-alt); color: var(--ink); }
.metric-icon svg { width: 22px; height: 22px; }

.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 28px; }

.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); }
.tab-btn { padding: 8px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }
.tab-badge { font-family: var(--font-mono); font-size: 11px; padding: 2px 7px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-badge { background: rgba(255,255,255,0.2); color: #fff; }

.search-box { position: relative; width: 280px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-box input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); }
.search-box input:focus { outline: none; border-color: var(--forest); }

.prescriptions-list { display: flex; flex-direction: column; gap: 20px; }

.rx-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); transition: transform 150ms ease, box-shadow 150ms ease; display: grid; grid-template-columns: 240px 1fr auto; gap: 28px; align-items: start; }
.rx-card:hover { box-shadow: var(--shadow-lift); }
@media (max-width: 1024px) { .rx-card { grid-template-columns: 1fr; gap: 20px; } }

.rx-meta-col { display: flex; flex-direction: column; gap: 12px; }
.rx-code-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; width: fit-content; }

.doc-info-sm { display: flex; align-items: center; gap: 12px; }
.doc-info-sm img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; background: var(--cream-alt); }
.doc-info-sm b { display: block; font-size: 14.5px; font-weight: 700; color: var(--ink); }
.doc-info-sm span { display: block; font-size: 12px; color: var(--ink-muted); }

.issued-txt { font-size: 12px; color: var(--ink-muted); margin-top: 4px; }

.rx-meds-col { display: flex; flex-direction: column; gap: 12px; }
.rx-meds-col h4 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); margin: 0; }

.med-pills-list { display: flex; flex-direction: column; gap: 8px; }
.med-item-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
.med-title { font-size: 14.5px; font-weight: 700; color: var(--forest); }
.med-dose { font-size: 13px; color: var(--ink-muted); font-weight: 500; }
.med-freq { font-family: var(--font-mono); font-size: 12px; font-weight: 600; background: var(--card); border: 1px solid var(--line); padding: 3px 8px; border-radius: var(--radius-sm); color: var(--ink); }

.rx-action-col { display: flex; flex-direction: column; align-items: flex-end; gap: 16px; justify-content: space-between; height: 100%; }
@media (max-width: 1024px) { .rx-action-col { align-items: flex-start; flex-direction: row; border-top: 1px solid var(--line); padding-top: 16px; width: 100%; } }

.status-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
.status-active { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-expired { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }

.refill-badge { font-size: 12px; font-weight: 600; color: var(--forest); display: flex; align-items: center; gap: 4px; margin-top: 6px; }
.refill-badge.muted { color: var(--ink-muted); }

.action-btns { display: flex; gap: 8px; flex-wrap: wrap; }
.btn-action { height: 38px; padding: 0 16px; border-radius: 999px; font-size: 13px; font-weight: 600; border: 1px solid var(--line); background: var(--card); color: var(--ink); text-decoration: none; transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
.btn-action:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
.btn-action.primary { background: var(--forest); color: #fff; }
.btn-action.primary:hover { background: var(--forest-2); }
</style>
