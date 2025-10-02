<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_members' => User::where('role', UserRole::MEMBER)->count(),
            'pending_members' => User::where('role', UserRole::MEMBER)
                ->where('status', UserStatus::PENDING)->count(),
            'approved_members' => User::where('role', UserRole::MEMBER)
                ->where('status', UserStatus::APPROVED)->count(),
            'total_staff' => User::where('role', UserRole::STAFF)->count(),
        ];

        $recent_members = User::where('role', UserRole::MEMBER)
            ->latest()
            ->take(10)
            ->get();

        return view('manager.reports.index', compact('stats', 'recent_members'));
    }
}