<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Medical Record #REC-301 Detail" />

    <!-- TOP NAV ROW -->
    <div class="top-nav-row">
        <Link href="/patient/medical-records" class="back-btn">← Back to Medical History</Link>
    </div>

    <!-- HEADER BANNER -->
    <div class="record-header-card">
        <div>
            <div class="badge-row">
                <span class="ref-badge">#REC-301</span>
                <span class="type-badge">Consultation & ECG</span>
            </div>
            <h1>Cardiology Follow-Up & ECG Assessment</h1>
        </div>

        <button class="btn btn-outline btn-export" @click="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
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
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    Clinical Vitals Measured During Visit
                </div>

                <div class="detail-vitals-grid">
                    <div class="vital-tile">
                        <label>Blood Pressure</label>
                        <b>120/80 mmHg</b>
                        <span>✓ Normal Range</span>
                    </div>
                    <div class="vital-tile">
                        <label>Heart Rate</label>
                        <b>72 bpm</b>
                        <span>✓ Regular Rhythm</span>
                    </div>
                    <div class="vital-tile">
                        <label>Oxygen Saturation</label>
                        <b>99% SpO2</b>
                        <span>✓ Optimal</span>
                    </div>
                    <div class="vital-tile">
                        <label>Body Temp</label>
                        <b>98.6 °F</b>
                        <span>✓ Afebrile</span>
                    </div>
                    <div class="vital-tile">
                        <label>Body Weight</label>
                        <b>74.5 kg</b>
                        <span>BMI 22.8</span>
                    </div>
                    <div class="vital-tile">
                        <label>Resp. Rate</label>
                        <b>16 bpm</b>
                        <span>✓ Normal</span>
                    </div>
                </div>
            </div>

            <!-- DIAGNOSIS & FINDINGS -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    Diagnoses & Clinical Impressions
                </div>

                <div class="diagnosis-box">
                    <div class="diag-row">
                        <span class="diag-name">Essential (Primary) Hypertension - Well Controlled</span>
                        <span class="icd-badge">ICD-10: I10</span>
                    </div>
                    <p class="diag-desc">Blood pressure parameters remain well within target clinic goals (&lt; 130/80 mmHg) on current regimen. No acute ischemic changes noted on resting electrocardiogram.</p>
                </div>

                <div class="diagnosis-box no-margin">
                    <div class="diag-row">
                        <span class="diag-name">Exertional Chest Tightness - Benign / Non-Ischemic</span>
                        <span class="icd-badge">ICD-10: R07.89</span>
                    </div>
                    <p class="diag-desc">Symptoms likely secondary to mild musculoskeletal strain following routine exercise routines. Negative for anginal equivalent signs.</p>
                </div>
            </div>

            <!-- PHYSICIAN NOTES -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Attending Physician Clinical Notes
                </div>

                <div class="notes-prose">
                    <p><strong>Subjective:</strong> Patient Habib Hossain presents for a scheduled 3-month cardiology follow-up. Reports feeling generally well. Mentions brief episodes of chest tightness after heavy dumbbell workouts last week, resolving spontaneously within 2 minutes of rest.</p>
                    <p><strong>Objective:</strong> Cardiovascular exam reveals regular rate and rhythm without murmurs, rubs, or gallops. S1 and S2 present and normal. Peripheral pulses 2+ bilaterally. Lungs clear to auscultation. 12-lead ECG demonstrates sinus rhythm at 72 bpm with normal axis and no ST-T wave abnormalities.</p>
                    <p><strong>Plan:</strong> Continue current daily regimen. Recommended routine hydration before exercise and minor warm-up routine adjustments. Follow-up consultation in 6 months or sooner if symptoms recur.</p>
                </div>
            </div>

            <!-- ATTACHED DIAGNOSTIC DOCUMENTS -->
            <div class="card-shell">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                    </svg>
                    Associated Test Files & Diagnostics
                </div>

                <div class="attachments-list">
                    <div class="attachment-item">
                        <div class="att-meta">
                            <div class="att-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                </svg>
                            </div>
                            <div class="att-info">
                                <b>12-Lead Electrocardiogram (ECG) Strip Scan</b>
                                <span>PDF · 1.4 MB · Uploaded July 14, 2026</span>
                            </div>
                        </div>
                        <button class="btn btn-outline att-btn" @click="alert('Downloading ECG PDF...')">Download</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR SUMMARY -->
        <div class="sidebar-summary-col">
            <!-- ATTENDING DOCTOR CARD -->
            <div class="doc-profile-card">
                <div class="doc-profile-row">
                    <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=200" alt="Dr. Sarah Jenkins" class="doc-avatar" />
                    <div class="doc-meta">
                        <h3>Dr. Sarah Jenkins</h3>
                        <p>Cardiology Attending</p>
                        <span class="dept-tag">Cardiology</span>
                    </div>
                </div>

                <div class="doc-sub-details">
                    <div class="doc-detail-pair">
                        <span>Visit Date:</span>
                        <b>July 14, 2026</b>
                    </div>
                    <div class="doc-detail-pair">
                        <span>Facility:</span>
                        <b>MediFlow Main Clinic</b>
                    </div>
                </div>
            </div>

            <!-- LINKED PRESCRIPTION CARD -->
            <div class="linked-rx-card">
                <h4>Prescription Prescribed</h4>

                <div class="rx-item-mini">
                    <div>
                        <b>Amlodipine 5 mg</b>
                        <span>Oral Tablet · 1x Daily</span>
                    </div>
                    <span class="status-active">Active</span>
                </div>

                <div style="margin-top:16px;">
                    <Link href="/patient/prescriptions/401" class="btn btn-primary">
                        View Full Prescription #RX-401 →
                    </Link>
                </div>
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

