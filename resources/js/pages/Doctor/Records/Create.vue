<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    appointment?: {
        id: string
        db_id: number
        date: string
    }
    patient?: {
        id: string
        name: string
        initials: string
        gender: string
        age: number
        bloodGroup: string
    }
}>()

const appInfo = computed(() => props.appointment ?? {
    id: '101',
    db_id: 101,
    date: 'Today',
})

const patientInfo = computed(() => props.patient ?? {
    id: 'MDF-9021',
    name: 'Habib Hossain',
    initials: 'HH',
    gender: 'Male',
    age: 28,
    bloodGroup: 'O+',
})

const showToast = ref(false)

const form = useForm({
    symptoms: 'Exertional chest tightness, minor dyspnea after workout routine.',
    primaryDiagnosis: 'Essential (Primary) Hypertension - Controlled',
    icdCode: 'I10',
    bpSystolic: 120,
    bpDiastolic: 80,
    heartRate: 72,
    weight: 74.5,
    soapSubjective: 'Patient presents for cardiology follow-up.',
    soapObjective: 'Resting 12-lead ECG shows normal sinus rhythm. Lungs clear.',
    soapPlan: 'Maintain current Amlodipine 5mg regimen.',
    attachment: null as File | null,
})

function handleSaveRecord() {
    form.post(`/doctor/appointments/${appInfo.value.id}/records`, {
        preserveScroll: true,
        onSuccess: () => {
            showToast.value = true
        },
    })
}
</script>

<template>
    <Head title="Create Medical Record - MediFlow" />

    <!-- TOP HEADER -->
    <div class="top-nav-row">
        <Link :href="`/doctor/appointments/${appInfo.id}`" class="back-btn">
            ← Cancel & Return to Appointment #{{ appInfo.id }}
        </Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="record-header-card">
        <div>
            <span class="ref-badge">New Record for Visit #{{ appInfo.id }}</span>
            <h1>Create Patient Clinical Medical Record</h1>
        </div>
    </div>

    <!-- PATIENT MINI SUMMARY -->
    <div class="patient-summary-box">
        <div class="patient-meta-group">
            <div class="patient-avatar-md">{{ patientInfo.initials }}</div>
            <div class="patient-info">
                <b>{{ patientInfo.name }}</b>
                <span>Patient ID: #{{ patientInfo.id }} · {{ patientInfo.gender }}, {{ patientInfo.age }} Yrs · Blood Type: {{ patientInfo.bloodGroup }}</span>
            </div>
        </div>

        <div class="date-badge">
            Date: <strong>{{ appInfo.date }}</strong>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            Clinical Findings & SOAP Assessment Form
        </div>

        <form @submit.prevent="handleSaveRecord">
            <div class="form-grid">
                <!-- CHIEF SYMPTOMS -->
                <div class="form-group full-width">
                    <label>Chief Complaints / Symptoms <span>*</span></label>
                    <textarea v-model="form.symptoms" class="form-control" required placeholder="Describe primary symptoms reported by patient..."></textarea>
                </div>

                <!-- DIAGNOSIS & ICD-10 CODE -->
                <div class="form-group">
                    <label>Primary Diagnosis <span>*</span></label>
                    <input v-model="form.primaryDiagnosis" type="text" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>ICD-10 Classification Code <span>*</span></label>
                    <select v-model="form.icdCode" class="form-control" required>
                        <option value="I10">I10 — Essential (Primary) Hypertension</option>
                        <option value="R07.89">R07.89 — Other Chest Pain / Tightness</option>
                        <option value="I20.9">I20.9 — Angina Pectoris, Unspecified</option>
                        <option value="R00.0">R00.0 — Tachycardia, Unspecified</option>
                    </select>
                </div>

                <!-- MEASURED VITALS INPUTS -->
                <div class="subhead">Patient Vitals Snapshot</div>

                <div class="vitals-inputs-grid">
                    <div class="form-group">
                        <label>BP Systolic <span>*</span></label>
                        <input v-model="form.bpSystolic" type="number" class="form-control" required placeholder="120" />
                    </div>

                    <div class="form-group">
                        <label>BP Diastolic <span>*</span></label>
                        <input v-model="form.bpDiastolic" type="number" class="form-control" required placeholder="80" />
                    </div>

                    <div class="form-group">
                        <label>Heart Rate (BPM)</label>
                        <input v-model="form.heartRate" type="number" class="form-control" placeholder="72" />
                    </div>

                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input v-model="form.weight" type="number" step="0.1" class="form-control" placeholder="74.5" />
                    </div>
                </div>

                <!-- CLINICAL SOAP NOTES -->
                <div class="subhead">Detailed Physician SOAP Notes</div>

                <div class="form-group full-width">
                    <label>Subjective (Patient Narrative)</label>
                    <textarea v-model="form.soapSubjective" class="form-control" placeholder="Patient history & subjective reports..."></textarea>
                </div>

                <div class="form-group full-width">
                    <label>Objective (Physical Examination & ECG)</label>
                    <textarea v-model="form.soapObjective" class="form-control" placeholder="Physical examination findings..."></textarea>
                </div>

                <div class="form-group full-width">
                    <label>Treatment Plan & Follow-Up Instructions</label>
                    <textarea v-model="form.soapPlan" class="form-control" placeholder="Treatment, medication changes..."></textarea>
                </div>

                <!-- ATTACHMENT FILE UPLOAD -->
                <div class="form-group full-width">
                    <label>Attach Diagnostic File (ECG, Lab Scan PDF)</label>
                    <input type="file" class="form-control" style="padding: 8px 16px;" />
                </div>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/doctor/appointments/101" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-primary">Save & Finalize Medical Record</button>
            </div>
        </form>
    </div>

    <!-- TOAST NOTICE -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Medical Record saved successfully!
    </div>
</template>

<style scoped>
.top-nav-row { margin-bottom: 20px; }
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
}
.back-btn:hover { background: var(--card); border-color: var(--forest); }

.record-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px 32px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}
.ref-badge { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.record-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; }

.patient-summary-box {
    background: var(--cream);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}
.patient-meta-group { display: flex; align-items: center; gap: 14px; }
.patient-avatar-md { width: 46px; height: 46px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-weight: 800; font-size: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.patient-info b { font-size: 15px; font-weight: 800; color: var(--forest); display: block; }
.patient-info span { font-size: 12.5px; color: var(--ink-muted); display: block; }
.date-badge { font-size: 12.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 36px;
    box-shadow: var(--shadow-card);
}
@media (max-width: 600px) { .form-card { padding: 20px; } }

.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }
@media (max-width: 680px) { .form-grid { grid-template-columns: 1fr; } }
.full-width { grid-column: 1 / -1; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); outline: none; }
textarea.form-control { height: auto; min-height: 100px; padding: 12px 16px; resize: vertical; }

.subhead { font-size: 13.5px; font-weight: 800; color: var(--forest); text-transform: uppercase; letter-spacing: 0.04em; grid-column: 1 / -1; border-top: 1px dashed var(--line); padding-top: 18px; margin-top: 6px; }

.vitals-inputs-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; grid-column: 1 / -1; }
@media (max-width: 768px) { .vitals-inputs-grid { grid-template-columns: repeat(2, 1fr); } }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 150ms ease; }
.btn-primary { background: var(--forest); color: #fff; border: 1.5px solid var(--forest); box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
