<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface AuditLogItem {
    id: number | string
    timestamp: string
    actor_name: string
    actor_email: string
    event: string
    target: string
    ip: string
}

const props = withDefaults(
    defineProps<{
        logs?: AuditLogItem[]
    }>(),
    {
        logs: () => [
            {
                id: 1,
                timestamp: 'Aug 7, 2026 · 02:05 AM',
                actor_name: 'System Admin',
                actor_email: 'admin@mediflow.com',
                event: 'DOCTOR_ONBOARDED',
                target: 'Registered Dr. Sarah Jenkins (#901)',
                ip: '192.168.1.10',
            },
            {
                id: 2,
                timestamp: 'Aug 7, 2026 · 01:40 AM',
                actor_name: 'Habib Hossain',
                actor_email: 'habib@example.com',
                event: 'APPOINTMENT_BOOKED',
                target: 'Booked visit #MDF-101 with Dr. Jenkins',
                ip: '103.142.5.12',
            },
            {
                id: 3,
                timestamp: 'Aug 6, 2026 · 11:15 PM',
                actor_name: 'System Admin',
                actor_email: 'admin@mediflow.com',
                event: 'DEPARTMENT_CREATED',
                target: 'Created Cardiology unit (CARD)',
                ip: '192.168.1.10',
            },
        ],
    }
)

const searchQuery = ref<string>('')

const filteredLogs = computed(() => {
    const q = searchQuery.value.toLowerCase().trim()
    if (!q) return props.logs
    return props.logs.filter(
        (log) =>
            log.actor_name.toLowerCase().includes(q) ||
            log.actor_email.toLowerCase().includes(q) ||
            log.event.toLowerCase().includes(q) ||
            log.target.toLowerCase().includes(q) ||
            log.ip.toLowerCase().includes(q)
    )
})
</script>

<template>
    <Head title="System Activity Audit Logs — Admin Portal" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-[var(--forest)]">System Activity Audit Logs</h1>
            <p class="text-xs text-[var(--ink-muted)]">Chronological record of administrative actions, user logins, and overrides</p>
        </div>
    </div>

    <!-- TOOLBAR & FILTERS -->
    <div class="toolbar-row">
        <div style="font-size: 13.5px; font-weight: 700; color: var(--forest);">
            Showing recent system audit events (Live Stream)
        </div>

        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input v-model="searchQuery" type="text" class="search-input" placeholder="Search user, action, or IP..." />
        </div>
    </div>

    <!-- DATA TABLE CARD -->
    <div class="card-shell">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Actor / User</th>
                        <th>Action Event</th>
                        <th>Target Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="log in filteredLogs" :key="log.id">
                        <td style="font-family: var(--font-mono); font-size: 12.5px; color: var(--ink-muted);">
                            {{ log.timestamp }}
                        </td>
                        <td>
                            <b>{{ log.actor_name }}</b>
                            <span style="display: block; font-size: 11.5px; color: var(--ink-muted);">{{ log.actor_email }}</span>
                        </td>
                        <td><span class="action-badge">{{ log.event }}</span></td>
                        <td>{{ log.target }}</td>
                        <td><span class="ip-tag">{{ log.ip }}</span></td>
                    </tr>

                    <tr v-if="filteredLogs.length === 0">
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--ink-muted);">
                            No audit logs found matching your search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style>
.toolbar-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 24px; }

.search-box { position: relative; width: 300px; }
.search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--ink-muted); }
.search-input { width: 100%; height: 40px; border-radius: 999px; border: 1px solid var(--line); background: var(--card); padding: 0 16px 0 40px; font-size: 13.5px; color: var(--ink); outline: none; transition: border-color 150ms ease; }
.search-input:focus { border-color: var(--forest); }

.card-shell { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); box-shadow: var(--shadow-card); overflow: hidden; }
.table-responsive { width: 100%; overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; text-align: left; }
.data-table th { background: var(--cream); padding: 14px 24px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); border-bottom: 1px solid var(--line); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); font-size: 13.5px; vertical-align: middle; }

.action-badge { font-family: var(--font-mono); font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); color: var(--forest); display: inline-block; }
.ip-tag { font-family: var(--font-mono); font-size: 12px; color: var(--ink-muted); }
</style>
