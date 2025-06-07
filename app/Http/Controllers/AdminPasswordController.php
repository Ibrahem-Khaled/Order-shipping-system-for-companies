<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AdminPasswordController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id()) // استبعاد المستخدم الحالي
            ->orderBy('name')
            ->paginate(10);

        return view('reset-password', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ]);

        User::whereIn('id', $request->users)->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'تم تحديث كلمات المرور للمستخدمين المحددين');
    }
}
