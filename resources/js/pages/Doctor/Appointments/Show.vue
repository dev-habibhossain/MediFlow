<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const currentStatus = ref('in-progress')

const appointment = {
    id: 'MDF-9021',
    patientName: 'Habib Hossain',
    patientAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200',
    dob: 'Jan 15, 1988 (Age 38)',
    gender: 'Male',
    phone: '+1 (555) 234-5678',
    email: 'habib.hossain@example.com',
    date: 'August 22, 2026',
    time: '09:30 AM EST',
    mode: 'In-Person (Room 302)',
    department: 'Cardiology Dept',
    fee: '$120.00',
    statusLabel: 'In Progress',
    vitals: {
        bp: '135/88 mmHg',
        hr: '72 bpm',
        temp: '98.6 °F',
        spo2: '98%',
        weight: '74 kg',
    },
    complaint: 'Patient reports persistent mild headache for the past 2 weeks with elevated morning blood pressure readings ranging between 135-140 systolic.',
    historySummary: 'Diagnosed with Stage 1 Essential Hypertension (2024). No prior surgeries. Regular compliance with dietary sodium reduction.',
    allergies: ['Penicillin (Moderate Rash)', 'NSAIDs (Mild Gastric Upset)'],
}
</script>

<template>
    <Head :title="`Appointment ${appointment.id}`" />

    <!-- BREADCRUMB / BACK LINK -->
    <div class="nav-bar-row">
        <Link href="/doctor/appointments" class="back-link">
            ← Back to All Appointments
        </Link>
        <span class="appointment-tag">Appointment Ref #{{ appointment.id }}</span>
    </div>

    <!-- HERO HEADER CARD -->
    <div class="hero-card">
        <div class="hero-main">
            <img :src="appointment.patientAvatar" :alt="appointment.patientName" class="patient-hero-avatar" />
            <div class="hero-meta">
                <div class="hero-title-row">
                    <h2>{{ appointment.patientName }}</h2>
                    <span class="status-pill-badge in-progress">{{ appointment.statusLabel }}</span>
                </div>
                <p>{{ appointment.date }} at {{ appointment.time }} • {{ appointment.mode }}</p>
                <div class="hero-badges">
                    <span class="meta-pill">DOB: {{ appointment.dob }}</span>
                    <span class="meta-pill">Phone: {{ appointment.phone }}</span>
                    <span class="meta-pill">Dept: {{ appointment.department }}</span>
                </div>
            </div>
        </div>

        <div class="hero-actions">
            <Link :href="`/doctor/patients/${appointment.id}/history`" class="btn btn-outline">
                View Patient History
            </Link>
        </div>
    </div>

    <!-- VITALS STRIP -->
    <div class="vitals-strip">
        <div class="vital-box">
            <label>Blood Pressure</label>
            <b>{{ appointment.vitals.bp }}</b>
        </div>
        <div class="vital-box">
            <label>Heart Rate</label>
            <b>{{ appointment.vitals.hr }}</b>
        </div>
        <div class="vital-box">
            <label>Body Temp</label>
            <b>{{ appointment.vitals.temp }}</b>
        </div>
        <div class="vital-box">
            <label>SpO2 Level</label>
            <b>{{ appointment.vitals.spo2 }}</b>
        </div>
        <div class="vital-box">
            <label>Weight</label>
            <b>{{ appointment.vitals.weight }}</b>
        </div>
    </div>

    <!-- DETAIL TWO COLUMN GRID -->
    <div class="detail-grid">
        <!-- LEFT: CLINICAL NOTES & ACTION TILES -->
        <div class="left-column">
            <!-- CHIEF COMPLAINT CARD -->
            <div class="content-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Chief Complaint & Symptoms
                </div>
                <p class="complaint-text">{{ appointment.complaint }}</p>
            </div>

            <!-- ALLERGIES & HISTORY -->
            <div class="content-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Known Allergies & Clinical Background
                </div>
                
                <div class="allergies-list">
                    <span v-for="allergy in appointment.allergies" :key="allergy" class="allergy-badge">
                        ⚠️ {{ allergy }}
                    </span>
                </div>

                <div class="history-summary">
                    <b style="font-size: 13px; color: var(--forest); display: block; margin-bottom: 4px;">Medical History Summary:</b>
                    <p style="font-size: 13px; color: var(--ink);">{{ appointment.historySummary }}</p>
                </div>
            </div>

            <!-- ACTION BUTTONS GRID -->
            <div class="action-cards-grid">
                <Link :href="`/doctor/appointments/${appointment.id}/records/create`" class="action-card-btn">
                    <div class="action-icon bg-forest-soft">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <div>
                        <b>Create Medical Record</b>
                        <span>Log SOAP diagnosis & notes</span>
                    </div>
                </Link>

                <Link :href="`/doctor/appointments/${appointment.id}/prescriptions/create`" class="action-card-btn">
                    <div class="action-icon bg-lime-soft">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div>
                        <b>Issue New Prescription</b>
                        <span>Write rx medication order</span>
                    </div>
                </Link>
            </div>
        </div>

        <!-- RIGHT: VISIT STATUS & PATIENT METADATA -->
        <div class="right-column">
            <!-- STATUS UPDATE CARD -->
            <div class="content-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Visit Status Controls
                </div>

                <div class="status-controls">
                    <label style="font-size: 12px; font-weight: 700; color: var(--ink-muted); text-transform: uppercase;">Update Status</label>
                    <select v-model="currentStatus" class="form-select">
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed / Discharged</option>
                        <option value="no-show">No-Show / Cancelled</option>
                    </select>

                    <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" @click="alert('Status updated!')">
                        Save Status Change
                    </button>
                </div>
            </div>

            <!-- PATIENT PROFILE CARD -->
            <div class="content-card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Patient Information
                </div>

                <div class="info-list">
                    <div class="info-item">
                        <label>Full Name</label>
                        <span>{{ appointment.patientName }}</span>
                    </div>
                    <div class="info-item">
                        <label>Email Address</label>
                        <span>{{ appointment.email }}</span>
                    </div>
                    <div class="info-item">
                        <label>Phone Number</label>
                        <span>{{ appointment.phone }}</span>
                    </div>
                    <div class="info-item">
                        <label>Consultation Fee</label>
                        <b class="mono-text">{{ appointment.fee }}</b>
                    </div>
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
    margin-bottom: 16px;
}
.back-link { font-size: 13.5px; font-weight: 700; color: var(--forest); text-decoration: none; }
.appointment-tag { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; color: var(--ink-muted); }

