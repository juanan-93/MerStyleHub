@extends('layouts.app', ['title' => 'Crear usuario'])

@section('content')
    <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Crear usuario</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar password</label>
                <input class="form-control" name="password_confirmation" type="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                <option value="customer">customer</option>
                <option value="worker">worker</option>
                <option value="admin">admin</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button class="btn" style="background:#A08A7A;color:#fff;">Crear</button>
            </form>
        </div>
        </div>
    </div>
    </div>
@endsection
