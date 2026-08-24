<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorScheduleController extends Controller
{
    protected function getDoctor(): Doctor
    {
        $user = auth()->user();
        if ($user && $user->doctor) {
            return $user->doctor;
        }

        $doctor = Doctor::first();
        if (! $doctor) {
            abort(404, 'Doctor profile not found.');
        }

        return $doctor;
    }

    public function index(): Response
    {
        $doctor = $this->getDoctor();

        $dayNames = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->get()
            ->keyBy('day_of_week');

        $scheduleList = [];
        $slotDuration = '30';

        foreach ($dayNames as $dayNum => $dayName) {
            $sched = $schedules->get($dayNum);
            if ($sched) {
                $slotDuration = (string) $sched->slot_duration_minutes;
                $scheduleList[] = [
                    'day_num' => $dayNum,
                    'day' => $dayName,
                    'active' => (bool) $sched->is_active,
                    'startTime' => $sched->start_time ? substr($sched->start_time, 0, 5) : '08:30',
                    'endTime' => $sched->end_time ? substr($sched->end_time, 0, 5) : '17:00',
                    'maxPatients' => $dayNum <= 5 ? 16 : 8,
                ];
            } else {
                $isDefaultActive = $dayNum <= 5;
                $scheduleList[] = [
                    'day_num' => $dayNum,
                    'day' => $dayName,
                    'active' => $isDefaultActive,
                    'startTime' => $dayNum === 3 ? '08:30' : '08:30',
                    'endTime' => $dayNum === 3 ? '13:00' : '17:00',
                    'maxPatients' => $dayNum <= 5 ? 16 : 8,
                ];
            }
        }

        return Inertia::render('Doctor/Schedule/Index', [
            'schedule' => $scheduleList,
            'slotDuration' => $slotDuration,
            'bufferTime' => '5',
            'autoConfirm' => true,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $validated = $request->validate([
            'schedule' => 'required|array',
            'schedule.*.day_num' => 'required|integer|between:1,7',
            'schedule.*.active' => 'required|boolean',
            'schedule.*.startTime' => 'nullable|string',
            'schedule.*.endTime' => 'nullable|string',
            'slotDuration' => 'required|string',
        ]);

        $slotDuration = (int) $validated['slotDuration'];

        foreach ($validated['schedule'] as $item) {
            DoctorSchedule::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $item['day_num'],
                ],
                [
                    'start_time' => $item['active'] ? ($item['startTime'] ?? '08:30:00') : '08:30:00',
                    'end_time' => $item['active'] ? ($item['endTime'] ?? '17:00:00') : '17:00:00',
                    'slot_duration_minutes' => $slotDuration,
                    'is_active' => $item['active'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Recurring weekly schedule updated successfully.');
    }

    public function exceptions(): Response
    {
        $doctor = $this->getDoctor();

        $exceptions = DoctorScheduleException::where('doctor_id', $doctor->id)
            ->orderBy('exception_date', 'desc')
            ->get()
            ->map(function ($exc) {
                $dateFormatted = Carbon::parse($exc->exception_date)->format('M d, Y');

                return [
                    'id' => "EXC-{$exc->id}",
                    'db_id' => $exc->id,
                    'type' => ucfirst($exc->type ?? 'Vacation'),
                    'range' => $dateFormatted,
                    'days' => '1 Day',
                    'reason' => $exc->reason ?? 'Schedule exception.',
                    'status' => 'approved',
                    'statusLabel' => 'Approved',
                ];
            });

        return Inertia::render('Doctor/Schedule/Exceptions', [
            'exceptions' => $exceptions,
        ]);
    }

    public function storeException(Request $request): RedirectResponse
    {
        $doctor = $this->getDoctor();

        $validated = $request->validate([
            'exceptionType' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'reasonNotes' => 'required|string',
        ]);

        $start = Carbon::parse($validated['startDate']);
        $end = Carbon::parse($validated['endDate']);

        while ($start->lte($end)) {
            DoctorScheduleException::create([
                'doctor_id' => $doctor->id,
                'exception_date' => $start->format('Y-m-d'),
                'type' => $validated['exceptionType'],
                'reason' => $validated['reasonNotes'],
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
            ]);
            $start->addDay();
        }

        return redirect()->back()->with('success', 'Schedule exception request submitted.');
    }

    public function destroyException(string $id): RedirectResponse
    {
        $doctor = $this->getDoctor();

        DoctorScheduleException::where('doctor_id', $doctor->id)
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('id', str_replace('EXC-', '', $id));
            })
            ->delete();

        return redirect()->back()->with('success', 'Schedule exception cancelled.');
    }
}