.hero-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px 28px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 24px;
}

.hero-main { display: flex; align-items: center; gap: 20px; }
.patient-hero-avatar { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; }

.hero-title-row { display: flex; align-items: center; gap: 12px; }
.hero-title-row h2 { font-size: 22px; font-weight: 800; color: var(--forest); margin: 0; }

.hero-meta p { font-size: 13px; color: var(--ink-muted); margin: 4px 0 8px 0; }
.hero-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.meta-pill { font-size: 11.5px; font-weight: 600; background: var(--cream); border: 1px solid var(--line); padding: 3px 10px; border-radius: 999px; }

.vitals-strip {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 900px) { .vitals-strip { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 500px) { .vitals-strip { grid-template-columns: 1fr 1fr; } }

.vital-box {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 16px;
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.vital-box label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.vital-box b { font-family: var(--font-mono); font-size: 18px; font-weight: 800; color: var(--forest); }

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) { .detail-grid { grid-template-columns: 1fr; } }

.left-column, .right-column { display: flex; flex-direction: column; gap: 20px; }

.content-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-card);
}

.card-title {
    font-size: 15px;
    font-weight: 800;
    color: var(--forest);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 10px;
}

.complaint-text { font-size: 14px; color: var(--ink); line-height: 1.5; }

.allergies-list { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
.allergy-badge { font-size: 12px; font-weight: 700; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 4px 12px; border-radius: 999px; }

.action-cards-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 600px) { .action-cards-grid { grid-template-columns: 1fr; } }

.action-card-btn {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    text-decoration: none;
    transition: all 150ms ease;
    box-shadow: var(--shadow-sm);
}
.action-card-btn:hover { border-color: var(--forest); transform: translateY(-2px); box-shadow: var(--shadow-lift); }

.action-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bg-forest-soft { background: #E2E8F0; color: var(--forest); }
.bg-lime-soft { background: var(--lime-soft); color: var(--lime-text); }

.action-card-btn b { display: block; font-size: 13.5px; font-weight: 700; color: var(--forest); }
.action-card-btn span { display: block; font-size: 11.5px; color: var(--ink-muted); }

.status-controls { display: flex; flex-direction: column; gap: 8px; }

.form-select {
    height: 42px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 13.5px;
    color: var(--ink);
    width: 100%;
}

.info-list { display: flex; flex-direction: column; gap: 12px; }
.info-item { display: flex; justify-content: space-between; font-size: 13px; border-bottom: 1px dashed var(--line); padding-bottom: 8px; }
.info-item:last-child { border-bottom: none; }
.info-item label { color: var(--ink-muted); }
.info-item span { font-weight: 600; color: var(--ink); }
.mono-text { font-family: var(--font-mono); color: var(--forest); }

.status-pill-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-pill-badge.in-progress { background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; }

.btn { display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
