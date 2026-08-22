<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

const reviews = [
    {
        id: 1,
        patient: 'Anonymous Patient',
        rating: 5,
        date: 'Aug 20, 2026',
        comment: 'Dr. Sarah Jenkins is incredibly attentive and thorough. She explained my blood pressure management plan clearly and answered all my questions.',
    },
    {
        id: 2,
        patient: 'Tanjila A.',
        rating: 5,
        date: 'Aug 18, 2026',
        comment: 'Very minimal wait time and excellent telehealth experience! Seamless prescription delivery to my pharmacy.',
    },
    {
        id: 3,
        patient: 'Robert C.',
        rating: 5,
        date: 'Aug 12, 2026',
        comment: 'Outstanding cardiology specialist. Felt completely cared for during my routine screening.',
    },
]
</script>

<template>
    <Head title="My Performance" />

    <!-- PAGE HEADER -->
    <div class="page-title-row">
        <div>
            <h2>Clinical Performance & Patient Feedback</h2>
            <p>Track consultation volume, patient satisfaction scores, and operational efficiency</p>
        </div>
    </div>

    <!-- METRICS OVERVIEW STRIP -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <label>Monthly Consultations</label>
                <b>142 Visits</b>
                <span class="text-success">↑ 12% vs last month</span>
            </div>
            <div class="metric-icon bg-forest-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Satisfaction Score</label>
                <b>4.9 / 5.0</b>
                <span>Based on 98 Reviews</span>
            </div>
            <div class="metric-icon bg-amber-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>On-Time Start Rate</label>
                <b>98.4%</b>
                <span>Avg Wait: 3.2 mins</span>
            </div>
            <div class="metric-icon bg-lime-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Prescriptions Issued</label>
                <b>118 Rxs</b>
                <span>100% Digital Transmission</span>
            </div>
            <div class="metric-icon bg-cream-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- MAIN ANALYTICS GRID -->
    <div class="analytics-grid">
        <!-- RATING DISTRIBUTION & VOLUME CARD -->
        <div class="panel-card">
            <div class="panel-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                Patient Rating Breakdown
            </div>

            <div class="rating-breakdown">
                <div class="bar-row">
                    <span class="star-label">5 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: 92%;"></div>
                    </div>
                    <span class="pct-label">92%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">4 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: 6%;"></div>
                    </div>
                    <span class="pct-label">6%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">3 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: 2%;"></div>
                    </div>
                    <span class="pct-label">2%</span>
                </div>
            </div>
        </div>

        <!-- RECENT PATIENT REVIEWS LIST -->
        <div class="panel-card">
            <div class="panel-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Recent Verified Patient Reviews
            </div>

            <div class="reviews-list">
                <div v-for="rev in reviews" :key="rev.id" class="review-item">
                    <div class="review-top">
                        <b>{{ rev.patient }}</b>
                        <div class="stars">
                            ★★★★★
                        </div>
                    </div>
                    <p class="review-text">"{{ rev.comment }}"</p>
                    <small class="review-date">{{ rev.date }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page-title-row { margin-bottom: 24px; }
.page-title-row h2 { font-size: 22px; font-weight: 800; color: var(--forest); }
.page-title-row p { font-size: 13px; color: var(--ink-muted); }

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
@media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metrics-grid { grid-template-columns: 1fr; } }

.metric-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.metric-info label { font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); display: block; margin-bottom: 4px; }
.metric-info b { font-family: var(--font-mono); font-size: 26px; font-weight: 800; color: var(--forest); display: block; }
.metric-info span { font-size: 12px; color: var(--ink-muted); font-weight: 600; display: inline-block; margin-top: 6px; }
.metric-info span.text-success { color: #15803D; }

.metric-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.metric-icon svg { width: 22px; height: 22px; }
.bg-forest-soft { background: #E2E8F0; color: var(--forest); }
.bg-lime-soft { background: var(--lime-soft); color: var(--lime-text); }
.bg-cream-soft { background: var(--cream); color: var(--forest); }
.bg-amber-soft { background: #FEF3C7; color: #B45309; }

.analytics-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) { .analytics-grid { grid-template-columns: 1fr; } }

.panel-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px;
    box-shadow: var(--shadow-card);
}

.panel-header {
    font-size: 15px;
    font-weight: 800;
    color: var(--forest);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--line);
    padding-bottom: 10px;
}

.rating-breakdown { display: flex; flex-direction: column; gap: 14px; }

.bar-row { display: flex; align-items: center; gap: 12px; font-size: 12.5px; }
.star-label { width: 60px; font-weight: 700; color: var(--forest); }
.bar-bg { flex: 1; height: 10px; border-radius: 999px; background: var(--cream); overflow: hidden; border: 1px solid var(--line); }
.bar-fill { height: 100%; background: var(--forest); border-radius: 999px; }
.pct-label { width: 34px; font-family: var(--font-mono); font-weight: 700; color: var(--ink-muted); text-align: right; }

.reviews-list { display: flex; flex-direction: column; gap: 16px; }

.review-item {
    background: var(--cream);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 16px;
}

.review-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.review-top b { font-size: 13.5px; font-weight: 700; color: var(--forest); }
.stars { color: #F59E0B; font-size: 13px; letter-spacing: 2px; }

.review-text { font-size: 13px; color: var(--ink); line-height: 1.4; margin: 0 0 6px 0; }
.review-date { font-size: 11px; color: var(--ink-muted); font-family: var(--font-mono); }
</style>
