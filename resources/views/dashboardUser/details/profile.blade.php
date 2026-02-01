<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="ti ti-user-circle me-2"></i>Información Personal
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px; background-color: var(--color-primary); color: white; font-size: 2.5rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                </div>
                
                <hr>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nombre Completo</label>
                        <p class="fw-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Email</label>
                        <p class="fw-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Miembro desde</label>
                        <p class="fw-medium">{{ Auth::user()->created_at->format('d M, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Estado</label>
                        <p class="fw-medium">
                            <span class="badge bg-success">Activo</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>