<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    appointment?: {
        id: string
        db_id: number
        patientId: string
        patientDbId?: number
        patientName: string
        patientInitials: string
        age: number
        gender: string
        bloodGroup: string
        allergies: string
        visitsCompleted: number
        date: string
        time: string
        mode: string
        location: string
        phone: string
        email: string
        paymentStatus: string
        receipt: string
        reason: string
        status: string
        vitals: {
            bp: string
            hr: string
            weight: string
        }
        prescriptions?: Array<{
            id: number
            code: string
            status: string
            issuedAt: string
            notes?: string
            items: Array<{
                id: number
                name: string
                dosage: string
                frequency: string
                duration: string
                refills: number
                instructions?: string
            }>
        }>
        medicalRecord?: {
            id: number
            symptoms: string
            diagnosis: string
            icdCode: string
            notes?: string
            treatmentPlan?: string
            createdAt: string
        }
    }
}>()

const appData = computed(() => props.appointment ?? {
    id: '101',
    db_id: 101,
    patientId: 'MDF-9021',
    patientName: 'Habib Hossain',
    patientInitials: 'HH',
    age: 28,
    gender: 'Male',
    bloodGroup: 'O+',
    allergies: 'Penicillin (Mild)',
    visitsCompleted: 14,
    date: 'Friday, Aug 7, 2026',
    time: '10:00 AM – 10:30 AM EST',
    mode: 'In-Person Visit',
    location: 'Room 302, Harbor Ave Clinic',
    phone: '(555) 340-2199',
    email: 'habib@example.com',
    paymentStatus: 'Paid ($120.00)',
    receipt: 'Receipt #INV-88402',
    reason: 'Routine follow-up consultation regarding recent blood pressure fluctuations.',
    status: 'confirmed',
    vitals: {
        bp: '120/80',
        hr: '72',
        weight: '74.5',
    },
    prescriptions: [],
    medicalRecord: null,
})

const currentStatus = ref(appData.value.status)
const toastMsg = ref('')
const showToast = ref(false)

// Edit Prescription Modal State
const isEditModalOpen = ref(false)
const activeEditRxId = ref<number | null>(null)
const activeEditRxCode = ref<string>('')

const editForm = useForm({
    items: [
        {
            name: '',
            frequency: '1x Daily (Morning)',
            duration: '30 Days',
            refills: '1',
            instructions: '',
        },
    ],
    pharmacyNotes: '',
})

function openEditModal(rx: any) {
    activeEditRxId.value = rx.id
    activeEditRxCode.value = rx.code
    editForm.pharmacyNotes = rx.notes ?? ''
    editForm.items = rx.items.map((item: any) => ({
        name: item.name,
        frequency: item.frequency,
        duration: item.duration,
        refills: String(item.refills),
        instructions: item.instructions ?? '',
    }))
    isEditModalOpen.value = true
}

function closeEditModal() {
    isEditModalOpen.value = false
    activeEditRxId.value = null
}

function addEditRow() {
    editForm.items.push({
        name: '',
        frequency: '1x Daily (Morning)',
        duration: '30 Days',
        refills: '1',
        instructions: '',
    })
}

function removeEditRow(index: number) {
    if (editForm.items.length > 1) {
        editForm.items.splice(index, 1)
    } else {
        alert('Prescription must contain at least one medication line item.')
    }
}

function handleUpdateRx() {
    if (!activeEditRxId.value) return
    editForm.put(`/doctor/prescriptions/${activeEditRxId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal()
            toastMsg.value = `Prescription #${activeEditRxCode.value} updated successfully!`
            showToast.value = true
            setTimeout(() => { showToast.value = false }, 3000)
        },
    })
}

function deletePrescription(id: number, code: string) {
    if (confirm(`Are you sure you want to delete Prescription #${code}?`)) {
        router.delete(`/doctor/prescriptions/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toastMsg.value = `Prescription #${code} deleted successfully.`
                showToast.value = true
                setTimeout(() => { showToast.value = false }, 3000)
            },
        })
    }
}

function handleStatusChange(event: Event) {
    const val = (event.target as HTMLSelectElement).value
    currentStatus.value = val
    router.patch(
        `/doctor/appointments/${appData.value.id}/status`,
        { status: val },
        {
            preserveScroll: true,
            onSuccess: () => {
                toastMsg.value = `Status updated to ${val.replace('_', ' ')}`
                showToast.value = true
                setTimeout(() => { showToast.value = false }, 3000)
            },
        }
    )
}

