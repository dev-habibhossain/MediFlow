<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = ref({
    chiefComplaint: 'Persistent mild morning headache for past 2 weeks.',
    icdCode: 'I10',
    bp: '135/88',
    hr: '72',
    temp: '98.6',
    spo2: '98',
    weight: '74',
    subjective: 'Patient reports mild occipital headache in the mornings. No visual disturbances or dizziness.',
    objective: 'BP 135/88 mmHg, HR 72 bpm regular rhythm. Lungs clear to auscultation bilaterally. No peripheral edema.',
    assessment: 'Stage 1 Essential Hypertension (ICD-10 I10). Well-compensated with no acute target organ damage.',
    plan: '1. Initiate Lisinopril 10mg daily PO.\n2. Advise low-sodium diet and daily BP log.\n3. Return visit in 3 weeks.',
})

function handleSubmit() {
    alert('Medical Record created and cryptographically signed!')
}
</script>

<template>
    <Head title="Create Medical Record" />

    <!-- NAV BAR ROW -->
    <div class="nav-bar-row">
        <Link href="/doctor/appointments/MDF-9021" class="back-link">
            ← Back to Appointment #MDF-9021
        </Link>
        <span class="context-tag">Creating Record for Patient: Habib Hossain (#MDF-9021)</span>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="form-card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                New Clinical SOAP Note & Medical Record Entry
            </h3>
        </div>

        <form @submit.prevent="handleSubmit">
            <!-- DIAGNOSIS & COMPLAINT BLOCK -->
            <div class="form-section">
                <h4 class="section-title">Primary Diagnosis & Complaint</h4>

                <div class="form-grid-dual">
                    <div class="form-group">
                        <label>Chief Complaint <span>*</span></label>
                        <input v-model="form.chiefComplaint" type="text" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label>Primary Diagnosis (ICD-10 Code) <span>*</span></label>
                        <select v-model="form.icdCode" class="form-control" required>
                            <option value="I10">ICD-10 I10 — Essential (primary) hypertension</option>
                            <option value="I25.10">ICD-10 I25.10 — Atherosclerotic heart disease</option>
                            <option value="E11.9">ICD-10 E11.9 — Type 2 diabetes mellitus without complications</option>
                            <option value="R07.9">ICD-10 R07.9 — Chest pain, unspecified</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- VITALS SNAPSHOT BLOCK -->
            <div class="form-section">
                <h4 class="section-title">Physical Vitals Snapshot</h4>

                <div class="vitals-grid">
                    <div class="form-group">
                        <label>Blood Pressure (mmHg)</label>
                        <input v-model="form.bp" type="text" class="form-control mono" placeholder="120/80" />
                    </div>

                    <div class="form-group">
                        <label>Heart Rate (bpm)</label>
                        <input v-model="form.hr" type="text" class="form-control mono" placeholder="72" />
                    </div>

                    <div class="form-group">
                        <label>Body Temp (°F)</label>
                        <input v-model="form.temp" type="text" class="form-control mono" placeholder="98.6" />
                    </div>

                    <div class="form-group">
                        <label>SpO2 (%)</label>
                        <input v-model="form.spo2" type="text" class="form-control mono" placeholder="98" />
                    </div>

                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input v-model="form.weight" type="text" class="form-control mono" placeholder="74" />
                    </div>
                </div>
            </div>

            <!-- SOAP NOTES BLOCK -->
            <div class="form-section">
                <h4 class="section-title">SOAP Clinical Evaluation</h4>

                <div class="soap-grid">
                    <div class="form-group">
                        <label>Subjective (S) — Patient History & Symptoms <span>*</span></label>
                        <textarea v-model="form.subjective" class="form-control textarea" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Objective (O) — Exam & Clinical Findings <span>*</span></label>
                        <textarea v-model="form.objective" class="form-control textarea" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Assessment (A) — Clinical Impression <span>*</span></label>
                        <textarea v-model="form.assessment" class="form-control textarea" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Plan (P) — Treatment & Follow-up <span>*</span></label>
                        <textarea v-model="form.plan" class="form-control textarea" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <Link href="/doctor/appointments/MDF-9021" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-primary">Save & Sign Medical Record</button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.nav-bar-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.back-link { font-size: 13.5px; font-weight: 700; color: var(--forest); text-decoration: none; }
.context-tag { font-size: 12.5px; font-weight: 600; color: var(--ink-muted); }

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow-card);
}

.card-header {
    border-bottom: 1px solid var(--line);
    padding-bottom: 16px;
    margin-bottom: 24px;
}
.card-header h3 { font-size: 18px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.form-section { margin-bottom: 28px; }
.section-title {
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--forest);
    margin-bottom: 16px;
    border-left: 3px solid var(--lime);
    padding-left: 10px;
}

.form-grid-dual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 768px) { .form-grid-dual { grid-template-columns: 1fr; } }

.vitals-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
}
@media (max-width: 900px) { .vitals-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 500px) { .vitals-grid { grid-template-columns: 1fr 1fr; } }

.soap-grid { display: flex; flex-direction: column; gap: 16px; }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }
.form-group label span { color: #DC2626; }

.form-control {
    height: 44px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 14px;
    color: var(--ink);
}
.form-control.mono { font-family: var(--font-mono); }
.form-control.textarea { height: auto; padding: 12px 14px; resize: vertical; }

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid var(--line);
    padding-top: 20px;
}

.btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
