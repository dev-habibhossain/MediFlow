<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Prescription #RX-401 Detail" />

    <!-- TOP NAV ROW -->
    <div class="top-nav-row">
        <Link href="/patient/prescriptions" class="back-btn">← Back to Prescriptions List</Link>
    </div>

    <!-- TOP ACTION BAR -->
    <div class="rx-header-card">
        <div>
            <div class="badge-row">
                <span class="ref-badge">#RX-401</span>
                <span class="status-tag">Active Prescription</span>
            </div>
            <h1>Digital Prescription Specification</h1>
        </div>

        <div class="btn-row">
            <button class="btn btn-outline" @click="window.print()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
                </svg>
                Print
            </button>
            <button class="btn btn-primary" @click="alert('Downloading official Rx #RX-401 PDF...')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Download Official PDF
            </button>
        </div>
    </div>

    <!-- FORMAL RX SHEET CONTAINER -->
    <div class="rx-document-card">
        <!-- CLINIC BRAND STRIP -->
        <div class="doc-header-strip">
            <div class="hospital-brand">
                <b>
                    <span class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                            <path d="M12 21s-7-4.35-9.5-8.5C.6 8.9 2.3 5 6 5c2 0 3.3 1.1 4 2 .7-.9 2-2 4-2 3.7 0 5.4 3.9 3.5 7.5C19 16.65 12 21 12 21z"/>
                        </svg>
                    </span>
                    MediFlow Hospital & Clinics
                </b>
                <span>120 Harbor Ave, Suite 300 · Tel: (555) 019-2831</span>
            </div>

            <div class="rx-symbol">℞</div>
        </div>

        <!-- METADATA BLOCK -->
        <div class="meta-info-grid">
            <div class="meta-block">
                <label>Prescribing Physician</label>
                <b>Dr. Sarah Jenkins</b>
                <span>Senior Cardiologist (Lic #MD-90412)</span>
            </div>

            <div class="meta-block">
                <label>Patient Details</label>
                <b>Habib Hossain</b>
                <span>Age: 28 · Gender: Male · ID: #MDF-9021</span>
            </div>

            <div class="meta-block">
                <label>Issue Date & Validity</label>
                <b>July 14, 2026</b>
                <span>Valid Until: October 14, 2026</span>
            </div>

            <div class="meta-block">
                <label>Linked Consultation</label>
                <b><Link href="/patient/appointments/101" class="appt-link">Appointment #MDF-101</Link></b>
                <span>Cardiology Follow-Up</span>
            </div>
        </div>

        <!-- REGIMEN TABLE -->
        <div class="meds-table-wrap">
            <table class="meds-table">
                <thead>
                    <tr>
                        <th>Medication & Form</th>
                        <th>Dosage & Frequency</th>
                        <th>Duration / Refills</th>
                        <th>Instructions & Timing</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="med-name-cell">
                            <b>Amlodipine Besylate</b>
                            <span>5 mg Oral Tablet</span>
                        </td>
                        <td>
                            <span class="freq-pill">1x Daily</span>
                        </td>
                        <td>
                            <b>90 Days Supply</b>
                            <span class="refill-sub">2 Refills Authorized</span>
                        </td>
                        <td>
                            <strong>Take in the morning with food.</strong> Monitor resting blood pressure weekly.
                        </td>
                    </tr>

                    <tr>
                        <td class="med-name-cell">
                            <b>Atorvastatin Calcium</b>
                            <span>20 mg Oral Tablet</span>
                        </td>
                        <td>
                            <span class="freq-pill">1x Daily</span>
                        </td>
                        <td>
                            <b>90 Days Supply</b>
                            <span class="refill-sub">2 Refills Authorized</span>
                        </td>
                        <td>
                            <strong>Take at bedtime.</strong> Avoid grapefruit juice during duration of treatment.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SPECIAL INSTRUCTIONS -->
        <div class="notes-section">
            <h4>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Special Physician Notes & Precautions
            </h4>
            <p>Maintain consistent daily administration times. If you experience dizziness or muscle pain, contact the MediFlow Cardiology Desk immediately. Do not discontinue Amlodipine abruptly without consulting your physician.</p>
        </div>

        <!-- SIGNATURE FOOTER -->
        <div class="signature-strip">
            <div class="pharma-note">
                This is an authenticated electronic prescription issued under MediFlow Security Protocol v4.2. Dispensing pharmacy may verify token online.
            </div>

            <div class="signature-box">
                <div class="sig-script">S. Jenkins, MD</div>
                <b>Dr. Sarah Jenkins</b>
                <span>Digitally Signed via MediFlow Portal</span>
            </div>
        </div>
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
    transition: all 150ms ease;
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
.badge-row { display: flex; align-items: center; gap: 10px; }
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); }
.status-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.rx-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 4px 0 0 0; }

