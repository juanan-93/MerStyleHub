@extends('layouts.app', ['title' => __('Asignar Cuestionario')])

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('questionnaire.index') }}">
            <i class="ti ti-clipboard-list me-1"></i>{{ __('Cuestionarios') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Asignar Usuarios') }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Mensajes --}}
    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- Información del Cuestionario --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px; background-color: rgba(160, 138, 122, 0.1);">
                        <i class="ti ti-clipboard-list" style="font-size: 1.5rem; color: var(--color-primary);"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-semibold" style="color: var(--color-secondary);">{{ $questionnaire->title }}</h5>
                        <p class="mb-0 text-muted small">{{ $questionnaire->description ?: 'Sin descripción' }}</p>
                    </div>
                    <div class="ms-auto">
                        @if($questionnaire->status === 'active')
                            <span class="badge bg-success px-3 py-2">Activo</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Inactivo</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Asignar nuevos usuarios --}}
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                    <i class="ti ti-user-plus me-2" style="color: var(--color-primary);"></i>{{ __('Asignar Usuarios') }}
                </h5>
            </div>
            <div class="card-body">
                @if($availableUsers->count() > 0)
                    <form action="{{ route('questionnaire.assign.store', $questionnaire->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">{{ __('Selecciona los usuarios') }}</label>
                            <div class="users-list" style="max-height: 350px; overflow-y: auto;">
                                @foreach($availableUsers as $user)
                                    <div class="form-check user-item p-3 rounded mb-2" style="background-color: var(--color-light);">
                                        <input class="form-check-input" type="checkbox" name="user_ids[]" 
                                               value="{{ $user->id }}" id="user_{{ $user->id }}">
                                        <label class="form-check-label w-100 d-flex align-items-center ms-2" for="user_{{ $user->id }}" style="cursor: pointer;">
                                            <div class="avatar-circle me-3" style="width: 40px; height: 40px; background-color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-white fw-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('user_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="ti ti-send me-1"></i> {{ __('Asignar Seleccionados') }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-users-group" style="font-size: 3rem; color: var(--color-border);"></i>
                        </div>
                        <h6 class="text-muted mb-2">{{ __('No hay usuarios disponibles') }}</h6>
                        <p class="text-muted small mb-0">{{ __('Todos los usuarios ya tienen asignado este cuestionario o no hay usuarios con rol cliente registrados.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Usuarios asignados --}}
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold" style="color: var(--color-secondary);">
                    <i class="ti ti-users me-2" style="color: var(--color-primary);"></i>{{ __('Usuarios Asignados') }}
                    <span class="badge bg-light text-dark ms-2">{{ $assignedUsers->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($assignedUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Usuario') }}</th>
                                    <th class="text-center">{{ __('Estado') }}</th>
                                    <th class="text-center">{{ __('Asignado') }}</th>
                                    <th class="text-end">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3" style="width: 40px; height: 40px; background-color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <span class="text-white fw-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($user->pivot->status === 'completed')
                                                <span class="badge bg-success px-3">
                                                    <i class="ti ti-check me-1"></i>Completado
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark px-3">
                                                    <i class="ti ti-clock me-1"></i>Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">
                                                {{ $user->pivot->assigned_at ? \Carbon\Carbon::parse($user->pivot->assigned_at)->format('d/m/Y H:i') : '-' }}
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            @if($user->pivot->status === 'completed')
                                                <a href="{{ route('questionnaire.user-responses', [$questionnaire->id, $user->id]) }}" 
                                                   class="btn btn-sm btn-outline-secondary rounded-circle"
                                                   title="Ver respuestas"
                                                   data-bs-toggle="tooltip">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('questionnaire.unassign', [$questionnaire->id, $user->id]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger rounded-circle btn-unassign"
                                                        title="Quitar asignación"
                                                        data-bs-toggle="tooltip"
                                                        data-user-name="{{ $user->name }}">
                                                    <i class="ti ti-user-minus"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-user-off" style="font-size: 3rem; color: var(--color-border);"></i>
                        </div>
                        <h6 class="text-muted mb-2">{{ __('Sin usuarios asignados') }}</h6>
                        <p class="text-muted small mb-0">{{ __('Selecciona usuarios de la lista para asignarles este cuestionario.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Botón volver --}}
    <div class="col-12">
        <a href="{{ route('questionnaire.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> {{ __('Volver a Cuestionarios') }}
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        padding-left: 2.5rem !important; /* Más espacio para el checkbox */
        position: relative;
    }

    .user-item .form-check-input {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        margin: 0;
    }

    .user-item:hover {
        background-color: rgba(160, 138, 122, 0.1) !important;
        border-color: var(--color-primary);
    }

    .user-item:has(.form-check-input:checked) {
        background-color: rgba(160, 138, 122, 0.15) !important;
        border-color: var(--color-primary);
    }

    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }

    .btn-primary-custom {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
        color: var(--color-white);
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background-color: #8B7669;
        border-color: #8B7669;
        color: var(--color-white);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(160, 138, 122, 0.3) !important;
    }

    .users-list::-webkit-scrollbar {
        width: 6px;
    }

    .users-list::-webkit-scrollbar-track {
        background: var(--color-light);
        border-radius: 3px;
    }

    .users-list::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 3px;
    }

    .users-list::-webkit-scrollbar-thumb:hover {
        background: var(--color-primary);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Click en toda la fila del usuario para seleccionar
        document.querySelectorAll('.user-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });

        // Confirmación para quitar asignación
        document.querySelectorAll('.btn-unassign').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const userName = this.getAttribute('data-user-name');
                const form = this.closest('form');
                
                if (confirm(`¿Estás seguro de quitar la asignación de "${userName}"? Si el usuario ha comenzado a responder, sus respuestas se mantendrán.`)) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