.record-header-card {
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
.badge-row { display: flex; align-items: center; gap: 10px; }
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); }
.type-badge { font-size: 12px; font-weight: 700; background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.03em; }
.record-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 6px 0 0 0; }

.record-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .record-grid { grid-template-columns: 1fr; } }

.main-content-col { display: flex; flex-direction: column; gap: 24px; }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); padding: 28px; }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }
.card-title svg { width: 18px; height: 18px; color: var(--forest); }

.detail-vitals-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
@media (max-width: 680px) { .detail-vitals-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .detail-vitals-grid { grid-template-columns: 1fr; } }

.vital-tile { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 14px 16px; }
.vital-tile label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.vital-tile b { font-family: var(--font-mono); font-size: 18px; font-weight: 800; color: var(--forest); display: block; }
.vital-tile span { font-size: 11.5px; color: #15803D; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-top: 2px; }

.diagnosis-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 20px; margin-bottom: 16px; }
.diagnosis-box.no-margin { margin-bottom: 0; }
.diag-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 8px; }
.diag-name { font-size: 16px; font-weight: 800; color: var(--ink); }
.icd-badge { font-family: var(--font-mono); font-size: 12px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); border: 1px solid #d2e85a; padding: 2px 8px; border-radius: var(--radius-sm); }
.diag-desc { font-size: 14px; color: var(--ink-muted); line-height: 1.55; margin: 0; }

.notes-prose { font-size: 14px; color: var(--ink); line-height: 1.65; background: var(--cream); border-left: 3px solid var(--forest); padding: 16px 20px; border-radius: 0 var(--radius-md) var(--radius-md) 0; }
.notes-prose p { margin-bottom: 12px; }
.notes-prose p:last-child { margin-bottom: 0; }

.attachments-list { display: flex; flex-direction: column; gap: 10px; }
.attachment-item { display: flex; align-items: center; justify-content: space-between; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 12px 16px; }
.att-meta { display: flex; align-items: center; gap: 12px; }
.att-icon { width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--card); border: 1px solid var(--line); display: flex; align-items: center; justify-content: center; color: var(--forest); }
.att-info b { font-size: 13.5px; font-weight: 700; color: var(--ink); display: block; }
.att-info span { font-size: 11.5px; color: var(--ink-muted); font-family: var(--font-mono); }
.att-btn { width: auto; height: 36px; font-size: 12.5px; }

.sidebar-summary-col { display: flex; flex-direction: column; gap: 24px; }
.doc-profile-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); }
.doc-profile-row { display: flex; gap: 16px; align-items: center; margin-bottom: 16px; }
.doc-avatar { width: 64px; height: 64px; border-radius: var(--radius-md); object-fit: cover; background: var(--cream-alt); }
.doc-meta h3 { font-size: 16px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.doc-meta p { font-size: 13px; color: var(--ink-muted); font-weight: 500; margin: 0; }
.dept-tag { display: inline-block; font-size: 11.5px; font-weight: 600; background: var(--lime-soft); color: var(--lime-text); padding: 2px 8px; border-radius: 999px; margin-top: 4px; }

.doc-sub-details { font-size: 13px; color: var(--ink-muted); border-top: 1px solid var(--line); padding-top: 14px; }
.doc-detail-pair { display: flex; justify-content: space-between; margin-bottom: 6px; }
.doc-detail-pair:last-child { margin-bottom: 0; }
.doc-detail-pair b { color: var(--ink); }

.linked-rx-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); }
.linked-rx-card h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 12px 0; }
.rx-item-mini { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 12px 14px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
.rx-item-mini b { font-size: 13.5px; font-weight: 700; color: var(--ink); display: block; }
.rx-item-mini span { font-size: 12px; color: var(--ink-muted); }
.status-active { font-size: 11px; font-weight: 700; background: #DCFCE7; color: #15803D; padding: 2px 6px; border-radius: 4px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; width: 100%; cursor: pointer; }
.btn-export { width: auto; height: 40px; font-size: 13px; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
