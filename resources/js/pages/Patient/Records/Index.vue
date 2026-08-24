<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const activeCat = ref('all')
const searchQuery = ref('')

const vitals = ref([
    { label: 'Blood Pressure', value: '120/80', unit: 'mmHg', sub: 'Last recorded: July 14, 2026', icon: 'heart' },
    { label: 'Heart Rate', value: '72', unit: 'bpm', sub: 'Normal Resting Rate', icon: 'pulse' },
    { label: 'Body Weight', value: '74.5', unit: 'kg', sub: 'BMI: 22.8 (Optimal)', icon: 'scale' },
    { label: 'Blood Sugar', value: '98', unit: 'mg/dL', sub: 'Fasting Glucose', icon: 'drop' },
])

const records = ref([
    {
        id: 301,
        year: '2026',
        date: 'July 14, 2026',
        category: 'consultation',
        typeLabel: 'Consultation',
        tagClass: 'tag-consultation',
        title: 'Cardiology Follow-Up & ECG Assessment',
        description: 'Routine cardiovascular evaluation following reported minor exertion-related chest tightness. Resting ECG revealed normal sinus rhythm. Adjusted anti-hypertensive medication dosage.',
        doctorName: 'Dr. Sarah Jenkins',
        doctorDept: 'Cardiology Department',
        avatar: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100',
        highlight: true,
        buttonText: 'View Full Record →',
    },
    {
        id: 302,
        year: '2026',
        date: 'June 28, 2026',
        category: 'lab',
        typeLabel: 'Lab Report',
        tagClass: 'tag-lab',
        title: 'Comprehensive Blood Count (CBC) & Lipid Panel',
        description: 'Total Cholesterol: 185 mg/dL (Desirable), HDL: 52 mg/dL, Triglycerides: 130 mg/dL. All blood count values within standard physiological reference ranges.',
        doctorName: 'Dr. Emily Watson',
        doctorDept: 'MediFlow Diagnostics Lab',
        avatar: 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=100',
        highlight: false,
        buttonText: 'View Report PDF →',
    },
    {
        id: 303,
        year: '2025',
        date: 'November 12, 2025',
        category: 'consultation',
        typeLabel: 'Consultation',
        tagClass: 'tag-consultation',
        title: 'Annual Health Examination & Preventive Screening',
        description: 'General wellness physical examination. Patient reported excellent physical endurance. Vaccination history updated (Tdap booster administered).',
        doctorName: 'Dr. Alan Grant',
        doctorDept: 'General Practice',
        avatar: 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=100',
        highlight: false,
        buttonText: 'View Full Record →',
    },
    {
        id: 304,
        year: '2025',
        date: 'August 04, 2025',
        category: 'vital',
        typeLabel: 'Vitals Telemetry',
        tagClass: 'tag-vital',
        title: '24-Hour Ambulatory Blood Pressure Telemetry',
        description: 'Ambulatory monitoring telemetry session. Mean daytime BP: 124/82 mmHg, Mean nocturnal BP: 112/72 mmHg. Normal nocturnal dipper pattern preserved.',
        doctorName: 'MediFlow Telemetry Division',
        doctorDept: 'Cardiology Telemetry',
        avatar: '',
        highlight: false,
        buttonText: 'View Telemetry Data →',
    },
])

const filteredRecords = computed(() => {
    return records.value.filter((rec) => {
        const matchesCat = activeCat.value === 'all' || rec.category === activeCat.value
        const q = searchQuery.value.toLowerCase().trim()
        const matchesSearch = !q || rec.title.toLowerCase().includes(q) || rec.description.toLowerCase().includes(q) || rec.doctorName.toLowerCase().includes(q)
        return matchesCat && matchesSearch
    })
})

const groupedRecords = computed(() => {
    const map: Record<string, typeof records.value> = {}
    filteredRecords.value.forEach((rec) => {
        if (!map[rec.year]) map[rec.year] = []
        map[rec.year].push(rec)
    })
    return map
})
</script>

