@extends('layouts.app', ['title' => __('Users')])



@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center">
                <div>
                    <h5 class="mb-0" style="color:#343434;">{{ __('Users') }}</h5>
                    <small style="color:#A08A7A;">
                        {{ __('Manage the users created in the platform') }}
                    </small>
                </div>

                <a href="{{ route('users.create') }}"
                   class="btn"
                   style="background:#A08A7A;border-color:#A08A7A;color:#fff;">
                    <i class="ti ti-user-plus me-1"></i> {{ __('New user') }}
                </a>
            </div>

            <div class="card-body">
                {{-- Flash message (opcional) --}}
                @if (session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive"> {{-- responsive en móvil --}} {{-- [web:268] --}}
                    <table class="table align-middle table-hover mb-0">
                        <thead style="background:#ECE9E2;color:#343434;">
                            <tr>
                                <th style="width: 70px;">#</th>
                                <th>{{ __('Name') }}</th>
                                <th class="d-none d-md-table-cell">{{ __('Email') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th class="d-none d-lg-table-cell">{{ __('Created') }}</th>
                                <th class="text-end" style="width: 140px;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    // Spatie: devuelve una colección con los nombres de roles
                                    $roles = $user->getRoleNames(); // [web:281]
                                    $role = $roles->first();
                                @endphp

                                <tr>
                                    <td class="text-muted">
                                        {{ $user->id }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width:40px;height:40px;background:#ECE9E2;color:#343434;">
                                                <span class="fw-semibold">
                                                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="fw-semibold text-truncate" style="color:#343434; max-width: 220px;">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="small d-md-none text-truncate" style="color:#A08A7A; max-width: 220px;">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="d-none d-md-table-cell" style="color:#343434;">
                                        {{ $user->email }}
                                    </td>

                                    <td>
                                        @if ($role === 'admin')
                                            <span class="badge rounded-pill" style="background:#343434;">
                                                admin
                                            </span>
                                        @elseif ($role === 'worker')
                                            <span class="badge rounded-pill" style="background:#A08A7A;">
                                                worker
                                            </span>
                                        @elseif ($role === 'customer')
                                            <span class="badge rounded-pill text-dark" style="background:#ECE9E2;">
                                                customer
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ $role ?? '—' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="d-none d-lg-table-cell" style="color:#343434;">
                                        {{ optional($user->created_at)->format('d/m/Y') }}
                                    </td>

                                    <td class="text-end">
                                        {{-- De momento solo maqueta (sin rutas) --}}
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" title="Ver">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" title="Editar">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="mb-2" style="color:#343434;">
                                            {{ __('No users found') }}
                                        </div>
                                        <a href="{{ route('user.create') }}"
                                           class="btn btn-sm"
                                           style="background:#A08A7A;border-color:#A08A7A;color:#fff;">
                                            <i class="ti ti-user-plus me-1"></i> {{ __('Create the first user') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <small style="color:#A08A7A;">
                            {{ __('Showing') }} {{ $users->firstItem() }}–{{ $users->lastItem() }}
                            {{ __('of') }} {{ $users->total() }} {{ __('users') }}
                        </small>
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
