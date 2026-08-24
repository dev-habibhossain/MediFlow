<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const selectedDateStr = ref('Monday, Aug 10')
const selectedTimeStr = ref('11:00 AM')

const morningSlots = ['09:00 AM', '09:30 AM', '11:00 AM', '11:30 AM']
const afternoonSlots = ['02:00 PM', '02:30 PM', '03:30 PM', '04:00 PM']

function selectDate(date: string) {
    selectedDateStr.value = date
}

function selectTime(time: string) {
    selectedTimeStr.value = time
}

function handleConfirmReschedule() {
    alert(`Appointment #MDF-101 has been rescheduled to ${selectedDateStr.value} at ${selectedTimeStr.value}.`)
    router.visit('/patient/appointments/101')
}
</script>

<template>
    <Head title="Reschedule Appointment #MDF-101" />

    <!-- TOP NAV ROW -->
    <div class="top-nav-row">
        <Link href="/patient/appointments/101" class="back-btn">← Back to Appointment Detail</Link>
    </div>

    <!-- RESCHEDULE HEADER CARD -->
    <div class="reschedule-header-card">
        <div>
            <span class="ref-badge">Rescheduling Visit #MDF-101</span>
            <h1>Select New Appointment Date & Time</h1>
        </div>
    </div>

    <!-- MAIN RESCHEDULE GRID -->
    <div class="reschedule-grid">
        <!-- LEFT: INTERACTIVE SLOT PICKER -->
        <div class="picker-card">
            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                1. Select New Date
            </div>

            <!-- CALENDAR SELECTOR -->
            <div class="calendar-box">
                <div class="calendar-header">
                    <h3>August 2026</h3>
                    <div style="display:flex; gap:6px;">
                        <button class="cal-nav-btn" disabled><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M15 18l-6-6 6-6"/></svg></button>
                        <button class="cal-nav-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 18l6-6-6-6"/></svg></button>
                    </div>
                </div>

                <div class="days-row">
                    <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                </div>

                <div class="dates-grid">
                    <button class="date-btn" disabled>2</button>
                    <button class="date-btn" disabled>3</button>
                    <button class="date-btn" disabled>4</button>
                    <button class="date-btn" disabled>5</button>
                    <button class="date-btn" disabled>6</button>
                    <button class="date-btn" disabled>7<span class="sub-txt">Current</span></button>

                    <button class="date-btn" :class="{ active: selectedDateStr === 'Saturday, Aug 8' }" @click="selectDate('Saturday, Aug 8')">
                        8<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Sunday, Aug 9' }" @click="selectDate('Sunday, Aug 9')">
                        9<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Monday, Aug 10' }" @click="selectDate('Monday, Aug 10')">
                        10<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Tuesday, Aug 11' }" @click="selectDate('Tuesday, Aug 11')">
                        11<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Wednesday, Aug 12' }" @click="selectDate('Wednesday, Aug 12')">
                        12<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Thursday, Aug 13' }" @click="selectDate('Thursday, Aug 13')">
                        13<span class="dot"></span>
                    </button>
                    <button class="date-btn" :class="{ active: selectedDateStr === 'Friday, Aug 14' }" @click="selectDate('Friday, Aug 14')">
                        14<span class="dot"></span>
                    </button>
                </div>
            </div>

            <div class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                2. Choose Available Time Slot
            </div>

            <!-- TIME SLOTS -->
            <div>
                <div class="slot-group-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2"/>
                    </svg>
                    Morning Slots
                </div>
                <div class="slots-grid">
                    <button class="slot-btn" disabled>09:00 AM</button>
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '09:30 AM' }" @click="selectTime('09:30 AM')">09:30 AM</button>
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '11:00 AM' }" @click="selectTime('11:00 AM')">11:00 AM</button>
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '11:30 AM' }" @click="selectTime('11:30 AM')">11:30 AM</button>
                </div>

                <div class="slot-group-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                    Afternoon Slots
                </div>
                <div class="slots-grid">
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '02:00 PM' }" @click="selectTime('02:00 PM')">02:00 PM</button>
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '02:30 PM' }" @click="selectTime('02:30 PM')">02:30 PM</button>
                    <button class="slot-btn" :class="{ active: selectedTimeStr === '03:30 PM' }" @click="selectTime('03:30 PM')">03:30 PM</button>
                    <button class="slot-btn" disabled>04:00 PM</button>
                </div>
            </div>
        </div>

        <!-- RIGHT: SIDEBAR SUMMARY & CONFIRMATION -->
        <div class="sidebar-summary-card">
            <div class="doc-mini-profile">
                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=150" alt="Dr. Sarah Jenkins" class="doc-avatar" />
                <div class="doc-info">
                    <h3>Dr. Sarah Jenkins</h3>
                    <p>Cardiology Specialist</p>
                </div>
            </div>

            <div class="comparison-box">
                <div class="comp-item">
                    <label>Current Appointment</label>
                    <span class="old-schedule">Friday, Aug 7 at 10:00 AM</span>
                </div>
                <div class="comp-item new">
                    <label>New Selected Schedule</label>
                    <span>{{ selectedDateStr }} at {{ selectedTimeStr }}</span>
                </div>
            </div>

            <div class="btn-col">
                <button class="btn btn-primary" @click="handleConfirmReschedule">
                    Confirm Reschedule
                </button>
                <Link href="/patient/appointments/101" class="btn btn-outline">
                    Cancel & Keep Original
                </Link>
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

