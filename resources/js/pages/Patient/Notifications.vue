<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const currentFilter = ref('all')

const notifications = ref([
    {
        id: 1,
        title: 'Appointment Confirmed with Dr. Sarah Jenkins',
        time: '2 hours ago',
        description: 'Your cardiology consultation #MDF-101 has been confirmed for Friday, Aug 7, 2026 at 10:00 AM at Harbor Ave Clinic.',
        actionUrl: '/patient/appointments/101',
        actionText: 'View Appointment Details →',
        category: 'appointment',
        read: false,
    },
    {
        id: 2,
        title: 'New Lab Results Uploaded',
        time: 'Yesterday at 04:30 PM',
        description: 'Comprehensive Blood Count (CBC) & Lipid Panel test results from June 28 are now available for review.',
        actionUrl: '/patient/medical-records/302',
        actionText: 'View Lab Report →',
        category: 'lab',
        read: false,
    },
    {
        id: 3,
        title: 'Prescription #RX-401 Issued',
        time: 'July 14, 2026',
        description: 'Dr. Sarah Jenkins prescribed Amlodipine 5mg and Atorvastatin 20mg with 2 authorized refills.',
        actionUrl: '/patient/prescriptions/401',
        actionText: 'View Digital Rx →',
        category: 'prescription',
        read: true,
    },
    {
        id: 4,
        title: 'Security Alert: New Sign-In Detected',
        time: 'July 01, 2026',
        description: 'Your account was logged in from Linux (Chrome) in Bangladesh. If this wasn\'t you, change your password immediately.',
        actionUrl: '/patient/settings/security',
        actionText: 'Security Settings →',
        category: 'system',
        read: true,
    },
])

const unreadCount = computed(() => notifications.value.filter((n) => !n.read).length)

const filteredNotifications = computed(() => {
    return notifications.value.filter((n) => {
        if (currentFilter.value === 'all') return true
        if (currentFilter.value === 'unread') return !n.read
        return n.category === currentFilter.value
    })
})

function markAllRead() {
    notifications.value.forEach((n) => (n.read = true))
}

function dismissNotif(id: number) {
    notifications.value = notifications.value.filter((n) => n.id !== id)
}
</script>

<template>
    <Head title="Notifications" />

    <!-- TOP BAR MARK ALL -->
    <div class="top-action-bar">
        <button class="btn-mark-all" @click="markAllRead">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Mark all as read
        </button>
    </div>

    <!-- FILTER TABS -->
    <div class="filter-row">
        <div class="tab-group">
            <button class="tab-btn" :class="{ active: currentFilter === 'all' }" @click="currentFilter = 'all'">
                All Notifications <span class="tab-count">{{ notifications.length }}</span>
            </button>
            <button class="tab-btn" :class="{ active: currentFilter === 'unread' }" @click="currentFilter = 'unread'">
                Unread <span class="tab-count">{{ unreadCount }}</span>
            </button>
            <button class="tab-btn" :class="{ active: currentFilter === 'appointment' }" @click="currentFilter = 'appointment'">
                Appointments
            </button>
            <button class="tab-btn" :class="{ active: currentFilter === 'lab' }" @click="currentFilter = 'lab'">
                Lab & Medical
            </button>
            <button class="tab-btn" :class="{ active: currentFilter === 'system' }" @click="currentFilter = 'system'">
                System
            </button>
        </div>
    </div>

    <!-- NOTIFICATIONS FEED -->
    <div class="notification-feed">
        <div v-for="notif in filteredNotifications" :key="notif.id" class="notif-card" :class="{ unread: !notif.read }">
            <div class="notif-icon" :class="notif.category">
                <svg v-if="notif.category === 'appointment'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <svg v-else-if="notif.category === 'lab'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                </svg>
                <svg v-else-if="notif.category === 'prescription'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M10.5 20.4l-6.9-6.9c-.8-.8-.8-2 0-2.8l11.3-11.3c.8-.8 2-.8 2.8 0l6.9 6.9c.8.8.8 2 0 2.8l-11.3 11.3c-.8.8-2 .8-2.8 0z"/>
                </svg>
                <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>

            <div class="notif-body">
                <div class="notif-header">
                    <div class="notif-title">
                        <span v-if="!notif.read" class="unread-dot"></span>
                        {{ notif.title }}
                    </div>
                    <span class="notif-time">{{ notif.time }}</span>
                </div>
                <p class="notif-desc">{{ notif.description }}</p>
                <Link :href="notif.actionUrl" class="notif-action">{{ notif.actionText }}</Link>
            </div>

            <button class="btn-dismiss" title="Dismiss" @click="dismissNotif(notif.id)">✕</button>
        </div>
    </div>
</template>

<style scoped>
.top-action-bar { display: flex; justify-content: flex-end; margin-bottom: 16px; }
.btn-mark-all { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--forest); background: var(--cream); border: 1px solid var(--line); padding: 8px 16px; border-radius: 999px; transition: all 150ms ease; cursor: pointer; }
.btn-mark-all:hover { background: var(--card); border-color: var(--forest); }

.filter-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }

.tab-group { display: flex; background: var(--card); border: 1px solid var(--line); border-radius: 999px; padding: 4px; gap: 4px; box-shadow: var(--shadow-sm); flex-wrap: wrap; }
.tab-btn { padding: 8px 18px; border-radius: 999px; font-size: 13px; font-weight: 600; color: var(--ink-muted); transition: all 150ms ease; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
.tab-btn:hover { color: var(--ink); }
.tab-btn.active { background: var(--forest); color: #fff; }
.tab-count { font-family: var(--font-mono); font-size: 11px; padding: 2px 6px; border-radius: 999px; background: rgba(22,24,15,0.08); color: inherit; }
.tab-btn.active .tab-count { background: rgba(255,255,255,0.2); color: #fff; }

.notification-feed { display: flex; flex-direction: column; gap: 14px; }

.notif-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px 24px; box-shadow: var(--shadow-sm); transition: all 150ms ease; display: flex; align-items: flex-start; gap: 16px; position: relative; }
.notif-card:hover { box-shadow: var(--shadow-card); transform: translateY(-1px); }
.notif-card.unread { background: #FCFAF2; border-color: rgba(22,48,31,0.2); }
.notif-card.unread::before { content: ""; position: absolute; left: 0; top: 16px; bottom: 16px; width: 4px; background: var(--forest); border-radius: 0 4px 4px 0; }

.notif-icon { width: 42px; height: 42px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.notif-icon.appointment { background: #DCFCE7; color: #15803D; }
.notif-icon.lab { background: #E0F2FE; color: #0369A1; }
.notif-icon.prescription { background: #FEF3C7; color: #B45309; }
.notif-icon.system { background: var(--cream-alt); color: var(--ink); }

.notif-body { flex: 1; }
.notif-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; flex-wrap: wrap; gap: 8px; }
.notif-title { font-size: 15px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 8px; }
.unread-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--forest); display: inline-block; }
.notif-time { font-family: var(--font-mono); font-size: 12px; color: var(--ink-muted); }

.notif-desc { font-size: 13.5px; color: var(--ink-muted); line-height: 1.5; margin: 0 0 10px 0; }

.notif-action { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 700; color: var(--forest); text-decoration: underline; }
.notif-action:hover { color: var(--forest-2); }

.btn-dismiss { width: 28px; height: 28px; border-radius: 50%; color: var(--ink-muted); display: inline-flex; align-items: center; justify-content: center; transition: all 150ms ease; opacity: 0.6; cursor: pointer; border: none; background: transparent; }
.btn-dismiss:hover { opacity: 1; background: var(--cream); color: #DC2626; }
</style>
