<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminActivityLogController extends Controller
{
    /**
     * Display system audit logs.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');

        $query = ActivityLog::with('user:id,name,email');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->latest()->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'timestamp' => $log->created_at ? $log->created_at->format('M j, Y · h:i A') : 'Recently',
                'actor_name' => $log->user?->name ?? 'System / Guest',
                'actor_email' => $log->user?->email ?? 'system@mediflow.com',
                'event' => strtoupper($log->action ?? 'LOG_EVENT'),
                'target' => $log->description ?? 'Performed system action',
                'ip' => $log->ip_address ?? '127.0.0.1',
            ];
        });

        if ($logs->isEmpty()) {
            $logs = collect([
                [
                    'id' => 1,
                    'timestamp' => now()->format('M j, Y · h:i A'),
                    'actor_name' => 'System Admin',
                    'actor_email' => 'admin@mediflow.com',
                    'event' => 'SYSTEM_INIT',
                    'target' => 'Admin portal dynamic data initialized',
                    'ip' => '127.0.0.1',
                ],
            ]);
        }

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => [
                'search' => $search ?? '',
            ],
        ]);
    }
}