.reschedule-header-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    padding: 24px 32px;
    box-shadow: var(--shadow-card);
    margin-bottom: 24px;
}
.ref-badge { font-family: var(--font-mono); font-size: 12.5px; font-weight: 700; background: var(--cream); border: 1px solid var(--line); color: var(--forest); padding: 4px 10px; border-radius: var(--radius-sm); display: inline-block; margin-bottom: 4px; }
.reschedule-header-card h1 { font-size: 22px; font-weight: 800; color: var(--forest); letter-spacing: -0.01em; margin: 0; }

.reschedule-grid { display: grid; grid-template-columns: 1fr 360px; gap: 24px; align-items: start; }
@media (max-width: 1024px) { .reschedule-grid { grid-template-columns: 1fr; } }

.picker-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); }
.section-title { font-size: 16px; font-weight: 800; color: var(--forest); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.section-title svg { width: 18px; height: 18px; color: var(--forest); }

.calendar-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 20px; margin-bottom: 28px; }
.calendar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.calendar-header h3 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0; }
.cal-nav-btn { width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--line); background: var(--card); display: flex; align-items: center; justify-content: center; cursor: pointer; }

.days-row { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center; font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: var(--ink-muted); margin-bottom: 8px; }
.dates-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }

.date-btn { height: 42px; border-radius: var(--radius-sm); border: 1px solid transparent; background: var(--card); font-size: 13.5px; font-weight: 600; color: var(--ink); display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; cursor: pointer; }
.date-btn:hover:not(:disabled) { border-color: var(--forest); }
.date-btn.active { background: var(--forest); color: #fff; border-color: var(--forest); }
.date-btn:disabled { opacity: 0.3; cursor: not-allowed; background: transparent; }
.sub-txt { font-size: 9px; color: var(--ink-muted); }
.date-btn .dot { width: 4px; height: 4px; border-radius: 50%; background: var(--lime); position: absolute; bottom: 4px; }

.slot-group-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); margin: 16px 0 10px; display: flex; align-items: center; gap: 6px; }
.slot-group-title svg { width: 14px; height: 14px; }

.slots-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
@media (max-width: 500px) { .slots-grid { grid-template-columns: repeat(2, 1fr); } }

.slot-btn { height: 42px; border-radius: var(--radius-sm); border: 1px solid var(--line); background: var(--cream); font-family: var(--font-mono); font-size: 13px; font-weight: 600; color: var(--ink); display: flex; align-items: center; justify-content: center; cursor: pointer; }
.slot-btn:hover:not(:disabled) { border-color: var(--forest); background: var(--card); }
.slot-btn.active { background: var(--lime); color: var(--lime-text); border-color: #c4dc3c; font-weight: 700; box-shadow: var(--shadow-sm); }
.slot-btn:disabled { opacity: 0.35; cursor: not-allowed; background: transparent; text-decoration: line-through; }

.sidebar-summary-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 24px; box-shadow: var(--shadow-card); display: flex; flex-direction: column; gap: 20px; position: sticky; top: 92px; }

.doc-mini-profile { display: flex; gap: 14px; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--line); }
.doc-avatar { width: 56px; height: 56px; border-radius: var(--radius-md); object-fit: cover; background: var(--cream-alt); }
.doc-info h3 { font-size: 15px; font-weight: 800; color: var(--forest); margin: 0 0 2px 0; }
.doc-info p { font-size: 12.5px; color: var(--ink-muted); font-weight: 500; margin: 0; }

.comparison-box { background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-md); padding: 16px; display: flex; flex-direction: column; gap: 12px; }
.comp-item label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); display: block; margin-bottom: 2px; }
.comp-item span { font-size: 13.5px; font-weight: 700; color: var(--ink); display: block; }
.old-schedule { text-decoration: line-through; opacity: 0.6; }
.comp-item.new span { color: #15803D; }

.btn-col { display: flex; flex-direction: column; gap: 10px; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 24px; border-radius: 999px; font-size: 14.5px; font-weight: 600; text-decoration: none; transition: all 150ms ease; width: 100%; cursor: pointer; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); border: none; }
.btn-primary:hover { background: var(--forest-2); }
.btn-outline { background: transparent; color: var(--ink); border: 1.5px solid var(--line); }
.btn-outline:hover { border-color: var(--forest); background: var(--cream); }
</style>
