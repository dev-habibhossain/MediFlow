<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const overallRating = ref(5)
const bedsideRating = ref(5)
const waitRating = ref(4)
const staffRating = ref(5)
const reviewText = ref('')
const isSuccessModalOpen = ref(false)

const ratingLabels = ['1.0 — Poor', '2.0 — Fair', '3.0 — Good', '4.0 — Very Good', '5.0 — Excellent Experience']

function setOverall(score: number) {
    overallRating.value = score
}

function handleReviewSubmit() {
    isSuccessModalOpen.value = true
}

function returnToAppointments() {
    router.visit('/patient/appointments')
}
</script>

<template>
    <Head title="Leave a Doctor Review" />

    <!-- TOP NAV ROW -->
    <div class="top-nav-row">
        <Link href="/patient/appointments" class="back-btn">← Back to My Appointments</Link>
    </div>

    <!-- HEADER CARD -->
    <div class="review-header-card">
        <div>
            <span class="ref-badge">Completed Visit #MDF-90</span>
            <h1>Leave a Doctor Review & Feedback</h1>
        </div>
    </div>

    <!-- MAIN REVIEW FORM CARD -->
    <div class="review-form-card">
        <!-- COMPLETED DOCTOR BANNER -->
        <div class="doc-review-banner">
            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=200" alt="Dr. Emily Watson" />
            <div class="doc-review-info">
                <h3>Dr. Emily Watson</h3>
                <p>Pediatric Specialist · Pediatrics Dept</p>
                <span>Consultation completed on July 14, 2026</span>
            </div>
        </div>

        <form @submit.prevent="handleReviewSubmit">
            <!-- OVERALL RATING -->
            <div class="star-rating-section">
                <h3>How was your overall experience?</h3>
                <p>Tap a star to rate your consultation with Dr. Emily Watson</p>

                <div class="stars-row">
                    <span v-for="star in 5" :key="star" class="star-btn" :class="{ active: star <= overallRating }" @click="setOverall(star)">★</span>
                </div>
                <span class="rating-label">{{ ratingLabels[overallRating - 1] }}</span>
            </div>

            <!-- SUB-RATINGS -->
            <div class="sub-ratings-grid">
                <div class="sub-rating-item">
                    <label>Doctor's Bedside Manner</label>
                    <div class="mini-stars">
                        <span v-for="star in 5" :key="star" class="mini-star" :class="{ active: star <= bedsideRating }" @click="bedsideRating = star">★</span>
                    </div>
                </div>

                <div class="sub-rating-item">
                    <label>Wait Time & Efficiency</label>
                    <div class="mini-stars">
                        <span v-for="star in 5" :key="star" class="mini-star" :class="{ active: star <= waitRating }" @click="waitRating = star">★</span>
                    </div>
                </div>

                <div class="sub-rating-item">
                    <label>Clinic Staff Friendliness</label>
                    <div class="mini-stars">
                        <span v-for="star in 5" :key="star" class="mini-star" :class="{ active: star <= staffRating }" @click="staffRating = star">★</span>
                    </div>
                </div>
            </div>

            <!-- WRITTEN REVIEW TEXTAREA -->
            <div class="form-group">
                <label class="form-label" for="reviewText">
                    Write Your Feedback <span class="optional-txt">(Optional)</span>
                </label>
                <textarea id="reviewText" v-model="reviewText" class="feedback-textarea" placeholder="Share details about your appointment, clarity of instructions, or general impressions to help future patients..."></textarea>
            </div>

            <!-- BUTTON ROW -->
            <div class="btn-row">
                <Link href="/patient/appointments" class="btn btn-outline">Cancel</Link>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>

    <!-- SUCCESS MODAL POPUP -->
    <div v-if="isSuccessModalOpen" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h3>Thank You for Your Review!</h3>
            <p>Your feedback has been submitted successfully and will help Dr. Emily Watson and MediFlow continuously improve care quality.</p>

            <button class="btn btn-primary" style="width:100%;" @click="returnToAppointments">Return to Appointments</button>
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

.review-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px 32px;
    box-shadow: var(--shadow-card);
    margin-bottom: 24px;
}
.ref-badge { font-family: var(--font-mono); font-size: 13px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); }
.review-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 4px 0 0 0; }

.review-form-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; box-shadow: var(--shadow-card); }
@media (max-width: 600px) { .review-form-card { padding: 24px; } }

.doc-review-banner { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; display: flex; gap: 18px; align-items: center; margin-bottom: 32px; }
.doc-review-banner img { width: 68px; height: 68px; border-radius: var(--radius-md); object-fit: cover; background: var(--cream-alt); }
.doc-review-info h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.doc-review-info p { font-size: 13.5px; color: var(--ink-muted); font-weight: 500; margin: 0; }
.doc-review-info span { font-size: 12px; color: var(--ink-muted); display: block; margin-top: 2px; }

.star-rating-section { text-align: center; padding: 28px; background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); margin-bottom: 32px; }
.star-rating-section h3 { font-size: 16px; font-weight: 800; color: var(--forest); margin: 0 0 6px 0; }
.star-rating-section p { font-size: 13.5px; color: var(--ink-muted); margin: 0 0 16px 0; }

.stars-row { display: flex; gap: 8px; justify-content: center; margin-bottom: 8px; }
.star-btn { font-size: 32px; color: var(--line); transition: color 150ms ease, transform 150ms ease; cursor: pointer; }
.star-btn.active { color: #F59E0B; }
.rating-label { font-size: 13px; font-weight: 700; color: var(--forest); height: 20px; display: block; }

.sub-ratings-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
@media (max-width: 680px) { .sub-ratings-grid { grid-template-columns: 1fr; } }

.sub-rating-item { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; text-align: center; }
.sub-rating-item label { font-size: 12.5px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 8px; }
.mini-stars { display: flex; gap: 4px; justify-content: center; }
.mini-star { font-size: 20px; color: var(--line); cursor: pointer; transition: color 150ms ease; }
.mini-star.active { color: #F59E0B; }

.form-group { margin-bottom: 28px; }
.form-label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 8px; }
.optional-txt { font-size: 12px; color: var(--ink-muted); font-weight: normal; }

.feedback-textarea { width: 100%; min-height: 120px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 16px; font-size: 14px; color: var(--ink); resize: vertical; }
.feedback-textarea:focus { outline: none; border-color: var(--forest); background: var(--card); }

.btn-row { display: flex; gap: 12px; justify-content: flex-end; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }

.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.6); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; max-width: 440px; width: 100%; text-align: center; box-shadow: var(--shadow-lift); }
.modal-icon { width: 56px; height: 56px; border-radius: 50%; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.modal-card h3 { font-size: 20px; font-weight: 800; color: var(--forest); margin: 0 0 8px 0; }
.modal-card p { font-size: 14px; color: var(--ink-muted); line-height: 1.5; margin: 0 0 24px 0; }
</style>
