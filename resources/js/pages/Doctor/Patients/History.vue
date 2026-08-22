<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const selectedFilter = ref('all')

const patient = {
    id: 'MDF-9021',
    name: 'Habib Hossain',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200',
    dob: 'Jan 15, 1988 (38 yrs)',
    gender: 'Male',
    phone: '+1 (555) 234-5678',
    bloodType: 'O+ Positive',
    emergency: 'Tanjila Hossain (Wife) +1 (555) 876-5432',
    conditions: ['Stage 1 Essential Hypertension (ICD-10 I10)', 'Hyperlipidemia (Borderline)'],
    allergies: ['Penicillin (Severe Anaphylaxis Warning)', 'NSAIDs'],
}

const timelineEvents = [
    {
        id: 'REC-301',
        type: 'record',
        category: 'Consultation & SOAP Note',
        date: 'August 22, 2026',
        doctor: 'Dr. Sarah Jenkins (Cardiology)',
        title: 'Stage 1 Essential Hypertension Follow-up',
        icdCode: 'ICD-10 I10',
        content:
            'S: Patient reports mild morning occipital headaches for 2 weeks.\nO: BP 135/88 mmHg, HR 72 bpm, regular rhythm. Normal S1/S2.\nA: Essential Hypertension, moderately controlled.\nP: Initiate Lisinopril 10mg daily. Re-check BP in 3 weeks.',
        actionUrl: '/doctor/records/REC-301/edit',
        actionLabel: 'Amend Record',
    },
    {
        id: 'RX-8041',
        type: 'prescription',
        category: 'Digital Prescription',
        date: 'August 22, 2026',
        doctor: 'Dr. Sarah Jenkins (Cardiology)',
        title: 'Lisinopril 10mg Oral Tablet',
        instructions: 'Take 1 tablet daily in the morning with a full glass of water. Qty: 30 Tablets. Refills: 3',
        actionUrl: '/doctor/prescriptions/RX-8041/supersede',
        actionLabel: 'Supersede / Revise Rx',
    },
    {
        id: 'LAB-4029',
        type: 'lab',
        category: 'Diagnostic Test Result',
        date: 'May 14, 2026',
        doctor: 'MediFlow Diagnostics Lab',
        title: '12-Lead Electrocardiogram (ECG) & Fasting Lipid Panel',
        summary: 'Normal sinus rhythm @ 70 bpm. Total Cholesterol: 198 mg/dL, HDL: 48 mg/dL, LDL: 118 mg/dL, Triglycerides: 145 mg/dL.',
        actionUrl: '#',
        actionLabel: 'View Report PDF',
    },
]
</script>

<template>
    <Head :title="`Clinical History - ${patient.name}`" />

    <!-- TOP NAV ROW -->
    <div class="nav-bar-row">
        <Link href="/doctor/appointments/MDF-9021" class="back-link">
            ← Back to Appointment #MDF-9021
        </Link>

        <div class="header-action-group">
            <Link href="/doctor/appointments/MDF-9021/records/create" class="btn btn-outline">
                + New Medical Record
            </Link>
            <Link href="/doctor/appointments/MDF-9021/prescriptions/create" class="btn btn-primary">
                + Issue Prescription
            </Link>
        </div>
    </div>

    <!-- PATIENT HERO CARD -->
    <div class="patient-card">
        <div class="patient-main">
            <img :src="patient.avatar" :alt="patient.name" class="patient-lg-avatar" />
            <div class="patient-meta">
                <div class="name-row">
                    <h2>{{ patient.name }}</h2>
                    <span class="patient-id-badge">ID: {{ patient.id }}</span>
                </div>

                <div class="patient-stats-strip">
                    <span class="stat-pill">DOB: {{ patient.dob }}</span>
                    <span class="stat-pill">Gender: {{ patient.gender }}</span>
                    <span class="stat-pill">Blood Type: {{ patient.bloodType }}</span>
                    <span class="stat-pill">Phone: {{ patient.phone }}</span>
                </div>

                <div class="conditions-row">
                    <span v-for="cond in patient.conditions" :key="cond" class="cond-pill">
                        🩺 {{ cond }}
                    </span>
                    <span v-for="alg in patient.allergies" :key="alg" class="allergy-pill">
                        ⚠️ {{ alg }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER TABS & TIMELINE FEED -->
    <div class="timeline-section">
        <div class="timeline-header">
            <h3>Chronological Patient Medical History</h3>

            <div class="tab-filters">
                <button class="tab-btn" :class="{ active: selectedFilter === 'all' }" @click="selectedFilter = 'all'">All Timeline</button>
                <button class="tab-btn" :class="{ active: selectedFilter === 'record' }" @click="selectedFilter = 'record'">SOAP Records</button>
                <button class="tab-btn" :class="{ active: selectedFilter === 'prescription' }" @click="selectedFilter = 'prescription'">Prescriptions</button>
                <button class="tab-btn" :class="{ active: selectedFilter === 'lab' }" @click="selectedFilter = 'lab'">Labs & Tests</button>
            </div>
        </div>

        <div class="timeline-feed">
            <div v-for="event in timelineEvents" :key="event.id" class="feed-card">
                <div class="feed-header">
                    <div class="event-meta">
                        <span class="category-tag" :class="event.type">{{ event.category }}</span>
                        <span class="event-date">{{ event.date }}</span>
                        <span class="event-author">by {{ event.doctor }}</span>
                    </div>

                    <span class="ref-code">Ref #{{ event.id }}</span>
                </div>

                <div class="feed-body">
                    <h4 class="event-title">
                        {{ event.title }}
                        <span v-if="event.icdCode" class="icd-tag">{{ event.icdCode }}</span>
                    </h4>

                    <p v-if="event.content" class="event-text">{{ event.content }}</p>
                    <p v-if="event.instructions" class="event-text"><strong>Directions:</strong> {{ event.instructions }}</p>
                    <p v-if="event.summary" class="event-text"><strong>Lab Summary:</strong> {{ event.summary }}</p>
                </div>

                <div class="feed-footer">
                    <Link :href="event.actionUrl" class="btn-sm btn-outline">{{ event.actionLabel }}</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-bar-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}
