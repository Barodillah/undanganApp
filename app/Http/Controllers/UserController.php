<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function authorizeSuperAdmin()
    {
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();

        $users = User::with('role')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // ⚠️ DIKUNCI
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User Admin berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }


    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $this->authorizeSuperAdmin();

        if ($user->id === auth()->id()) {
            abort(403);
        }

        if ($user->role_id != 2) {
            abort(403, 'User ini tidak boleh dihapus');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function show(User $user)
    {
        // Hanya Super Admin (role_id = 1)
        abort_unless(auth()->user()->role_id == 1, 403);

        $user->load('role');

        return view('admin.users.show', compact('user'));
    }
}
