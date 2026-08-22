<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showToast = ref(false)

const supersedeReason = ref('Adjusted Amlodipine dosage from 5mg to 10mg based on 24h blood pressure telemetry log.')

const items = ref([
    {
        name: 'Amlodipine Besylate 10mg',
        frequency: '1x Daily (Morning)',
        duration: '90 Days',
        refills: '2',
        instructions: 'Take morning with food',
    },
    {
        name: 'Atorvastatin Calcium 20mg',
        frequency: '1x Daily (Bedtime)',
        duration: '90 Days',
        refills: '2',
        instructions: 'Take at bedtime, avoid grapefruit',
    },
])

const pharmacyNotes = ref('Discontinue previous 5mg Amlodipine. Patient to transition directly to 10mg daily morning dose.')

function addRow() {
    items.value.push({
        name: '',
        frequency: '1x Daily (Morning)',
        duration: '30 Days',
        refills: '1',
        instructions: '',
    })
}

function removeRow(index: number) {
    if (items.value.length > 1) {
        items.value.splice(index, 1)
    } else {
        alert('Prescription must contain at least one medication line item.')
    }
}

function handleSupersedeRx() {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
        window.location.href = '/doctor/patients/9021'
    }, 1200)
}
</script>

<template>
    <Head title="Supersede Prescription - MediFlow" />

    <!-- TOP HEADER -->
    <div class="top-nav-row">
        <Link href="/doctor/patients/9021" class="back-btn">
            ← Cancel & Back to Patient History
        </Link>
    </div>

    <!-- HEADER BANNER CARD -->
    <div class="rx-header-card">
        <div>
            <span class="ref-badge">Superseding Original Rx #RX-401</span>
            <h1>Correct & Supersede Prescription</h1>
        </div>
    </div>

    <!-- COMPLIANCE WARNING BANNER -->
    <div class="supersede-alert-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>
            <h4>Pharmacy Protocol Notice</h4>
            <p>Issuing this corrected prescription will immediately void Prescription <strong>#RX-401</strong> in the hospital central pharmacy system. The patient and assigned dispensing pharmacy will receive a cancellation broadcast for the previous order.</p>
        </div>
    </div>

    <!-- PATIENT MINI SUMMARY -->
    <div class="patient-summary-box">
        <div class="patient-meta-group">
            <div class="patient-avatar-md">HH</div>
            <div class="patient-info">
                <b>Habib Hossain</b>
                <span>Patient ID: #MDF-9021 · Male, 28 Yrs · Original Rx Issued: July 14, 2026</span>
            </div>
        </div>

        <div class="record-rev-badge">
            New Record ID: <strong>#RX-401-REV1</strong>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="card-title-row">
            <div class="card-title-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
                Corrected Line Items & Regimen
            </div>
        </div>

        <form @submit.prevent="handleSupersedeRx">
            <!-- REASON FOR SUPERSEDING -->
            <div class="reason-highlight-box">
                <label>Reason for Correction / Superseding <span>*</span></label>
                <input v-model="supersedeReason" type="text" class="form-control" required placeholder="E.g., Adjusted dosage..." />
            </div>

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
                        <tr v-for="(item, idx) in items" :key="idx" class="med-row">
                            <td>
                                <input v-model="item.name" type="text" class="form-control-sm" placeholder="e.g. Amlodipine 10mg" required />
                            </td>
                            <td>
                                <select v-model="item.frequency" class="form-control-sm">
                                    <option value="1x Daily (Morning)">1x Daily (Morning)</option>
                                    <option value="1x Daily (Bedtime)">1x Daily (Bedtime)</option>
                                    <option value="2x Daily (12 Hours)">2x Daily (12 Hours)</option>
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
                <label>Special Pharmacy Directions (Superseded Version)</label>
                <textarea v-model="pharmacyNotes" class="form-control" placeholder="Add additional directions for dispensing pharmacist..."></textarea>
            </div>

            <!-- BUTTON ROW -->
            <div class="form-actions">
                <Link href="/doctor/patients/9021" class="btn btn-outline">Cancel Amendment</Link>
                <button type="submit" class="btn btn-lime">Sign & Issue Superseded Rx #RX-401-REV1</button>
            </div>
        </form>
    </div>

    <!-- TOAST NOTICE -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Prescription #RX-401-REV1 issued! #RX-401 voided.
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

.supersede-alert-box {
    background: #FEF3C7;
    border: 1px solid #FDE68A;
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    color: #B45309;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 24px;
}
.supersede-alert-box svg { flex-shrink: 0; margin-top: 2px; }
.supersede-alert-box h4 { font-size: 14px; font-weight: 800; margin-bottom: 2px; }
.supersede-alert-box p { font-size: 13px; line-height: 1.45; color: #92400E; margin: 0; }

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
.record-rev-badge { font-size: 12.5px; color: var(--ink-muted); font-family: var(--font-mono); }

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

.reason-highlight-box {
    background: #FFFBEB;
    padding: 16px;
    border: 1px solid #FDE68A;
    border-radius: var(--radius-md);
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.reason-highlight-box label { font-size: 13px; font-weight: 700; color: #92400E; }
.reason-highlight-box label span { color: #DC2626; }

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
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: #fff; padding: 0 14px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); outline: none; }
textarea.form-control { height: auto; min-height: 90px; padding: 12px 16px; resize: vertical; background: var(--cream); }
textarea.form-control:focus { background: var(--card); }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid var(--line); }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 150ms ease; }
.btn-lime { background: var(--lime); color: var(--lime-text); border: 1px solid #c4dc3c; font-weight: 700; }
.btn-lime:hover { background: #d2e85a; }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