.btn-row { display: flex; gap: 10px; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 22px; border-radius: 999px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.rx-document-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 40px; box-shadow: var(--shadow-card); position: relative; }
@media (max-width: 640px) { .rx-document-card { padding: 24px; } }

.doc-header-strip { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 24px; border-bottom: 2px solid var(--line); margin-bottom: 28px; flex-wrap: wrap; gap: 20px; }
.hospital-brand b { font-size: 20px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 8px; }
.brand-icon { width: 24px; height: 24px; border-radius: 6px; background: var(--forest); display: inline-flex; align-items: center; justify-content: center; color: var(--lime); }
.hospital-brand span { font-size: 13px; color: var(--ink-muted); display: block; margin-top: 2px; }

.rx-symbol { font-family: var(--font-mono); font-size: 32px; font-weight: 800; color: var(--forest); line-height: 1; }

.meta-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; }
@media (max-width: 640px) { .meta-info-grid { grid-template-columns: 1fr; } }

.meta-block label { font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.meta-block b { font-size: 15px; font-weight: 800; color: var(--ink); display: block; }
.meta-block span { font-size: 13px; color: var(--ink-muted); display: block; }
.appt-link { text-decoration: underline; color: var(--forest); }

.meds-table-wrap { overflow-x: auto; margin-bottom: 32px; }
.meds-table { width: 100%; border-collapse: collapse; text-align: left; }
.meds-table th { background: var(--cream); padding: 12px 16px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.meds-table td { padding: 18px 16px; border-bottom: 1px solid var(--line); font-size: 14px; vertical-align: top; }
.meds-table tr:last-child td { border-bottom: none; }

.med-name-cell b { font-size: 15px; font-weight: 800; color: var(--forest); display: block; }
.med-name-cell span { font-size: 12.5px; color: var(--ink-muted); display: block; }

.freq-pill { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); padding: 3px 8px; border-radius: var(--radius-sm); display: inline-block; }
.refill-sub { font-size: 12px; color: var(--ink-muted); display: block; }

.notes-section { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: var(--radius-lg); padding: 20px; color: #1E3A8A; margin-bottom: 32px; }
.notes-section h4 { font-size: 14px; font-weight: 700; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px; }
.notes-section p { font-size: 13.5px; line-height: 1.5; margin: 0; }

.signature-strip { display: flex; justify-content: space-between; align-items: flex-end; padding-top: 24px; border-top: 1px dashed var(--line); flex-wrap: wrap; gap: 20px; }
.pharma-note { font-size: 12px; color: var(--ink-muted); max-width: 38ch; line-height: 1.4; }
.signature-box { text-align: right; }
.sig-script { font-family: 'Courier New', monospace; font-size: 18px; font-weight: bold; color: var(--forest); letter-spacing: -1px; margin-bottom: 2px; }
.signature-box b { font-size: 14px; font-weight: 700; color: var(--forest); display: block; }
.signature-box span { font-size: 12px; color: var(--ink-muted); display: block; }
</style>