function printSummary() {
    window.print()
}
</script>

<template>
    <Head :title="`Appointment #${appData.id} Detail - MediFlow`" />

    <!-- TOP HEADER / BACK LINK -->
    <div class="top-nav-row">
        <Link href="/doctor/appointments" class="back-btn">
            ← Back to Appointments List
        </Link>
        <button class="back-btn" @click="printSummary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
            </svg>
            Print Summary
        </button>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="detail-header-card">
        <div>
            <div class="header-info-group">
                <span class="ref-badge">#{{ appData.id }}</span>
                <span
                    class="badge"
                    :class="{
                        'badge-confirmed': currentStatus === 'confirmed',
                        'badge-in-progress': currentStatus === 'in_progress',
                        'badge-completed': currentStatus === 'completed',
                        'badge-cancelled': currentStatus === 'no_show' || currentStatus === 'cancelled'
                    }"
                >
                    {{ currentStatus === 'confirmed' ? 'Confirmed Visit' : currentStatus.replace('_', ' ').toUpperCase() }}
                </span>
            </div>
            <h1>Cardiology Follow-Up Consultation</h1>
        </div>
    </div>

    <!-- MAIN SPLIT GRID -->
    <div class="detail-grid">
        <!-- LEFT COLUMN: PATIENT CLINICAL DATA -->
        <div class="main-details-col">
            <!-- PATIENT PROFILE CARD -->
            <div class="card-shell">
                <div class="card-title">
                    <div class="card-title-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Patient Information
                    </div>
                    <Link :href="`/doctor/patients/${appData.patientDbId || appData.patientId || '1'}/history`" class="history-link">
                        View Full History →
                    </Link>
                </div>

                <div class="patient-profile-strip">
                    <div class="patient-avatar-lg">{{ appData.patientInitials }}</div>
                    <div class="patient-meta-lg">
                        <h3>{{ appData.patientName }}</h3>
                        <p>Patient ID: #{{ appData.patientId }} · Age: {{ appData.age }} · Gender: {{ appData.gender }}</p>

                        <div class="patient-quick-stats">
                            <span class="stat-pill">Blood Group: <strong>{{ appData.bloodGroup }}</strong></span>
                            <span class="stat-pill">Allergies: <strong>{{ appData.allergies }}</strong></span>
                            <span class="stat-pill">Visits Completed: <strong>{{ appData.visitsCompleted }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCHEDULE & LOCATION SPECIFICATIONS -->
            <div class="card-shell">
                <div class="card-title">
                    <div class="card-title-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        Schedule & Location
                    </div>
                </div>

                <div class="info-pairs-grid">
                    <div class="info-box">
                        <label>Date & Time</label>
                        <span>{{ appData.date }}</span>
                        <small>{{ appData.time }}</small>
                    </div>

                    <div class="info-box">
                        <label>Consultation Mode</label>
                        <span>{{ appData.mode }}</span>
                        <small>{{ appData.location }}</small>
                    </div>

                    <div class="info-box">
                        <label>Patient Contact</label>
                        <span>{{ appData.phone }}</span>
                        <small>{{ appData.email }}</small>
                    </div>

                    <div class="info-box">
                        <label>Payment Status</label>
                        <span style="color: #15803D;">{{ appData.paymentStatus }}</span>
                        <small>{{ appData.receipt }}</small>
                    </div>
                </div>
            </div>

            <!-- REASON & VITALS SNAPSHOT -->
            <div class="card-shell">
                <div class="card-title">
                    <div class="card-title-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                        Chief Complaint & Pre-Visit Vitals
                    </div>
                </div>

                <div class="symptom-box">
                    <label>Patient Stated Reason for Visit</label>
                    <p>"{{ appData.reason }}"</p>
                </div>

                <div class="vitals-mini-grid">
                    <div class="vital-tile">
                        <label>Blood Pressure</label>
                        <b>{{ appData.vitals.bp }} <small style="font-size: 11px; font-weight: normal;">mmHg</small></b>
                    </div>
                    <div class="vital-tile">
                        <label>Heart Rate</label>
                        <b>{{ appData.vitals.hr }} <small style="font-size: 11px; font-weight: normal;">bpm</small></b>
                    </div>
                    <div class="vital-tile">
                        <label>Body Weight</label>
                        <b>{{ appData.vitals.weight }} <small style="font-size: 11px; font-weight: normal;">kg</small></b>
                    </div>
                </div>
            </div>

            <!-- ISSUED PRESCRIPTIONS CARD (Styled to match Prescription Page) -->
            <div v-if="appData.prescriptions && appData.prescriptions.length > 0" class="card-shell" style="margin-top: 20px;">
                <div class="card-title">
                    <div class="card-title-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                        </svg>
                        Issued Electronic Prescriptions ({{ appData.prescriptions.length }})
                    </div>
                </div>

                <div v-for="rx in appData.prescriptions" :key="rx.id" style="padding: 20px; border-bottom: 1px solid var(--line);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <b style="font-size: 15px; color: var(--forest);">Rx #{{ rx.code }}</b>
                            <span style="font-size: 12.5px; color: var(--ink-muted); margin-left: 8px;">Issued {{ rx.issuedAt }}</span>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; background: #DCFCE7; color: #15803D; padding: 2px 8px; border-radius: 12px; margin-left: 8px;">{{ rx.status }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" class="btn-edit-rx" @click="openEditModal(rx)">
                                ✎ Edit Rx
                            </button>
                            <button type="button" class="btn-delete-rx" @click="deletePrescription(rx.id, rx.code)">
                                🗑 Delete
                            </button>
                        </div>
                    </div>

                    <!-- MEDICATION TABLE -->
                    <div class="table-wrap" style="margin-bottom: 12px;">
                        <table class="med-table">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Medication Name & Dosage</th>
                                    <th style="width: 22%;">Frequency / Timing</th>
                                    <th style="width: 16%;">Duration</th>
                                    <th style="width: 12%;">Refills</th>
                                    <th style="width: 20%;">Special Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in rx.items" :key="item.id" class="med-row">
                                    <td style="font-weight: 700; color: var(--ink);">{{ item.name }}</td>
                                    <td>
                                        <span class="freq-badge">{{ item.frequency }}</span>
                                    </td>
                                    <td style="font-size: 13px;">{{ item.duration }}</td>
                                    <td style="font-size: 13px;">{{ item.refills }} Refills</td>
                                    <td style="font-size: 12.5px; color: var(--ink-muted);">{{ item.instructions || 'Standard dosing' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="rx.notes" style="font-size: 13px; color: var(--ink-muted); background: var(--cream); padding: 10px 14px; border-radius: var(--radius-sm);">
                        <strong>Pharmacy Notes:</strong> {{ rx.notes }}
                    </div>
                </div>
            </div>

            <!-- CLINICAL MEDICAL RECORD CARD -->
            <div v-if="appData.medicalRecord" class="card-shell" style="margin-top: 20px;">
                <div class="card-title">
                    <div class="card-title-text">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        </svg>
                        Clinical Medical Record
                    </div>
                    <Link :href="`/doctor/records/${appData.medicalRecord.id}/edit`" class="btn btn-outline" style="font-size: 12px; padding: 4px 10px;">
                        Edit Record →
                    </Link>
                </div>

                <div style="padding: 16px;">
                    <div style="margin-bottom: 12px;">
                        <b style="font-size: 14.5px;">Diagnosis: {{ appData.medicalRecord.diagnosis }}</b>
                        <span v-if="appData.medicalRecord.icdCode" style="font-size: 12px; color: var(--slate); margin-left: 8px;">(ICD-10: {{ appData.medicalRecord.icdCode }})</span>
                    </div>
                    <div v-if="appData.medicalRecord.symptoms" style="font-size: 13px; margin-bottom: 8px;">
                        <strong>Symptoms:</strong> {{ appData.medicalRecord.symptoms }}
                    </div>
                    <div v-if="appData.medicalRecord.treatmentPlan" style="font-size: 13px; margin-bottom: 8px;">
                        <strong>Treatment Plan:</strong> {{ appData.medicalRecord.treatmentPlan }}
                    </div>
                    <div v-if="appData.medicalRecord.notes" style="font-size: 13px; color: var(--slate); background: var(--cream); padding: 10px; border-radius: var(--radius-sm); margin-top: 8px;">
                        <strong>Doctor Notes:</strong> {{ appData.medicalRecord.notes }}
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR: STATUS UPDATE & CLINICAL WORKFLOW -->
        <div class="sidebar-col">
            <!-- UPDATE STATUS CARD -->
            <div class="action-card">
                <h4 class="card-heading">Appointment Status</h4>
                <p class="card-subtext">Update consultation stage for hospital flow:</p>

                <select class="status-select" :value="currentStatus" @change="handleStatusChange">
                    <option value="confirmed">Confirmed (Waiting)</option>
                    <option value="in_progress">In Progress (In Room)</option>
                    <option value="completed">Completed</option>
                    <option value="no_show">No Show</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- CLINICAL DOCUMENTATION WORKFLOW -->
            <div class="action-card">
                <h4 class="card-heading">Clinical Actions</h4>
                <p class="card-subtext">Generate medical records or issue prescriptions for this session:</p>

                <Link :href="`/doctor/appointments/${appData.id}/records/create`" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                    Create Medical Record
                </Link>

                <Link :href="`/doctor/appointments/${appData.id}/prescriptions/create`" class="btn btn-lime">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                    </svg>
                    Issue Prescription
                </Link>

                <Link :href="`/doctor/patients/${appData.patientDbId || appData.patientId || '1'}/history`" class="btn btn-outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    Patient History Log
                </Link>
            </div>
        </div>
    </div>

    <!-- EDIT PRESCRIPTION MODAL -->
    <div v-if="isEditModalOpen" class="modal-backdrop" @click.self="closeEditModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Edit Prescription #{{ activeEditRxCode }}</h3>
                <button type="button" class="close-btn" @click="closeEditModal">✕</button>
            </div>

            <form @submit.prevent="handleUpdateRx">
                <div class="modal-body">
                    <div class="table-wrap">
                        <table class="med-table">
                            <thead>
                                <tr>
                                    <th style="width: 28%;">Medication Name & Dosage</th>
                                    <th style="width: 20%;">Frequency / Timing</th>
                                    <th style="width: 16%;">Duration</th>
                                    <th style="width: 14%;">Refills</th>
                                    <th style="width: 18%;">Instructions</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in editForm.items" :key="idx" class="med-row">
                                    <td>
                                        <input v-model="item.name" type="text" class="form-control-sm" placeholder="e.g. Amlodipine 5mg" required />
                                    </td>
                                    <td>
                                        <select v-model="item.frequency" class="form-control-sm">
                                            <option value="1x Daily (Morning)">1x Daily (Morning)</option>
                                            <option value="1x Daily (Bedtime)">1x Daily (Bedtime)</option>
                                            <option value="2x Daily (12 Hours)">2x Daily (12 Hours)</option>
                                            <option value="3x Daily (8 Hours)">3x Daily (8 Hours)</option>
                                            <option value="As Needed (PRN)">As Needed (PRN)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input v-model="item.duration" type="text" class="form-control-sm" placeholder="90 Days" required />
                                    </td>
                                    <td>
                                        <select v-model="item.refills" class="form-control-sm">
                                            <option value="0">0 Refills</option>
                                            <option value="1">1 Refill</option>
                                            <option value="2">2 Refills</option>
                                            <option value="3">3 Refills</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input v-model="item.instructions" type="text" class="form-control-sm" placeholder="e.g. Take with food" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn-remove-row" title="Remove item" @click="removeEditRow(idx)">✕</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn-row-add" style="margin-bottom: 16px;" @click="addEditRow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add Line Item
                    </button>

                    <div class="form-group">
                        <label>Special Pharmacy Precautions & Directions</label>
                        <textarea v-model="editForm.pharmacyNotes" class="form-control" placeholder="Add additional directions for dispensing pharmacist..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" @click="closeEditModal">Cancel</button>
                    <button type="submit" class="btn btn-lime" :disabled="editForm.processing">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST NOTICE -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>{{ toastMsg }}</span>
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

/* HEADER BANNER CARD */
.detail-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px 32px;
    box-shadow: var(--shadow-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 24px;
}
.header-info-group { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); }
.detail-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin-top: 4px; }

.badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 700; }
.badge-confirmed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-in-progress { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-completed { background: var(--cream-alt); color: var(--ink-muted); border: 1px solid var(--line); }
.badge-cancelled { background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5; }

/* GRID LAYOUT */
.detail-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .detail-grid { grid-template-columns: 1fr; } }