<template>
    <Head title="Medical History" />

    <!-- LATEST VITALS SUMMARY STRIP -->
    <div class="vitals-grid">
        <div v-for="v in vitals" :key="v.label" class="vital-card">
            <div class="vital-info">
                <label>{{ v.label }}</label>
                <b>{{ v.value }} <small class="unit-text">{{ v.unit }}</small></b>
                <span>{{ v.sub }}</span>
            </div>
            <div class="vital-icon">
                <svg v-if="v.icon === 'heart'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <svg v-else-if="v.icon === 'pulse'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <svg v-else-if="v.icon === 'scale'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- FILTER CONTROLS -->
    <div class="filter-row">
        <div class="category-pills">
            <button class="cat-pill" :class="{ active: activeCat === 'all' }" @click="activeCat = 'all'">All Records</button>
            <button class="cat-pill" :class="{ active: activeCat === 'consultation' }" @click="activeCat = 'consultation'">Consultations</button>
            <button class="cat-pill" :class="{ active: activeCat === 'lab' }" @click="activeCat = 'lab'">Lab Tests</button>
            <button class="cat-pill" :class="{ active: activeCat === 'vital' }" @click="activeCat = 'vital'">Vitals Log</button>
        </div>

        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search diagnoses or notes..." />
        </div>
    </div>

    <!-- CHRONOLOGICAL TIMELINE -->
    <div class="timeline-wrap">
        <div v-for="(recList, year) in groupedRecords" :key="year" class="timeline-year-group">
            <span class="year-badge">{{ year }}</span>

            <div v-for="rec in recList" :key="rec.id" class="timeline-item" :class="{ highlight: rec.highlight }">
                <div class="timeline-dot"></div>
                <div class="record-card">
                    <div class="record-body">
                        <div class="record-header-meta">
                            <span class="record-date">{{ rec.date }}</span>
                            <span class="type-tag" :class="rec.tagClass">{{ rec.typeLabel }}</span>
                        </div>
                        <h3>{{ rec.title }}</h3>
                        <p>{{ rec.description }}</p>
                        <div class="doc-attribution">
                            <img v-if="rec.avatar" :src="rec.avatar" :alt="rec.doctorName" />
                            {{ rec.doctorName }} · {{ rec.doctorDept }}
                        </div>
                    </div>

                    <Link :href="`/patient/medical-records/${rec.id}`" class="action-btn-sm">
                        {{ rec.buttonText }}
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.vitals-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
@media (max-width: 1100px) { .vitals-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .vitals-grid { grid-template-columns: 1fr; } }

.vital-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 18px 20px; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; }
.vital-info label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.vital-info b { font-family: var(--font-mono); font-size: 20px; font-weight: 800; color: var(--forest); display: block; }
.unit-text { font-size: 12px; font-weight: normal; }
.vital-info span { font-size: 11.5px; color: var(--ink-muted); display: block; margin-top: 2px; }

.vital-icon { width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--lime-soft); color: var(--lime-text); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vital-icon svg { width: 20px; height: 20px; }

.filter-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 28px; }

.category-pills { display: flex; gap: 8px; flex-wrap: wrap; }
.cat-pill { height: 36px; padding: 0 16px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
.cat-pill:hover { border-color: var(--forest); color: var(--ink); }
.cat-pill.active { background: var(--forest); color: #fff; border-color: var(--forest); }

.search-box { position: relative; width: 280px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-box input { width: 100%; height: 38px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13px; color: var(--ink); }
.search-box input:focus { outline: none; border-color: var(--forest); }

.timeline-wrap { position: relative; padding-left: 28px; }
.timeline-wrap::before { content: ""; position: absolute; left: 11px; top: 12px; bottom: 12px; width: 2px; background: var(--line); }

.timeline-year-group { margin-bottom: 32px; }
.timeline-year-group:last-child { margin-bottom: 0; }

.year-badge { display: inline-block; font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--forest); color: var(--lime); padding: 4px 12px; border-radius: 999px; margin-bottom: 20px; position: relative; margin-left: -28px; box-shadow: var(--shadow-sm); }

.timeline-item { position: relative; margin-bottom: 20px; }
.timeline-item:last-child { margin-bottom: 0; }

.timeline-dot { position: absolute; left: -28px; top: 22px; width: 12px; height: 12px; border-radius: 50%; background: var(--card); border: 3px solid var(--forest); transform: translateX(-50%); z-index: 2; }
.timeline-item.highlight .timeline-dot { border-color: #15803D; background: var(--lime); }

.record-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); transition: all 150ms ease; display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: flex-start; }
.record-card:hover { box-shadow: var(--shadow-lift); border-color: rgba(22,48,31,0.2); }
@media (max-width: 768px) { .record-card { grid-template-columns: 1fr; } }

.record-header-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px; }
.record-date { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; color: var(--ink-muted); }
.type-tag { font-size: 11.5px; font-weight: 700; padding: 2px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.03em; }
.tag-consultation { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
.tag-lab { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.tag-vital { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

.record-body h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin: 0 0 4px 0; }
.record-body p { font-size: 14px; color: var(--ink-muted); line-height: 1.5; margin: 0 0 12px 0; }

.doc-attribution { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--ink); }
.doc-attribution img { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }

.action-btn-sm { height: 38px; padding: 0 16px; border-radius: 999px; border: 1px solid var(--line); background: var(--cream); font-size: 13px; font-weight: 600; color: var(--forest); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 150ms ease; flex-shrink: 0; }
.action-btn-sm:hover { border-color: var(--forest); background: var(--forest); color: #fff; }
</style>
