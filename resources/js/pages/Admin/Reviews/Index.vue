<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

interface ReviewItem {
    id: number
    patient_name: string
    doctor_info: string
    rating: string
    comment: string
    visible: boolean
}

const reviews = ref<ReviewItem[]>([
    {
        id: 1,
        patient_name: 'Habib Hossain',
        doctor_info: 'Dr. Sarah Jenkins (Cardiology)',
        rating: '5.0',
        comment: 'Dr. Jenkins provided exceptional diagnostic care during my consultation.',
        visible: true,
    },
    {
        id: 2,
        patient_name: 'Tanjila Ahmed',
        doctor_info: 'Dr. Marcus Vance (Neurology)',
        rating: '4.8',
        comment: 'Very thorough consultation. The online booking system made scheduling easy.',
        visible: true,
    },
])

const toastMsg = ref('')
const showToast = ref(false)

function triggerToast(msg: string) {
    toastMsg.value = msg
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 2000)
}

function toggleReview(review: ReviewItem) {
    review.visible = !review.visible
    if (review.visible) {
        triggerToast('Review is now visible on frontend.')
    } else {
        triggerToast('Review hidden from public view.')
    }
}

function deleteReview(id: number) {
    if (confirm('Permanently delete this review?')) {
        reviews.value = reviews.value.filter((r) => r.id !== id)
        triggerToast('Review deleted successfully.')
    }
}
</script>

<template>
    <Head title="Reviews Moderation — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Reviews Moderation</h1>
            <p class="text-xs text-[var(--ink-muted)]">Show or hide submitted patient reviews across the platform</p>
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Reviewer & Doctor</th>
                        <th>Rating</th>
                        <th>Feedback Comment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="review in reviews" :key="review.id">
                        <td>
                            <div class="reviewer-meta">
                                <b>{{ review.patient_name }}</b>
                                <span>{{ review.doctor_info }}</span>
                            </div>
                        </td>
                        <td><span class="star-rating">★ {{ review.rating }}</span></td>
                        <td style="max-width: 320px; color: var(--ink-muted);">"{{ review.comment }}"</td>
                        <td>
                            <span class="status-badge" :class="review.visible ? 'status-visible' : 'status-hidden'">
                                ● {{ review.visible ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <button class="btn-action" @click="toggleReview(review)">Toggle Hide</button>
                                <button class="btn-action btn-delete" @click="deleteReview(review.id)">Delete</button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="reviews.length === 0">
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No reviews found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOAST -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12" /></svg>
        <span>{{ toastMsg }}</span>
    </div>
</template>

<style>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.reviewer-meta b { display: block; font-size: 14px; font-weight: 700; color: var(--forest); }
.reviewer-meta span { display: block; font-size: 12px; color: var(--ink-muted); }

.star-rating { color: #D97706; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 4px; }

.status-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-visible { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-hidden { background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; }

.action-btn-group { display: flex; gap: 8px; }
.btn-action { height: 34px; padding: 0 14px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); font-size: 12.5px; font-weight: 600; color: var(--forest); cursor: pointer; transition: all 150ms ease; }
.btn-action:hover { background: var(--forest); color: #fff; border-color: var(--forest); }
.btn-delete { color: #DC2626; border-color: #FCA5A5; background: #FEF2F2; }
.btn-delete:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
