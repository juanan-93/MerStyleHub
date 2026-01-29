<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Colorimetry;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    public function index()
    {
        $users = User::role('customer')->latest()->paginate(10);

        return view('user.index', compact('users'));
    }

    // Create user 
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $colorimetries = Colorimetry::where('is_active', true)->get();

        return view('user.create', compact('products', 'colorimetries'));
    }

    // Store user
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'city' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'phone_call_date' => ['nullable', 'date'],
            'product_id' => ['nullable', 'exists:products,id'],
            'style' => ['nullable', 'string', 'max:255'],
            'morphology' => ['nullable', 'string', 'max:255'],
            'colorimetry_id' => ['nullable', 'exists:colorimetries,id'],
            'observations' => ['nullable', 'string'],
            'service_completion_date' => ['nullable', 'date'],
            'percentage_paid' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_date' => ['nullable', 'date'],
            'percentage_pending' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // opcional: si quieres que dispare verificaciones/notificaciones tipo registro
        event(new Registered($user));

        // asignar rol Spatie como customer
        $user->assignRole('customer');

        // Crear perfil de cliente
        $user->customerProfile()->create([
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'city' => $request->city,
            'profession' => $request->profession,
            'phone_call_date' => $request->phone_call_date,
            'product_id' => $request->product_id,
            'style' => $request->style,
            'morphology' => $request->morphology,
            'colorimetry_id' => $request->colorimetry_id,
            'observations' => $request->observations,
            'service_completion_date' => $request->service_completion_date,
            'percentage_paid' => $request->percentage_paid ?? 0.00,
            'payment_date' => $request->payment_date,
            'percentage_pending' => $request->percentage_pending ?? 100.00,
        ]);

        return redirect()->route('users.index')->with('success', __('Usuario creado correctamente.'));
    }

    // Edit user
    public function edit(User $user)
    {
        $products = Product::where('is_active', true)->get();
        $colorimetries = Colorimetry::where('is_active', true)->get();

        return view('user.edit', compact('user', 'products', 'colorimetries'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'city' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'phone_call_date' => ['nullable', 'date'],
            'product_id' => ['nullable', 'exists:products,id'],
            'style' => ['nullable', 'string', 'max:255'],
            'morphology' => ['nullable', 'string', 'max:255'],
            'colorimetry_id' => ['nullable', 'exists:colorimetries,id'],
            'observations' => ['nullable', 'string'],
            'service_completion_date' => ['nullable', 'date'],
            'percentage_paid' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_date' => ['nullable', 'date'],
            'percentage_pending' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        // Si se proporciona una nueva contraseña, validarla
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
            $rules['password_confirmation'] = ['required'];
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Actualizar o crear perfil de cliente
        $user->customerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'age' => $request->age,
                'city' => $request->city,
                'profession' => $request->profession,
                'phone_call_date' => $request->phone_call_date,
                'product_id' => $request->product_id,
                'style' => $request->style,
                'morphology' => $request->morphology,
                'colorimetry_id' => $request->colorimetry_id,
                'observations' => $request->observations,
                'service_completion_date' => $request->service_completion_date,
                'percentage_paid' => $request->percentage_paid ?? 0.00,
                'payment_date' => $request->payment_date,
                'percentage_pending' => $request->percentage_pending ?? 100.00,
            ]
        );

        // Sincronizar rol Spatie como customer
        $user->syncRoles('customer');

        return redirect()->route('users.index')->with('success', __('Usuario actualizado correctamente.'));
    }

    // Destroy user
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['success' => true]);
    }
}
