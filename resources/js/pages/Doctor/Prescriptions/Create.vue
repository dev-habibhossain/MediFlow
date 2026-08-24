<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    appointment?: {
        id: string
        db_id: number
    }
    patient?: {
        id: string
        name: string
        initials: string
        gender: string
        age: number
        bloodGroup: string
        allergies: string
    }
    doctor?: {
        name: string
        license: string
    }
}>()

const appInfo = computed(() => props.appointment ?? {
    id: '101',
    db_id: 101,
})

const patientInfo = computed(() => props.patient ?? {
    id: 'MDF-9021',
    name: 'Habib Hossain',
    initials: 'HH',
    gender: 'Male',
    age: 28,
    bloodGroup: 'O+',
    allergies: 'Penicillin (Mild)',
})

const doctorInfo = computed(() => props.doctor ?? {
    name: 'Dr. Sarah Jenkins',
    license: 'MD-90412',
})

const showToast = ref(false)

const form = useForm({
    items: [
        {
            name: 'Amlodipine Besylate 5mg',
            frequency: '1x Daily (Morning)',
            duration: '90 Days',
            refills: '2',
            instructions: 'Take morning with food',
        },
    ],
    pharmacyNotes: 'Maintain consistent daily administration times.',
})

function addRow() {
    form.items.push({
        name: '',
        frequency: '1x Daily (Morning)',
        duration: '30 Days',
        refills: '1',
        instructions: '',
    })
}

function removeRow(index: number) {
    if (form.items.length > 1) {
        form.items.splice(index, 1)
    } else {
        alert('Prescription must contain at least one medication line item.')
    }
}

function handleIssueRx() {
    form.post(`/doctor/appointments/${appInfo.value.id}/prescriptions`, {
        preserveScroll: true,
        onSuccess: () => {
            showToast.value = true
        },
    })
}
</script>

<template>
    <Head title="Issue Electronic Prescription - MediFlow" />

    <!-- TOP HEADER -->
    <div class="top-nav-row">
        <Link href="/doctor/appointments/101" class="back-btn">
            ← Cancel & Return to Appointment #MDF-101
        </Link>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="rx-header-card">
        <div>
            <span class="ref-badge">Issue Digital Rx for Visit #MDF-101</span>
            <h1>Issue Electronic Prescription</h1>
        </div>
    </div>

    <!-- ALLERGY ALERT BOX -->
    <div class="allergy-alert-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>Safety Warning: Patient {{ patientInfo.name }} has a recorded allergy to <strong>{{ patientInfo.allergies }}</strong>. Avoid prescribing beta-lactam antibiotics.</span>
    </div>

    <div class="patient-summary-box">
        <div class="patient-meta-group">
            <div class="patient-avatar-md">{{ patientInfo.initials }}</div>
            <div class="patient-info">
                <b>{{ patientInfo.name }}</b>
                <span>Patient ID: #{{ patientInfo.id }} · {{ patientInfo.gender }}, {{ patientInfo.age }} Yrs · Blood Type: {{ patientInfo.bloodGroup }}</span>
            </div>
        </div>

        <div class="doctor-badge">
            Prescribing Physician: <strong>{{ doctorInfo.name }} ({{ doctorInfo.license }})</strong>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="card-title-row">
            <div class="card-title-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
                Prescription Line Items & Regimen
            </div>
        </div>

        <form @submit.prevent="handleIssueRx">
            <!-- MEDICATION ITEMS TABLE -->
            <div class="table-wrap">
                <table class="med-table">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Medication Name & Dosage</th>
                            <th style="width: 20%;">Frequency / Timing</th>
                            <th style="width: 16%;">Duration</th>
                            <th style="width: 14%;">Refills</th>
                            <th style="width: 18%;">Special Timing Instructions</th>
                            <th style="width: 40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, idx) in form.items" :key="idx" class="med-row">
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
                                <button type="button" class="btn-remove-row" title="Remove item" @click="removeRow(idx)">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn-row-add" @click="addRow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Medication Line Item
            </button>

            <!-- SPECIAL PHARMACY & PATIENT NOTES -->
            <div class="form-group">
                <label>Special Pharmacy Precautions & Directions</label>
                <textarea v-model="form.pharmacyNotes" class="form-control" placeholder="Add additional directions for dispensing pharmacist..."></textarea>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/doctor/appointments/101" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-lime">Sign & Issue Electronic Prescription</button>
            </div>
        </form>
    </div>

    <!-- TOAST NOTICE -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Digital Prescription #RX-401 issued successfully!
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

.rx-header-card {
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
.rx-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; }

.allergy-alert-box {
    background: #FEF2F2;
    border: 1px solid #FCA5A5;
    border-radius: var(--radius-lg);
    padding: 16px 20px;
    color: #991B1B;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 24px;
}
.allergy-alert-box svg { color: #DC2626; flex-shrink: 0; }

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
.doctor-badge { font-size: 12.5px; color: var(--ink-muted); font-family: var(--font-mono); }

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 32px;
    box-shadow: var(--shadow-card);
}
@media (max-width: 600px) { .form-card { padding: 20px; } }

.card-title-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid var(--line); padding-bottom: 14px; flex-wrap: wrap; gap: 12px; }
.card-title-text { font-size: 16px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.table-wrap { overflow-x: auto; margin-bottom: 20px; }
.med-table { width: 100%; border-collapse: collapse; min-width: 760px; }
.med-table th { background: var(--cream); padding: 12px 14px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); text-align: left; }
.med-table td { padding: 12px 10px; border-bottom: 1px solid var(--line); vertical-align: top; }

.form-control-sm { width: 100%; height: 40px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); padding: 0 12px; font-size: 13.5px; color: var(--ink); transition: border-color 150ms ease; }
.form-control-sm:focus { border-color: var(--forest); background: var(--card); outline: none; }

.btn-row-add { display: inline-flex; align-items: center; gap: 6px; height: 38px; padding: 0 18px; border-radius: 999px; background: var(--cream); border: 1px solid var(--line); font-size: 13px; font-weight: 700; color: var(--forest); transition: all 150ms ease; margin-bottom: 28px; cursor: pointer; }
.btn-row-add:hover { background: var(--forest); color: #fff; border-color: var(--forest); }

.btn-remove-row { width: 36px; height: 40px; border-radius: var(--radius-sm); border: 1px solid #FCA5A5; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; transition: all 150ms ease; cursor: pointer; }
.btn-remove-row:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.form-group { margin-bottom: 24px; display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; }
textarea.form-control { width: 100%; height: auto; min-height: 90px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 12px 16px; font-size: 14px; color: var(--ink); resize: vertical; transition: border-color 150ms ease; }
textarea.form-control:focus { border-color: var(--forest); background: var(--card); outline: none; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 150ms ease; }
.btn-lime { background: var(--lime); color: var(--lime-text); border: 1px solid #c4dc3c; font-weight: 700; }
.btn-lime:hover { background: #d2e85a; }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
