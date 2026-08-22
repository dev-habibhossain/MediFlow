<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const items = ref([
    {
        drug: 'Lisinopril 10mg Oral Tablet',
        dosage: '10mg',
        frequency: 'Once Daily (Morning)',
        duration: '30 Days',
        qty: '30 Tablets',
        refills: '3',
    },
])

const pharmacyNotes = ref('Dispense brand or generic equivalent. Take with full glass of water.')

function addItem() {
    items.value.push({
        drug: '',
        dosage: '',
        frequency: 'Once Daily',
        duration: '30 Days',
        qty: '30',
        refills: '0',
    })
}

function removeItem(index: number) {
    if (items.value.length > 1) {
        items.value.splice(index, 1)
    }
}

function handleSubmit() {
    alert('Digital Prescription issued and transmitted to pharmacy network!')
}
</script>

<template>
    <Head title="Issue Digital Prescription" />

    <!-- NAV BAR ROW -->
    <div class="nav-bar-row">
        <Link href="/doctor/appointments/MDF-9021" class="back-link">
            ← Back to Appointment #MDF-9021
        </Link>
        <span class="context-tag">Issuing Rx for Patient: Habib Hossain (#MDF-9021)</span>
    </div>

    <!-- SAFETY ALLERGY ALERT BANNER -->
    <div class="allergy-alert-banner">
        <div class="alert-icon">⚠️</div>
        <div class="alert-text">
            <b>Patient Safety Warning: Known Allergies</b>
            <p>Habib Hossain has recorded severe anaphylactic allergies to <strong>Penicillin</strong>. Please verify cross-reactivity before prescribing.</p>
        </div>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="form-card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                Issue Digital Prescription Order (Rx)
            </h3>
        </div>

        <form @submit.prevent="handleSubmit">
            <!-- DYNAMIC MEDICATION TABLE -->
            <div class="form-section">
                <div class="section-title-row">
                    <h4 class="section-title">Medication Orders</h4>
                    <button type="button" class="btn-sm btn-outline" @click="addItem">+ Add Medication Item</button>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 28%;">Medication & Strength</th>
                                <th style="width: 14%;">Dosage</th>
                                <th style="width: 22%;">Frequency / Schedule</th>
                                <th style="width: 12%;">Duration</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 8%;">Refills</th>
                                <th style="width: 6%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in items" :key="idx">
                                <td>
                                    <input v-model="item.drug" type="text" class="table-input" placeholder="e.g. Lisinopril 10mg" required />
                                </td>
                                <td>
                                    <input v-model="item.dosage" type="text" class="table-input" placeholder="e.g. 10mg" required />
                                </td>
                                <td>
                                    <select v-model="item.frequency" class="table-select">
                                        <option value="Once Daily (Morning)">Once Daily (Morning)</option>
                                        <option value="Twice Daily (BID)">Twice Daily (BID)</option>
                                        <option value="Three Times Daily (TID)">Three Times Daily (TID)</option>
                                        <option value="As Needed (PRN)">As Needed (PRN)</option>
                                    </select>
                                </td>
                                <td>
                                    <input v-model="item.duration" type="text" class="table-input" placeholder="30 Days" required />
                                </td>
                                <td>
                                    <input v-model="item.qty" type="text" class="table-input mono" placeholder="30" required />
                                </td>
                                <td>
                                    <input v-model="item.refills" type="text" class="table-input mono" placeholder="0" required />
                                </td>
                                <td>
                                    <button type="button" class="btn-icon-danger" title="Remove Item" @click="removeItem(idx)">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PHARMACY & DISPENSING NOTES -->
            <div class="form-section">
                <h4 class="section-title">Pharmacy & Special Dispensing Instructions</h4>

                <div class="form-group">
                    <label>Instructions for Pharmacist / Patient</label>
                    <textarea v-model="pharmacyNotes" class="form-control textarea" rows="3"></textarea>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <Link href="/doctor/appointments/MDF-9021" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-primary">Issue & Transmit Digital Rx</button>
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

.allergy-alert-banner {
    background: #FEE2E2;
    border: 1px solid #FCA5A5;
    border-radius: var(--radius-lg);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}
.alert-icon { font-size: 24px; }
.alert-text b { font-size: 14px; color: #991B1B; display: block; margin-bottom: 2px; }
.alert-text p { font-size: 12.5px; color: #7F1D1D; margin: 0; }

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
.section-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.section-title {
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--forest);
    border-left: 3px solid var(--lime);
    padding-left: 10px;
}

.table-responsive { width: 100%; overflow-x: auto; }

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--cream);
    padding: 12px 14px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted);
    border-bottom: 1px solid var(--line);
    text-align: left;
}

.data-table td {
    padding: 10px 8px;
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
}

.table-input, .table-select {
    height: 40px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 10px;
    font-size: 13.5px;
    color: var(--ink);
    width: 100%;
}
.table-input.mono { font-family: var(--font-mono); }

.btn-icon-danger {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #FEE2E2;
    color: #DC2626;
    border: none;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }

.form-control {
    height: 44px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 14px;
    color: var(--ink);
}
.form-control.textarea { height: auto; padding: 12px 14px; resize: vertical; }

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid var(--line);
    padding-top: 20px;
}

.btn { display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 24px; border-radius: 999px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-sm { display: inline-flex; align-items: center; justify-content: center; padding: 6px 16px; border-radius: 999px; font-size: 12.5px; font-weight: 700; cursor: pointer; }
.btn-outline { background: transparent; color: var(--forest); border: 1.5px solid var(--line); }
.btn-outline:hover { background: var(--cream); border-color: var(--forest); }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }
</style>
