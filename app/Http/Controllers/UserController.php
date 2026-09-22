<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Login page
     */
    public function login()
    {
        if (Auth::check()) {
           // return redirect()->route('dashboard');
        }

        return view('login');
    }

    /**
     * Process login
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (
            $credentials['email'] === env('ADMIN_EMAIL') &&
            $credentials['password'] === env('ADMIN_PASSWORD')
        ) {
            $request->session()->regenerate();

            $request->session()->put('admin_logged_in', true);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email หรือ Password ไม่ถูกต้อง',
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'virtual_admin',
            'admin_name',
            'admin_email',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Dashboard
     */
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $users = User::orderBy('id', 'desc')->get();

        return view('dashboard', compact('users'));
    }

    /**
     * Create user page
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'เพิ่ม User เรียบร้อยแล้ว');
    }

    /**
     * Edit user page
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // ถ้าไม่ได้กรอก password ให้ใช้ password เดิม
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'แก้ไข User เรียบร้อยแล้ว');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // ป้องกันไม่ให้ลบตัวเอง
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'ไม่สามารถลบ User ที่กำลัง Login อยู่ได้');
        }

        $user->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'ลบ User เรียบร้อยแล้ว');
    }
}