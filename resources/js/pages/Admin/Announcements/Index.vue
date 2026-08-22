<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

interface AnnouncementItem {
    id: string | number
    title: string
    date: string
    body: string
    target?: string
}

const props = defineProps<{
    announcements: AnnouncementItem[]
}>()

const showToast = ref(false)
const toastMessage = ref('')

const form = useForm({
    title: '',
    target: 'all',
    body: '',
})

function handlePublish() {
    form.post('/admin/announcements', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            toastMessage.value = 'Announcement broadcast published successfully!'
            showToast.value = true
            setTimeout(() => {
                showToast.value = false
            }, 3000)
        },
    })
}
</script>

<template>
    <Head title="Hospital Announcements & Broadcasts — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">Hospital Announcements & Broadcasts</h1>
            <p class="text-xs text-[var(--ink-muted)]">Send and manage system-wide notifications for patients and staff</p>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div v-if="showToast" class="toast-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span>{{ toastMessage }}</span>
    </div>

    <!-- CREATE ANNOUNCEMENT CARD -->
    <div class="card-shell mb-6">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            Publish New Broadcast Announcement
        </div>

        <form @submit.prevent="handlePublish">
            <div class="form-group mb-4">
                <label for="annTitle">Announcement Title <span>*</span></label>
                <input id="annTitle" v-model="form.title" type="text" class="form-control" required placeholder="e.g. Holiday Hours & Emergency Scheduling Notice" />
                <span v-if="form.errors.title" class="error-msg">{{ form.errors.title }}</span>
            </div>

            <div class="form-group mb-4">
                <label for="annTarget">Target Audience <span>*</span></label>
                <select id="annTarget" v-model="form.target" class="form-control" required>
                    <option value="all">All Users (Patients & Staff)</option>
                    <option value="patients">Patients Only</option>
                    <option value="doctors">Doctors & Staff Only</option>
                </select>
                <span v-if="form.errors.target" class="error-msg">{{ form.errors.target }}</span>
            </div>

            <div class="form-group mb-4">
                <label for="annContent">Message Body <span>*</span></label>
                <textarea id="annContent" v-model="form.body" class="form-control" required placeholder="Write broadcast message details here..."></textarea>
                <span v-if="form.errors.body" class="error-msg">{{ form.errors.body }}</span>
            </div>

            <button type="submit" class="btn-primary" :disabled="form.processing">
                <span>{{ form.processing ? 'Broadcasting...' : 'Publish Announcement' }}</span>
            </button>
        </form>
    </div>

    <!-- PREVIOUS ANNOUNCEMENTS HISTORY -->
    <div class="card-shell">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>
            Active & Recent Broadcast History
        </div>

        <div style="display: flex; flex-direction: column;">
            <div v-for="item in announcements" :key="item.id" class="announcement-item">
                <div class="announcement-header">
                    <div>
                        <b>{{ item.title }}</b>
                        <span v-if="item.target" class="target-tag">{{ item.target }}</span>
                    </div>
                    <span class="announcement-date">{{ item.date }}</span>
                </div>
                <div class="announcement-body">{{ item.body }}</div>
            </div>

            <div v-if="!announcements || announcements.length === 0" class="text-center py-8 text-xs text-[var(--ink-muted)]">
                No active announcements found.
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); }
.card-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }
.form-group label span { color: #DC2626; }
.form-control { width: 100%; height: 44px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.form-control:focus { border-color: var(--forest); background: var(--card); }
textarea.form-control { height: 120px; padding: 14px 16px; resize: vertical; }

.error-msg { font-size: 11.5px; color: #DC2626; font-weight: 600; margin-top: 4px; display: block; }

.btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 24px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 14px; font-weight: 700; box-shadow: var(--shadow-sm); transition: background-color 150ms ease; cursor: pointer; border: none; }
.btn-primary:hover { background: var(--forest-2); }

.announcement-item { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 18px 22px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.announcement-item:last-child { margin-bottom: 0; }
.announcement-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
.announcement-header b { font-size: 15px; font-weight: 700; color: var(--forest); }
.target-tag { font-family: var(--font-mono); font-size: 11px; font-weight: 700; background: var(--lime-soft); color: var(--lime-text); padding: 2px 8px; border-radius: 999px; margin-left: 10px; }
.announcement-date { font-family: var(--font-mono); font-size: 12px; color: var(--ink-muted); }
.announcement-body { font-size: 13.5px; color: var(--ink); line-height: 1.45; }

.toast-notice { position: fixed; bottom: 24px; right: 24px; background: var(--forest); color: #fff; padding: 14px 22px; border-radius: var(--radius-md); font-size: 14px; font-weight: 600; box-shadow: var(--shadow-lift); display: flex; align-items: center; gap: 10px; z-index: 100; animation: slideUp 200ms ease-out; }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
