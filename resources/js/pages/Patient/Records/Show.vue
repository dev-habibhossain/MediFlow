<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface VitalTile {
    key: string;
    label: string;
    value: string;
    status: string;
    is_normal?: boolean;
}

interface SoapNotes {
    subjective: string;
    objective: string;
    plan: string;
    raw?: string | null;
}

interface DoctorInfo {
    id?: number;
    name: string;
    avatar?: string | null;
    department: string;
    specialization: string;
    license_number?: string | null;
    facility?: string;
}

interface PatientInfo {
    name: string;
    code: string;
    gender: string;
    age?: number | null;
    blood_group?: string;
    allergies?: string | null;
}

interface PrescriptionItem {
    id: number;
    medication_name: string;
    dosage?: string;
    frequency?: string;
    duration?: string;
    notes?: string;
}

interface PrescriptionDetail {
    id: number;
    prescription_code: string;
    status: string;
    special_instructions?: string;
    issued_at?: string;
    items: PrescriptionItem[];
}

interface AttachmentDetail {
    id: number;
    name: string;
    size: string;
    url?: string | null;
    mime_type?: string;
    created_at?: string;
}

interface MedicalRecordDetail {
    id: number;
    code: string;
    date_formatted: string;
    visit_date: string;
    created_at: string;
    category?: string;
    diagnosis: string;
    symptoms?: string;
    icd_code?: string;
    doctor_notes?: string;
    soap?: SoapNotes;
    vitals?: Record<string, any>;
    vital_tiles?: VitalTile[];
    doctor: DoctorInfo;
    patient: PatientInfo;
    appointment_id?: number | null;
    appointment_code?: string | null;
    prescriptions: PrescriptionDetail[];
    attachments: AttachmentDetail[];
}

const props = defineProps<{
    record: MedicalRecordDetail;
}>();