.main-details-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); padding: 28px; }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title-text { display: flex; align-items: center; gap: 10px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }
.history-link { font-size: 13px; font-weight: 700; color: var(--forest); text-decoration: underline; }

/* PATIENT SUMMARY HEADER */
.patient-profile-strip { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
.patient-avatar-lg { width: 72px; height: 72px; border-radius: 50%; background: var(--lime); color: var(--lime-text); font-weight: 800; font-size: 24px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm); }
.patient-meta-lg h3 { font-size: 19px; font-weight: 800; color: var(--forest); margin-bottom: 2px; }
.patient-meta-lg p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; }

.patient-quick-stats { display: flex; gap: 12px; margin-top: 10px; flex-wrap: wrap; }
.stat-pill { font-size: 12px; font-weight: 600; background: var(--cream); border: 1px solid var(--line); padding: 4px 12px; border-radius: 999px; color: var(--ink); }

/* INFO GRID */
.info-pairs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 600px) { .info-pairs-grid { grid-template-columns: 1fr; } }

.info-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; }
.info-box label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.info-box span { font-size: 14.5px; font-weight: 700; color: var(--ink); display: block; }
.info-box small { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

/* REASON & CLINICAL PREP */
.symptom-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 18px; margin-bottom: 16px; }
.symptom-box label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.symptom-box p { font-size: 14.5px; font-weight: 600; color: var(--ink); }

