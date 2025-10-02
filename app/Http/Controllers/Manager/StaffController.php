<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = User::where('role', UserRole::STAFF)->latest()->paginate(15);
        return view('manager.staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('manager.staffs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = UserRole::STAFF;
        $validated['status'] = 'approved';
        $validated['approved_at'] = now();
        $validated['approved_by'] = auth()->id();

        User::create($validated);

        return redirect()->route('manager.staffs.index')
            ->with('success', 'สร้างเจ้าหน้าที่สำเร็จ');
    }

    public function edit(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        return view('manager.staffs.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$staff->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('manager.staffs.index')
            ->with('success', 'อัพเดทเจ้าหน้าที่สำเร็จ');
    }

    public function destroy(User $staff)
    {
        if (!$staff->isStaff()) {
            abort(404);
        }

        $staff->delete();

        return redirect()->route('manager.staffs.index')
            ->with('success', 'ลบเจ้าหน้าที่สำเร็จ');
    }
}