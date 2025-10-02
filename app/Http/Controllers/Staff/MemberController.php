<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', UserRole::MEMBER);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(15);
        
        return view('staff.members.index', compact('members'));
    }

    public function show(User $member)
    {
        if (!$member->isMember()) {
            abort(404);
        }

        return view('staff.members.show', compact('member'));
    }

    public function approve(User $member)
    {
        if (!$member->isMember()) {
            abort(404);
        }

        $member->update([
            'status' => UserStatus::APPROVED,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'อนุมัติสมาชิกสำเร็จ');
    }

    public function reject(User $member)
    {
        if (!$member->isMember()) {
            abort(404);
        }

        $member->update([
            'status' => UserStatus::REJECTED,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'ปฏิเสธสมาชิกสำเร็จ');
    }

    public function edit(User $member)
    {
        if (!$member->isMember()) {
            abort(404);
        }

        return view('staff.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        if (!$member->isMember()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$member->id,
        ]);

        $member->update($validated);

        return redirect()->route('staff.members.index')
            ->with('success', 'อัพเดทข้อมูลสมาชิกสำเร็จ');
    }
}