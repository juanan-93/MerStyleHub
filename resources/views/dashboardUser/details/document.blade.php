@push('styles')
<style>
    .documents-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8B7669 100%);
        color: white;
        border-radius: 12px;
    }

    .document-card {
        background-color: var(--color-light);
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .document-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .document-icon-wrapper i {
        font-size: 1.5rem;
    }

    .document-icon-pdf { background-color: rgba(220, 53, 69, 0.1); }
    .document-icon-pdf i { color: #dc3545; }
    .document-icon-doc { background-color: rgba(13, 110, 253, 0.1); }
    .document-icon-doc i { color: #0d6efd; }
    .document-icon-img { background-color: rgba(25, 135, 84, 0.1); }
    .document-icon-img i { color: #198754; }
    .document-icon-default { background-color: rgba(160, 138, 122, 0.15); }
    .document-icon-default i { color: var(--color-primary); }

    .document-name {
        color: var(--color-secondary);
        font-weight: 600;
        font-size: 0.95rem;
        word-break: break-word;
    }

    .document-meta {
        color: #999;
        font-size: 0.8rem;
    }

    .btn-view-doc {
        background-color: var(--color-primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-view-doc:hover {
        background-color: #8c786a;
        color: white;
    }

    .btn-download-doc {
        background-color: transparent;
        color: var(--color-primary);
        border: 1px solid var(--color-primary);
        border-radius: 8px;
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .btn-download-doc:hover {
        background-color: var(--color-primary);
        color: white;
    }

    .documents-empty-state {
        padding: 3rem 1rem;
    }

    .documents-empty-state i {
        font-size: 4rem;
        color: var(--color-border);
    }

    .documents-count-badge {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .image-preview-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .image-preview-overlay img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .image-preview-overlay.active {
        display: flex;
    }
    
    /* ===== ESTILOS RESPONSIVE MÓVIL - DOCUMENTOS ===== */
    @media (max-width: 768px) {
        .documents-header .card-body {
            padding: 0.75rem !important;
        }
        
        .documents-header h4 {
            font-size: 1rem;
        }
        
        .documents-header p {
            font-size: 0.75rem;
            margin-bottom: 0 !important;
        }
        
        .documents-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.4rem !important;
        }
        
        .documents-count-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
        
        /* Cards de documentos compactas */
        .document-card {
            padding: 0.6rem !important;
        }
        
        .document-card .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem !important;
        }
        
        .document-icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 8px;
        }
        
        .document-icon-wrapper i {
            font-size: 1.1rem;
        }
        
        .document-name {
            font-size: 0.82rem;
        }
        
        .document-meta {
            font-size: 0.68rem;
        }
        
        /* Botones de acción */
        .document-card .d-flex.gap-2.flex-shrink-0 {
            width: 100%;
            margin-top: 0.25rem;
            justify-content: flex-end;
        }
        
        .btn-view-doc, .btn-download-doc {
            padding: 0.3rem 0.5rem;
            font-size: 0.72rem;
        }
        
        .documents-empty-state {
            padding: 1.5rem 0.75rem;
        }
        
        .documents-empty-state i {
            font-size: 2.5rem;
        }
        
        .documents-empty-state h5 {
            font-size: 0.95rem;
        }
        
        /* Espaciado entre documentos */
        .row.g-3 {
            --bs-gutter-y: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .documents-header h4 {
            font-size: 0.9rem;
        }
        
        .document-icon-wrapper {
            width: 34px;
            height: 34px;
        }
        
        .document-card .flex-grow-1 {
            min-width: 0;
        }
        
        .btn-view-doc span {
            display: none;
        }
        
        .document-name {
            font-size: 0.78rem;
        }
    }
</style>
@endpush

{{-- Cabecera de documentos --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm documents-header">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1 fw-bold">
                            <i class="ti ti-files me-2"></i>Mis Documentos
                        </h4>
                        <p class="mb-0 opacity-75">Documentos compartidos por tu asesora de imagen</p>
                    </div>
                    @if(isset($documents) && $documents->count() > 0)
                        <span class="documents-count-badge">
                            <i class="ti ti-file-check me-1"></i>{{ $documents->count() }} {{ $documents->count() === 1 ? 'documento' : 'documentos' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lista de documentos --}}
<div class="row">
    <div class="col-12">
        @if(isset($documents) && $documents->count() > 0)
            <div class="row g-3">
                @foreach($documents as $document)
                    <div class="col-12">
                        <div class="document-card card border-0 p-3">
                            <div class="d-flex align-items-center gap-3">
                                {{-- Icono según tipo --}}
                                @php
                                    $ext = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                                    $iconClass = 'document-icon-default';
                                    $icon = 'ti-file';
                                    
                                    if ($ext === 'pdf') {
                                        $iconClass = 'document-icon-pdf';
                                        $icon = 'ti-file-type-pdf';
                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                        $iconClass = 'document-icon-doc';
                                        $icon = 'ti-file-type-doc';
                                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                        $iconClass = 'document-icon-img';
                                        $icon = 'ti-photo';
                                    }
                                @endphp
                                
                                <div class="document-icon-wrapper {{ $iconClass }}">
                                    <i class="ti {{ $icon }}"></i>
                                </div>

                                {{-- Info del documento --}}
                                <div class="flex-grow-1">
                                    <div class="document-name">{{ $document->file_name }}</div>
                                    <div class="document-meta">
                                        {{ $document->formatted_size }} 
                                        &bull; Subido el {{ $document->created_at->format('d/m/Y') }}
                                        @if($document->description)
                                            &bull; {{ $document->description }}
                                        @endif
                                    </div>
                                </div>

                                {{-- Acciones --}}
                                <div class="d-flex gap-2 flex-shrink-0">
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <button type="button" class="btn-view-doc preview-image" 
                                                data-image-url="{{ $document->file_url }}">
                                            <i class="ti ti-eye me-1"></i>Ver
                                        </button>
                                    @endif
                                    <a href="{{ $document->file_url }}" target="_blank" class="btn-download-doc" title="Abrir documento">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                    <a href="{{ $document->file_url }}" download="{{ $document->file_name }}" class="btn-download-doc" title="Descargar">
                                        <i class="ti ti-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card border-0" style="background-color: var(--color-light); border-radius: 12px;">
                <div class="documents-empty-state text-center">
                    <i class="ti ti-files-off d-block mb-3"></i>
                    <h5 class="fw-semibold mb-2" style="color: var(--color-secondary);">Sin documentos</h5>
                    <p class="text-muted mb-0">Tu asesora de imagen aún no ha compartido documentos contigo.</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Overlay para previsualizar imágenes --}}
<div class="image-preview-overlay" id="imagePreviewOverlay">
    <img src="" alt="Preview" id="imagePreviewImg">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('imagePreviewOverlay');
    const previewImg = document.getElementById('imagePreviewImg');
    
    // Previsualizar imágenes
    document.querySelectorAll('.preview-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const imageUrl = this.getAttribute('data-image-url');
            previewImg.src = imageUrl;
            overlay.classList.add('active');
        });
    });
    
    // Cerrar preview al hacer clic
    overlay.addEventListener('click', function() {
        overlay.classList.remove('active');
        previewImg.src = '';
    });
    
    // Cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) {
            overlay.classList.remove('active');
            previewImg.src = '';
        }
    });
});
</script>
@endpush