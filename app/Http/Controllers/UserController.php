<?php

namespace App\Http\Controllers;

use App\User;
use App\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $users = User::with('bidang')->paginate(10);
        return view('admin_page.users.index', compact('users'));
    }

    public function create()
    {
        $bidangs = Bidang::all();
        $roles = ['super_admin', 'admin_barang', 'user'];
        return view('admin_page.users.create', compact('bidangs', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['super_admin', 'admin_barang', 'user'])],
            'bidang_id' => 'required|exists:bidang,id',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $bidangs = Bidang::all();
        $roles = ['super_admin', 'admin_barang', 'user'];
        return view('admin_page.users.edit', compact('user', 'bidangs', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['super_admin', 'admin_barang', 'user'])],
            'bidang_id' => 'required|exists:bidang,id',
            'no_hp' => 'nullable|string|max:15',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $user->delete();

        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}