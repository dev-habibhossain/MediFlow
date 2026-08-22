<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const supersedeReason = ref('dosage_adjustment')
const customReasonNotes = ref('Increasing dosage from 10mg to 20mg daily due to sub-optimal blood pressure control.')

const items = ref([
    {
        drug: 'Lisinopril 20mg Oral Tablet',
        dosage: '20mg',
        frequency: 'Once Daily (Morning)',
        duration: '30 Days',
        qty: '30 Tablets',
        refills: '3',
    },
])

function handleSupersede() {
    alert('Prescription #RX-8041 superseded successfully! Original order voided.')
}
</script>

<template>
    <Head title="Supersede Prescription #RX-8041" />

    <!-- NAV BAR ROW -->
    <div class="nav-bar-row">
        <Link href="/doctor/patients/MDF-9021/history" class="back-link">
            ← Back to Patient History
        </Link>
        <span class="context-tag">Superseding Rx Order Ref #RX-8041 • Patient: Habib Hossain</span>
    </div>

    <!-- SUPERSEDE WARNING BANNER -->
    <div class="supersede-banner">
        <div class="banner-icon">🔄</div>
        <div class="banner-text">
            <b>Voiding & Superseding Existing Prescription Order #RX-8041</b>
            <p>Submitting this form will automatically flag previous order #RX-8041 (Lisinopril 10mg) as VOID/SUPERSEDED in the electronic health network.</p>
        </div>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="form-card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                Replacement Prescription Order (Rx)
            </h3>
        </div>

        <form @submit.prevent="handleSupersede">
            <!-- REASON FOR SUPERSEDING -->
            <div class="form-section highlight-section">
                <h4 class="section-title">Reason for Voiding Previous Rx</h4>

                <div class="form-grid-dual">
                    <div class="form-group">
                        <label>Primary Clinical Reason <span>*</span></label>
                        <select v-model="supersedeReason" class="form-control" required>
                            <option value="dosage_adjustment">Dosage Adjustment / Titration</option>
                            <option value="adverse_reaction">Adverse Drug Event / Side Effects</option>
                            <option value="drug_interaction">Drug Interaction Concern</option>
                            <option value="patient_preference">Formulation / Patient Request</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Detailed Explanation / Clinical Rationale <span>*</span></label>
                        <input v-model="customReasonNotes" type="text" class="form-control" required />
                    </div>
                </div>
            </div>

            <!-- REPLACEMENT MEDICATION ORDER -->
            <div class="form-section">
                <h4 class="section-title">New Replacement Medication Item</h4>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Medication & Strength</th>
                                <th style="width: 15%;">Dosage</th>
                                <th style="width: 25%;">Frequency</th>
                                <th style="width: 12%;">Duration</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 8%;">Refills</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in items" :key="idx">
                                <td>
                                    <input v-model="item.drug" type="text" class="table-input" required />
                                </td>
                                <td>
                                    <input v-model="item.dosage" type="text" class="table-input" required />
                                </td>
                                <td>
                                    <select v-model="item.frequency" class="table-select">
                                        <option value="Once Daily (Morning)">Once Daily (Morning)</option>
                                        <option value="Twice Daily (BID)">Twice Daily (BID)</option>
                                    </select>
                                </td>
                                <td>
                                    <input v-model="item.duration" type="text" class="table-input" required />
                                </td>
                                <td>
                                    <input v-model="item.qty" type="text" class="table-input mono" required />
                                </td>
                                <td>
                                    <input v-model="item.refills" type="text" class="table-input mono" required />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FORM ACTIONS -->
            <div class="form-actions">
                <Link href="/doctor/patients/MDF-9021/history" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-primary">Void Existing & Issue Replacement Rx</button>
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

.supersede-banner {
    background: #E0F2FE;
    border: 1px solid #BAE6FD;
    border-radius: var(--radius-lg);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}
.banner-icon { font-size: 24px; }
.banner-text b { font-size: 14px; color: #0369A1; display: block; margin-bottom: 2px; }
.banner-text p { font-size: 12.5px; color: #0284C7; margin: 0; }

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
.highlight-section { background: var(--cream); padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--line); }

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
    background: var(--card);
    padding: 0 10px;
    font-size: 13.5px;
    color: var(--ink);
    width: 100%;
}
.table-input.mono { font-family: var(--font-mono); }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }

.form-control {
    height: 44px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--card);
    padding: 0 14px;
    font-size: 14px;
    color: var(--ink);
}

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
