<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('user.index', compact('users'));
    }

    // Create user 
    public function create()
    {
        return view('user.create');
    }

    // Store user
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,worker,customer'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // opcional: si quieres que dispare verificaciones/notificaciones tipo registro
        event(new Registered($user));

        // asignar rol Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.dashboard');
    }
}
