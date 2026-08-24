<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
    exceptions?: Array<{
        id: string
        db_id?: number
        type: string
        range: string
        days: string
        reason: string
        status: string
        statusLabel: string
    }>
}>()

const form = useForm({
    exceptionType: 'vacation',
    startDate: new Date().toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0],
    reasonNotes: '',
})

const exceptionList = computed(() => props.exceptions ?? [])

function addException() {
    form.post('/doctor/schedule/exceptions', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('reasonNotes')
        },
    })
}

function cancelException(id: string) {
    router.delete(`/doctor/schedule/exceptions/${id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Schedule Exceptions & Leave" />

    <!-- NAV BAR ROW -->
    <div class="nav-bar-row">
        <Link href="/doctor/schedule" class="back-link">
            ← Back to Weekly Schedule
        </Link>
        <span class="context-tag">Schedule Override & Leave Portal</span>
    </div>

    <!-- ADD EXCEPTION FORM CARD -->
    <div class="form-card" style="margin-bottom: 28px;">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><line x1="9" y1="14" x2="15" y2="14"/>
                </svg>
                Request Schedule Exception / Leave
            </h3>
        </div>

        <form @submit.prevent="addException">
            <div class="form-grid">
                <div class="form-group">
                    <label>Exception Category <span>*</span></label>
                    <select v-model="form.exceptionType" class="form-control" required>
                        <option value="vacation">Planned Vacation / Leave</option>
                        <option value="conference">Medical Conference / Seminar</option>
                        <option value="emergency">Emergency Absence</option>
                        <option value="overtime">Overtime / Extra Duty Shift</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Start Date <span>*</span></label>
                    <input v-model="form.startDate" type="date" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>End Date <span>*</span></label>
                    <input v-model="form.endDate" type="date" class="form-control" required />
                </div>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label>Reason / Clinical Coverage Details <span>*</span></label>
                <input v-model="form.reasonNotes" type="text" class="form-control" placeholder="Specify reason and covering physician if applicable..." required />
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" :disabled="form.processing">+ Submit Schedule Exception Request</button>
            </div>
        </form>
    </div>

    <!-- EXISTING EXCEPTIONS TABLE -->
    <div class="table-card">
        <div class="card-header-bar">
            <h4>Active Schedule Exceptions & Leave Log</h4>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ref Code</th>
                        <th>Exception Category</th>
                        <th>Date Range & Duration</th>
                        <th>Reason / Notes</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="exceptionList.length === 0">
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--ink-muted); font-size: 13.5px;">
                            No schedule exceptions on record. Submit a request above.
                        </td>
                    </tr>
                    <tr v-for="exc in exceptionList" :key="exc.id">
                        <td><b class="mono-id">{{ exc.id }}</b></td>
                        <td><b>{{ exc.type }}</b></td>
                        <td>
                            <div class="range-cell">
                                <b>{{ exc.range }}</b>
                                <span>{{ exc.days }}</span>
                            </div>
                        </td>
                        <td><span class="reason-text">{{ exc.reason }}</span></td>
                        <td>
                            <span class="status-pill-badge" :class="exc.status">
                                {{ exc.statusLabel }}
                            </span>
                        </td>
                        <td>
                            <button class="btn-sm btn-outline-danger" @click="cancelException(exc.id)">Cancel</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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

.form-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 28px;
    box-shadow: var(--shadow-card);
}

.card-header {
    border-bottom: 1px solid var(--line);
    padding-bottom: 14px;
    margin-bottom: 20px;
}
.card-header h3 { font-size: 17px; font-weight: 800; color: var(--forest); display: flex; align-items: center; gap: 10px; }

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (max-width: 800px) { .form-grid { grid-template-columns: 1fr; } }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-group label { font-size: 13px; font-weight: 700; color: var(--ink); }
.form-group label span { color: #DC2626; }

.form-control {
    height: 42px;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--cream);
    padding: 0 14px;
    font-size: 13.5px;
    color: var(--ink);
}

.form-actions { display: flex; justify-content: flex-end; margin-top: 20px; }

.table-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
}

.card-header-bar {
    padding: 16px 24px;
    border-bottom: 1px solid var(--line);
}
.card-header-bar h4 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0; }

.table-responsive { width: 100%; overflow-x: auto; }

.data-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

.data-table th {
    background: var(--cream);
    padding: 12px 20px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--ink-muted);
    border-bottom: 1px solid var(--line);
}

.data-table td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
    font-size: 13.5px;
    vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }

.mono-id { font-family: var(--font-mono); font-size: 12.5px; color: var(--forest); }
.range-cell b { display: block; font-size: 13px; color: var(--forest); }
.range-cell span { display: block; font-size: 11.5px; color: var(--ink-muted); }
.reason-text { font-size: 13px; color: var(--ink); }

.status-pill-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
.status-pill-badge.approved { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.status-pill-badge.pending { background: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; }

.btn { display: inline-flex; align-items: center; justify-content: center; height: 42px; padding: 0 20px; border-radius: 999px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; }
.btn-primary { background: var(--forest); color: white; border: 1.5px solid var(--forest); }
.btn-primary:hover { background: var(--forest-2); }

.btn-sm { display: inline-flex; align-items: center; justify-content: center; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; cursor: pointer; }
.btn-outline-danger { background: transparent; color: #DC2626; border: 1px solid #FCA5A5; }
.btn-outline-danger:hover { background: #FEE2E2; }
</style>