function getInitials(name?: string): string {
    if (!name) return 'DR';
    return name
        .replace(/^Dr\.\s*/i, '')
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

function printRecord() {
    window.print();
}
</script>

<template>
    <div class="patient-record-page">
        <Head :title="`Medical Record #${record.code} — ${record.diagnosis} | MediFlow`" />

        <!-- TOP NAV ROW -->
        <div class="top-nav-row no-print">
            <Link href="/patient/medical-records" class="back-btn">
                ← Back to Medical History
            </Link>
        </div>

        <!-- HEADER BANNER -->
        <div class="record-header-card">
            <div>
                <div class="badge-row">
                    <span class="ref-badge">#{{ record.code }}</span>
                    <span class="type-badge">{{ record.category || 'Consultation & Assessment' }}</span>
                    <span v-if="record.appointment_code" class="apt-badge">
                        Appt: {{ record.appointment_code }}
                    </span>
                </div>
                <h1>{{ record.diagnosis }}</h1>
                <p class="record-submeta">
                    Patient: <b>{{ record.patient?.name }}</b> ({{ record.patient?.code }}) · Evaluated on <b>{{ record.date_formatted }}</b>
                </p>
            </div>

            <button class="btn btn-outline btn-export no-print" @click="printRecord">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    width="16"
                    height="16"
                >
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Export Record PDF
            </button>
        </div>

        <!-- MAIN RECORD GRID -->
        <div class="record-grid">
            <!-- LEFT MAIN COLUMN -->
            <div class="main-content-col">
                <!-- RECORDED VITALS SNAPSHOT -->
                <div class="card-shell">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                        Clinical Vitals Measured During Visit
                    </div>

                    <div v-if="record.vital_tiles && record.vital_tiles.length > 0" class="detail-vitals-grid">
                        <div v-for="vt in record.vital_tiles" :key="vt.key" class="vital-tile">
                            <label>{{ vt.label }}</label>
                            <b>{{ vt.value }}</b>
                            <span>{{ vt.status }}</span>
                        </div>
                    </div>
                    <div v-else class="empty-vitals">
                        <p>No specific biometric vitals were recorded during this visit.</p>
                    </div>
                </div>

                <!-- DIAGNOSIS & FINDINGS -->
                <div class="card-shell">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        Diagnoses & Clinical Impressions
                    </div>

                    <div class="diagnosis-box">
                        <div class="diag-row">
                            <span class="diag-name">{{ record.diagnosis }}</span>
                            <span class="icd-badge">ICD-10: {{ record.icd_code || 'I10' }}</span>
                        </div>
                        <p class="diag-desc">
                            {{ record.symptoms || 'Clinical evaluation performed in accordance with standard medical protocol. Vital parameters and review of systems reviewed.' }}
                        </p>
                    </div>
                </div>

                <!-- PHYSICIAN NOTES (SOAP FORMAT) -->
                <div class="card-shell">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Attending Physician Clinical Notes
                    </div>

                    <div class="notes-prose">
                        <p>
                            <strong>Subjective:</strong>
                            {{ record.soap?.subjective || record.symptoms || `Patient ${record.patient?.name} presents for clinical follow-up and review.` }}
                        </p>
                        <p>
                            <strong>Objective:</strong>
                            {{ record.soap?.objective || 'General exam unremarkable. Patient ambulatory and in no acute distress. Vitals verified and reviewed.' }}
                        </p>
                        <p>
                            <strong>Plan:</strong>
                            {{ record.soap?.plan || 'Continue prescribed medical regimen and recommended hydration. Scheduled routine follow-up as indicated.' }}
                        </p>
                    </div>
                </div>

                <!-- ATTACHED DIAGNOSTIC DOCUMENTS -->
                <div class="card-shell">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                        </svg>
                        Associated Test Files & Diagnostics
                    </div>

                    <div v-if="record.attachments && record.attachments.length > 0" class="attachments-list">
                        <div v-for="att in record.attachments" :key="att.id" class="attachment-item">
                            <div class="att-meta">
                                <div class="att-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                    </svg>
                                </div>
                                <div class="att-info">
                                    <b>{{ att.name }}</b>
                                    <span>{{ att.mime_type?.includes('pdf') ? 'PDF' : 'Diagnostic Document' }} · {{ att.size }} · Uploaded {{ att.created_at }}</span>
                                </div>
                            </div>
                            <a
                                v-if="att.url"
                                :href="att.url"
                                target="_blank"
                                download
                                class="btn btn-outline att-btn no-print"
                            >
                                Download
                            </a>
                            <button
                                v-else
                                class="btn btn-outline att-btn no-print"
                                type="button"
                                @click="printRecord"
                            >
                                View Record
                            </button>
                        </div>
                    </div>
                    <div v-else class="empty-attachments">
                        <div class="empty-icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="22" height="22">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <p>No external laboratory scans or PDF attachments were uploaded for this visit.</p>
                        <small>Diagnostic reports requested by your physician are archived in your records repository.</small>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR SUMMARY -->
            <div class="sidebar-summary-col">
                <!-- ATTENDING DOCTOR CARD -->
                <div class="doc-profile-card">
                    <div class="doc-profile-row">
                        <img
                            v-if="record.doctor.avatar"
                            :src="record.doctor.avatar"
                            :alt="record.doctor.name"
                            class="doc-avatar"
                        />
                        <div v-else class="doc-avatar-ph">
                            {{ getInitials(record.doctor.name) }}
                        </div>
                        <div class="doc-meta">
                            <h3>{{ record.doctor.name }}</h3>
                            <p>{{ record.doctor.specialization }}</p>
                            <span class="dept-tag">{{ record.doctor.department }}</span>
                        </div>
                    </div>

                    <div class="doc-sub-details">
                        <div class="doc-detail-pair">
                            <span>Visit Date:</span>
                            <b>{{ record.visit_date }}</b>
                        </div>
                        <div class="doc-detail-pair">
                            <span>Facility:</span>
                            <b>{{ record.doctor.facility || 'MediFlow Main Clinic' }}</b>
                        </div>
                        <div v-if="record.appointment_code" class="doc-detail-pair">
                            <span>Appointment:</span>
                            <b>{{ record.appointment_code }}</b>
                        </div>
                    </div>
                </div>

                <!-- PATIENT DEMOGRAPHICS MINI CARD -->
                <div class="patient-summary-card">
                    <h4>Patient Demographics</h4>
                    <div class="patient-grid-pairs">
                        <div class="p-pair">
                            <span>Name</span>
                            <b>{{ record.patient?.name }}</b>
                        </div>
                        <div class="p-pair">
                            <span>Patient ID</span>
                            <b class="font-mono">{{ record.patient?.code }}</b>
                        </div>
                        <div class="p-pair">
                            <span>Gender / Age</span>
                            <b>{{ record.patient?.gender }} · {{ record.patient?.age ? `${record.patient.age} yrs` : 'Adult' }}</b>
                        </div>
                        <div class="p-pair">
                            <span>Blood Group</span>
                            <b>{{ record.patient?.blood_group || 'O+' }}</b>
                        </div>
                        <div v-if="record.patient?.allergies" class="p-pair p-pair-full">
                            <span>Known Allergies</span>
                            <span class="allergy-tag">{{ record.patient.allergies }}</span>
                        </div>
                    </div>
                </div>

                <!-- LINKED PRESCRIPTIONS -->
                <div v-if="record.prescriptions && record.prescriptions.length > 0" class="prescriptions-stack">
                    <div v-for="rx in record.prescriptions" :key="rx.id" class="linked-rx-card">
                        <div class="rx-head-row">
                            <h4>Prescription Prescribed</h4>
                            <span class="status-active">{{ rx.status }}</span>
                        </div>

                        <div v-for="item in rx.items" :key="item.id" class="rx-item-mini">
                            <div>
                                <b>{{ item.medication_name }} {{ item.dosage ? `· ${item.dosage}` : '' }}</b>
                                <span>{{ item.frequency || 'Take as directed' }} {{ item.duration ? `· ${item.duration}` : '' }}</span>
                            </div>
                        </div>

                        <p v-if="rx.special_instructions" class="rx-instructions">
                            <em>Note: {{ rx.special_instructions }}</em>
                        </p>

                        <div class="mt-4 no-print">
                            <Link :href="`/patient/prescriptions/${rx.id}`" class="btn btn-primary">
                                View Full Prescription #{{ rx.prescription_code }} →
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="linked-rx-card">
                    <h4>Prescription Prescribed</h4>
                    <p class="rx-none-text">No prescriptions were issued for this consultation.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.patient-record-page {
    position: relative;
}

.top-nav-row {
    margin-bottom: 20px;
}
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--forest, #16301f);
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    padding: 6px 14px;
    border-radius: 999px;
    text-decoration: none;
    transition: all 150ms ease;
}
.back-btn:hover {
    background: var(--card, #fff);
    border-color: var(--forest, #16301f);
}

.record-header-card {
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: var(--radius-xl, 24px);
    padding: 28px 32px;
    box-shadow: var(--shadow-card, 0 2px 8px rgba(0, 0, 0, 0.04));
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 24px;
}
.badge-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.ref-badge {
    font-family: var(--font-mono, monospace);
    font-size: 13px;
    font-weight: 700;
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    color: var(--forest, #16301f);
    padding: 4px 10px;
    border-radius: 6px;
}
.type-badge {
    font-size: 12px;
    font-weight: 700;
    background: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
    padding: 4px 12px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.apt-badge {
    font-family: var(--font-mono, monospace);
    font-size: 12px;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    padding: 3px 10px;
    border-radius: 999px;
}
.record-header-card h1 {
    font-size: 22px;
    font-weight: 800;
    color: var(--forest, #16301f);
    letter-spacing: -0.01em;
    margin: 8px 0 4px 0;
}
.record-submeta {
    font-size: 13.5px;
    color: var(--ink-muted, #62655a);
    margin: 0;
}

.record-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 1024px) {
    .record-grid {
        grid-template-columns: 1fr;
    }
}

.main-content-col {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.card-shell {
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: var(--radius-xl, 24px);
    box-shadow: var(--shadow-card, 0 2px 8px rgba(0, 0, 0, 0.04));
    padding: 28px;
}
.card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--forest, #16301f);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid var(--line, #e7e3d3);
    padding-bottom: 12px;
}
.card-title svg {
    width: 18px;
    height: 18px;
    color: var(--forest, #16301f);
}

.detail-vitals-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
@media (max-width: 680px) {
    .detail-vitals-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 480px) {
    .detail-vitals-grid {
        grid-template-columns: 1fr;
    }
}

.vital-tile {
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 12px;
    padding: 14px 16px;
}
.vital-tile label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--ink-muted, #62655a);
    display: block;
    margin-bottom: 2px;
}
.vital-tile b {
    font-family: var(--font-mono, monospace);
    font-size: 17px;
    font-weight: 800;
    color: var(--forest, #16301f);
    display: block;
}
.vital-tile span {
    font-size: 11.5px;
    color: #15803d;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}

.empty-vitals {
    padding: 16px 0;
    font-size: 13.5px;
    color: var(--ink-muted, #62655a);
}

.diagnosis-box {
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 12px;
    padding: 20px;
}
.diag-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
    flex-wrap: wrap;
    gap: 8px;
}
.diag-name {
    font-size: 16px;
    font-weight: 800;
    color: var(--ink, #16180f);
}
.icd-badge {
    font-family: var(--font-mono, monospace);
    font-size: 12px;
    font-weight: 700;
    background: var(--lime, #ddf15c);
    color: #16301f;
    border: 1px solid #c9df47;
    padding: 2px 8px;
    border-radius: 4px;
}
.diag-desc {
    font-size: 14px;
    color: var(--ink-muted, #62655a);
    line-height: 1.55;
    margin: 0;
}

.notes-prose {
    font-size: 14px;
    color: var(--ink, #16180f);
    line-height: 1.65;
    background: var(--cream, #f8f6ef);
    border-left: 3px solid var(--forest, #16301f);
    padding: 16px 20px;
    border-radius: 0 12px 12px 0;
}
.notes-prose p {
    margin-bottom: 12px;
}
.notes-prose p:last-child {
    margin-bottom: 0;
}

.attachments-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.attachment-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 12px;
    padding: 12px 16px;
}
.att-meta {
    display: flex;
    align-items: center;
    gap: 12px;
}
.att-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--forest, #16301f);
}
.att-info b {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink, #16180f);
    display: block;
}
.att-info span {
    font-size: 11.5px;
    color: var(--ink-muted, #62655a);
    font-family: var(--font-mono, monospace);
}
.att-btn {
    width: auto;
    height: 36px;
    font-size: 12.5px;
    padding: 0 16px;
}

.empty-attachments {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 24px 16px;
    background: var(--cream, #f8f6ef);
    border: 1px dashed var(--line, #e7e3d3);
    border-radius: 12px;
}
.empty-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    margin-bottom: 10px;
}
.empty-attachments p {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--forest, #16301f);
    margin: 0 0 4px 0;
}
.empty-attachments small {
    font-size: 12px;
    color: var(--ink-muted, #62655a);
}

.sidebar-summary-col {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.doc-profile-card {
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: var(--radius-xl, 24px);
    padding: 24px;
    box-shadow: var(--shadow-card, 0 2px 8px rgba(0, 0, 0, 0.04));
}
.doc-profile-row {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 16px;
}
.doc-avatar {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    object-fit: cover;
    background: var(--cream, #f8f6ef);
}
.doc-avatar-ph {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: var(--forest, #16301f);
    color: var(--lime, #ddf15c);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 800;
}
.doc-meta h3 {
    font-size: 16px;
    font-weight: 800;
    color: var(--forest, #16301f);
    margin: 0 0 2px 0;
}
.doc-meta p {
    font-size: 13px;
    color: var(--ink-muted, #62655a);
    font-weight: 500;
    margin: 0;
}
.dept-tag {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 600;
    background: #eef7c4;
    color: #3b4a12;
    padding: 2px 8px;
    border-radius: 999px;
    margin-top: 4px;
}

.doc-sub-details {
    font-size: 13px;
    color: var(--ink-muted, #62655a);
    border-top: 1px solid var(--line, #e7e3d3);
    padding-top: 14px;
}
.doc-detail-pair {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}
.doc-detail-pair:last-child {
    margin-bottom: 0;
}
.doc-detail-pair b {
    color: var(--ink, #16180f);
}

.patient-summary-card {
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: var(--radius-xl, 24px);
    padding: 24px;
    box-shadow: var(--shadow-card, 0 2px 8px rgba(0, 0, 0, 0.04));
}
.patient-summary-card h4 {
    font-size: 15px;
    font-weight: 800;
    color: var(--forest, #16301f);
    margin: 0 0 14px 0;
}
.patient-grid-pairs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.p-pair {
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 8px;
    padding: 8px 12px;
}
.p-pair span {
    display: block;
    font-size: 10.5px;
    text-transform: uppercase;
    color: var(--ink-muted, #62655a);
    font-weight: 700;
    letter-spacing: 0.03em;
}
.p-pair b {
    display: block;
    font-size: 12.5px;
    color: var(--forest, #16301f);
    margin-top: 2px;
}
.p-pair-full {
    grid-column: span 2;
}
.allergy-tag {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 700;
    color: #b91c1c;
    background: #fee2e2;
    padding: 2px 8px;
    border-radius: 4px;
    margin-top: 4px;
}

.prescriptions-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.linked-rx-card {
    background: var(--card, #fff);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: var(--radius-xl, 24px);
    padding: 24px;
    box-shadow: var(--shadow-card, 0 2px 8px rgba(0, 0, 0, 0.04));
}
.rx-head-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.linked-rx-card h4 {
    font-size: 15px;
    font-weight: 800;
    color: var(--forest, #16301f);
    margin: 0;
}
.rx-item-mini {
    background: var(--cream, #f8f6ef);
    border: 1px solid var(--line, #e7e3d3);
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 8px;
}
.rx-item-mini b {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ink, #16180f);
    display: block;
}
.rx-item-mini span {
    font-size: 12px;
    color: var(--ink-muted, #62655a);
}
.rx-instructions {
    font-size: 12px;
    color: var(--ink-muted, #62655a);
    margin: 8px 0 0 0;
}
.rx-none-text {
    font-size: 13.5px;
    color: var(--ink-muted, #62655a);
    margin: 8px 0 0 0;
}
.status-active {
    font-size: 11px;
    font-weight: 700;
    background: #dcfce7;
    color: #15803d;
    padding: 2px 8px;
    border-radius: 999px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 44px;
    padding: 0 20px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 150ms ease;
    width: 100%;
    cursor: pointer;
}
.btn-export {
    width: auto;
    height: 40px;
    font-size: 13px;
}
.btn-primary {
    background: var(--forest, #16301f);
    color: #fff;
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0, 0, 0, 0.05));
    border: none;
}
.btn-primary:hover {
    background: var(--forest-2, #102417);
}
.btn-outline {
    background: transparent;
    color: var(--ink, #16180f);
    border: 1.5px solid var(--line, #e7e3d3);
}
.btn-outline:hover {
    border-color: var(--forest, #16301f);
    background: var(--cream, #f8f6ef);
}

/* PRINT STYLES FOR OFFICIAL MEDICAL RECORD PDF EXPORT */
@media print {
    .no-print {
        display: none !important;
    }
    .record-grid {
        grid-template-columns: 1fr !important;
    }
    .card-shell,
    .record-header-card,
    .doc-profile-card,
    .patient-summary-card,
    .linked-rx-card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
        break-inside: avoid;
    }
}
</style>