/* VITALS STRIP */
.vitals-mini-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width: 600px) { .vitals-mini-grid { grid-template-columns: 1fr; } }
.vital-tile { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 14px; }
.vital-tile label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); display: block; }
.vital-tile b { font-family: var(--font-mono); font-size: 17px; font-weight: 800; color: var(--forest); display: block; margin-top: 2px; }

/* SIDEBAR ACTIONS */
.sidebar-col { display: flex; flex-direction: column; gap: 24px; }

.action-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 14px; }
.card-heading { font-size: 15px; font-weight: 800; color: var(--forest); }
.card-subtext { font-size: 12.5px; color: var(--ink-muted); }

.status-select { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 14px; font-size: 14px; font-weight: 700; color: var(--forest); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 700; transition: all 150ms ease; width: 100%; text-decoration: none; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
.btn-lime { background: var(--lime); color: var(--lime-text); border: 1px solid #c4dc3c; }
.btn-lime:hover { background: #d2e85a; }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

/* TOAST */
.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

/* PRESCRIPTION TABLE & MODAL STYLES */
.table-wrap { overflow-x: auto; margin-top: 10px; }
.med-table { width: 100%; border-collapse: collapse; min-width: 600px; }
.med-table th { background: var(--cream); padding: 10px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); text-align: left; }
.med-table td { padding: 10px 12px; border-bottom: 1px solid var(--line); vertical-align: middle; }

.freq-badge { background: var(--cream); color: var(--forest); border: 1px solid var(--line); padding: 2px 8px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 700; display: inline-block; }

.btn-edit-rx {
    background: var(--cream);
    color: var(--forest);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 150ms ease;
}
.btn-edit-rx:hover { background: var(--forest); color: #fff; border-color: var(--forest); }

.btn-delete-rx {
    background: #FEE2E2;
    color: #DC2626;
    border: 1px solid #FCA5A5;
    border-radius: var(--radius-sm);
    padding: 6px 14px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 150ms ease;
}
.btn-delete-rx:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.form-control-sm { width: 100%; height: 40px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); padding: 0 12px; font-size: 13.5px; color: var(--ink); transition: border-color 150ms ease; }
.form-control-sm:focus { border-color: var(--forest); background: var(--card); outline: none; }

.btn-row-add { display: inline-flex; align-items: center; gap: 6px; height: 36px; padding: 0 16px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); font-size: 12.5px; font-weight: 700; color: var(--forest); transition: all 150ms ease; cursor: pointer; }
.btn-row-add:hover { background: var(--forest); color: #fff; border-color: var(--forest); }

.btn-remove-row { width: 36px; height: 40px; border-radius: var(--radius-sm); border: 1px solid #FCA5A5; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; transition: all 150ms ease; cursor: pointer; }
.btn-remove-row:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.form-group { margin-top: 16px; display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; }
textarea.form-control { width: 100%; height: auto; min-height: 80px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 12px 16px; font-size: 13.5px; color: var(--ink); resize: vertical; transition: border-color 150ms ease; }
textarea.form-control:focus { border-color: var(--forest); background: var(--card); outline: none; }

.modal-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}
.modal-box {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 820px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-lift);
    overflow: hidden;
}
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-header h3 { font-size: 18px; font-weight: 800; color: var(--forest); margin: 0; }
.close-btn { background: transparent; border: none; font-size: 20px; color: var(--ink-muted); cursor: pointer; }
.close-btn:hover { color: var(--forest); }
.modal-body { padding: 24px; overflow-y: auto; }
.modal-footer { padding: 16px 24px; border-top: 1px solid var(--line); display: flex; justify-content: flex-end; gap: 12px; }
</style>