.back-link { font-size: 13.5px; font-weight: 700; color: var(--forest); text-decoration: none; }
.header-action-group { display: flex; gap: 10px; }

.patient-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px;
    box-shadow: var(--shadow-card);
    margin-bottom: 28px;
}

.patient-main { display: flex; gap: 24px; align-items: flex-start; }
@media (max-width: 600px) { .patient-main { flex-direction: column; } }

.patient-lg-avatar { width: 84px; height: 84px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }

.name-row { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
.name-row h2 { font-size: 24px; font-weight: 800; color: var(--forest); margin: 0; }
.patient-id-badge { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); padding: 3px 10px; border-radius: 999px; }

.patient-stats-strip { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
.stat-pill { font-size: 12px; font-weight: 600; color: var(--ink-muted); background: var(--cream); border: 1px solid var(--line); padding: 4px 12px; border-radius: 999px; }

.conditions-row { display: flex; gap: 8px; flex-wrap: wrap; }
.cond-pill { font-size: 12px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; padding: 4px 12px; border-radius: 999px; }
.allergy-pill { font-size: 12px; font-weight: 700; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 4px 12px; border-radius: 999px; }

.timeline-section { display: flex; flex-direction: column; gap: 20px; }

.timeline-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 14px;
}
.timeline-header h3 { font-size: 18px; font-weight: 800; color: var(--forest); }

.tab-filters {
    display: flex;
    gap: 6px;
    background: var(--cream);
    padding: 4px;
    border-radius: 999px;
    border: 1px solid var(--line);
}

.tab-btn {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink-muted);
    transition: all 150ms ease;
}

.tab-btn.active {
    background: var(--card);
    color: var(--forest);
    box-shadow: var(--shadow-sm);
}

.timeline-feed { display: flex; flex-direction: column; gap: 20px; }

.feed-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-card);
}

.feed-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    border-bottom: 1px dashed var(--line);
    padding-bottom: 10px;
}

.event-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.category-tag { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; padding: 3px 10px; border-radius: 999px; }
.category-tag.record { background: #E0F2FE; color: #0369A1; }
.category-tag.prescription { background: #DCFCE7; color: #15803D; }
.category-tag.lab { background: #FEF3C7; color: #B45309; }

.event-date { font-size: 12.5px; font-weight: 700; color: var(--forest); }
.event-author { font-size: 12px; color: var(--ink-muted); }
.ref-code { font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: var(--ink-muted); }

.event-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
.icd-tag { font-family: var(--font-mono); font-size: 11px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); padding: 2px 8px; border-radius: 4px; color: var(--ink); }

.event-text { font-size: 13.5px; color: var(--ink); line-height: 1.5; white-space: pre-line; margin-bottom: 14px; }

.feed-footer { display: flex; justify-content: flex-end; }

.btn { display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-sm { display: inline-flex; align-items: center; justify-content: center; padding: 6px 16px; border-radius: 999px; font-size: 12.5px; font-weight: 700; text-decoration: none; transition: all 150ms ease; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
