<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    metrics?: {
        monthlyConsultations: number
        consultationGrowth: string
        patientSatisfaction: string
        satisfactionCount: number
        avgConsultationTime: string
        timeStatus: string
        completedPercentage: string
        completionDetail: string
        prescriptionsIssued: number
    }
    ratingBreakdown?: {
        fiveStar: number
        fourStar: number
        threeStar: number
        twoStar?: number
        oneStar?: number
    }
    reviews?: Array<{
        id: number
        patient: string
        rating: number
        date: string
        comment: string
    }>
    timeframe?: string
}>()

const currentTimeframe = ref(props.timeframe || 'this_month')

function changeTimeframe(val: string) {
    currentTimeframe.value = val
    router.get(
        '/doctor/performance',
        { timeframe: val },
        { preserveState: true, preserveScroll: true, replace: true }
    )
}

const metricsData = computed(() => props.metrics ?? {
    monthlyConsultations: 0,
    consultationGrowth: 'No data yet',
    patientSatisfaction: 'N/A',
    satisfactionCount: 0,
    avgConsultationTime: '~20 Mins',
    timeStatus: 'Optimal (Target 20-30m)',
    completedPercentage: '0%',
    completionDetail: '0 of 0 fulfilled',
    prescriptionsIssued: 0,
})

const breakdown = computed(() => props.ratingBreakdown ?? {
    fiveStar: 0,
    fourStar: 0,
    threeStar: 0,
    twoStar: 0,
    oneStar: 0,
})

const reviewList = computed(() => props.reviews ?? [])
</script>

<template>
    <Head title="My Performance" />

    <!-- PAGE HEADER WITH TIMEFRAME SELECTOR -->
    <div class="page-title-row">
        <div>
            <h2>Clinical Performance & Patient Feedback</h2>
            <p>Track consultation volume, patient satisfaction scores, and operational efficiency</p>
        </div>

        <div class="timeframe-selector">
            <button
                class="timeframe-btn"
                :class="{ active: currentTimeframe === 'this_month' }"
                @click="changeTimeframe('this_month')"
            >
                This Month
            </button>
            <button
                class="timeframe-btn"
                :class="{ active: currentTimeframe === 'last_3_months' }"
                @click="changeTimeframe('last_3_months')"
            >
                Last 3 Months
            </button>
            <button
                class="timeframe-btn"
                :class="{ active: currentTimeframe === 'this_year' }"
                @click="changeTimeframe('this_year')"
            >
                This Year
            </button>
            <button
                class="timeframe-btn"
                :class="{ active: currentTimeframe === 'all_time' }"
                @click="changeTimeframe('all_time')"
            >
                All Time
            </button>
        </div>
    </div>

    <!-- METRICS OVERVIEW STRIP -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-info">
                <label>Completed Consultations</label>
                <b>{{ metricsData.monthlyConsultations }} Visits</b>
                <span class="text-success">{{ metricsData.consultationGrowth }}</span>
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
                <b>{{ metricsData.patientSatisfaction }}</b>
                <span>Based on {{ metricsData.satisfactionCount }} Reviews</span>
            </div>
            <div class="metric-icon bg-amber-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Avg Consultation Time</label>
                <b>{{ metricsData.avgConsultationTime }}</b>
                <span>{{ metricsData.timeStatus }}</span>
            </div>
            <div class="metric-icon bg-lime-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-info">
                <label>Completion Rate</label>
                <b>{{ metricsData.completedPercentage }}</b>
                <span>{{ metricsData.completionDetail }}</span>
            </div>
            <div class="metric-icon bg-cream-soft">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v14a2 2 0 0 2 2h12a2 2 0 0 2 2V8z"/><polyline points="14 2 14 8 20 8"/>
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
                        <div class="bar-fill" :style="{ width: breakdown.fiveStar + '%' }"></div>
                    </div>
                    <span class="pct-label">{{ breakdown.fiveStar }}%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">4 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" :style="{ width: breakdown.fourStar + '%' }"></div>
                    </div>
                    <span class="pct-label">{{ breakdown.fourStar }}%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">3 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" :style="{ width: breakdown.threeStar + '%' }"></div>
                    </div>
                    <span class="pct-label">{{ breakdown.threeStar }}%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">2 Stars</span>
                    <div class="bar-bg">
                        <div class="bar-fill" :style="{ width: (breakdown.twoStar ?? 0) + '%' }"></div>
                    </div>
                    <span class="pct-label">{{ breakdown.twoStar ?? 0 }}%</span>
                </div>

                <div class="bar-row">
                    <span class="star-label">1 Star</span>
                    <div class="bar-bg">
                        <div class="bar-fill" :style="{ width: (breakdown.oneStar ?? 0) + '%' }"></div>
                    </div>
                    <span class="pct-label">{{ breakdown.oneStar ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- RECENT PATIENT REVIEWS LIST -->
        <div class="panel-card">
            <div class="panel-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Recent Verified Patient Reviews ({{ reviewList.length }})
            </div>

            <div class="reviews-list">
                <div v-if="reviewList.length === 0" class="empty-reviews">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <p>No patient reviews recorded for the selected timeframe yet.</p>
                </div>
                <div v-for="rev in reviewList" :key="rev.id" class="review-item">
                    <div class="review-top">
                        <b>{{ rev.patient }}</b>
                        <div class="stars">
                            {{ '★'.repeat(Math.max(1, Math.min(5, rev.rating))) }}
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
.page-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}
.page-title-row h2 { font-size: 22px; font-weight: 800; color: var(--forest); margin: 0; }
.page-title-row p { font-size: 13px; color: var(--ink-muted); margin: 4px 0 0 0; }

.timeframe-selector {
    display: flex;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 999px;
    padding: 3px;
    gap: 3px;
    box-shadow: var(--shadow-sm);
}

.timeframe-btn {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--ink-muted);
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 150ms ease;
}
.timeframe-btn:hover { color: var(--ink); }
.timeframe-btn.active { background: var(--forest); color: #fff; }

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
.bar-fill { height: 100%; background: var(--forest); border-radius: 999px; transition: width 300ms ease; }
.pct-label { width: 34px; font-family: var(--font-mono); font-weight: 700; color: var(--ink-muted); text-align: right; }

.reviews-list { display: flex; flex-direction: column; gap: 16px; }

.empty-reviews {
    text-align: center;
    padding: 32px 16px;
    color: var(--ink-muted);
}
.empty-reviews svg { margin-bottom: 8px; stroke: var(--ink-muted); opacity: 0.6; }
.empty-reviews p { font-size: 13.5px; margin: 0; font-weight: 600; }

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
