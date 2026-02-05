<div class="row g-4">
    <div class="col-12">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-user me-2" style="color: var(--color-primary);"></i>Información Personal
        </h5>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Teléfono</div>
                <div class="info-value">{{ $profile->phone ?? 'No especificado' }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Edad</div>
                <div class="info-value">
                    @if($profile && $profile->birth_date)
                        {{ \Carbon\Carbon::parse($profile->birth_date)->age }} años
                    @else
                        No especificado
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Ciudad</div>
                <div class="info-value">{{ $profile->location ?? 'No especificado' }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Profesión</div>
                <div class="info-value">{{ $profile->profession ?? 'No especificado' }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-palette me-2" style="color: var(--color-primary);"></i>Información de Estilo
        </h5>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Servicio</div>
                <div class="info-value">
                    @if($profile && $profile->product)
                        {{ $profile->product->title }}
                    @else
                        No especificado
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Colorimetría</div>
                <div class="info-value">{{ $profile->colorimetry->name ?? 'No asignada' }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                <div class="info-label">Morfología</div>
                <div class="info-value">{{ $profile->morphology ?? 'No especificado' }}</div>
            </div>
        </div>
    </div>

    {{-- Documentos del Cliente --}}
    <div class="col-12 mt-4">
        <h5 class="fw-semibold mb-3" style="color: var(--color-secondary);">
            <i class="ti ti-files me-2" style="color: var(--color-primary);"></i>Documentos
        </h5>
    </div>

    <div class="col-12">
        <div class="card border-0" style="background-color: var(--color-light);">
            <div class="card-body">
                {{-- Zona de subida --}}
                <div id="dropZone" class="border-2 border-dashed rounded p-4 text-center mb-4" 
                     style="border-color: var(--color-primary); background-color: white; cursor: pointer; transition: all 0.3s;">
                    <input type="file" id="fileInput" multiple class="d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <i class="ti ti-cloud-upload mb-2" style="font-size: 3rem; color: var(--color-primary);"></i>
                    <h6 class="mb-2" style="color: var(--color-secondary);">Arrastra archivos aquí o haz clic para seleccionar</h6>
                    <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>
                </div>

                {{-- Lista de documentos --}}
                <div id="documentsList">
                    @forelse($profile->documents ?? [] as $document)
                        <div class="document-item card border-0 mb-2 p-3" style="background-color: white;" data-document-id="{{ $document->id }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; background-color: rgba(160, 138, 122, 0.1);">
                                        @if(Str::endsWith($document->file_name, ['.pdf']))
                                            <i class="ti ti-file-type-pdf" style="color: #dc3545; font-size: 1.5rem;"></i>
                                        @elseif(Str::endsWith($document->file_name, ['.doc', '.docx']))
                                            <i class="ti ti-file-type-doc" style="color: #0d6efd; font-size: 1.5rem;"></i>
                                        @elseif(Str::endsWith($document->file_name, ['.jpg', '.jpeg', '.png']))
                                            <i class="ti ti-photo" style="color: #198754; font-size: 1.5rem;"></i>
                                        @else
                                            <i class="ti ti-file" style="color: var(--color-primary); font-size: 1.5rem;"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold" style="color: var(--color-secondary);">{{ $document->file_name }}</h6>
                                        <small class="text-muted">
                                            {{ $document->formatted_size }} • 
                                            Subido el {{ $document->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $document->file_url }}" target="_blank" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-document" 
                                            data-document-id="{{ $document->id }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4" id="noDocumentsMessage">
                            <i class="ti ti-files-off mb-2" style="font-size: 2rem; color: var(--color-border);"></i>
                            <p class="text-muted mb-0">No hay documentos subidos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const documentsList = document.getElementById('documentsList');
    const userId = {{ $user->id }};
    const profileId = {{ $profile->id ?? 'null' }};

    if (!profileId) {
        dropZone.style.display = 'none';
        return;
    }

    // Click en zona de drop
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & Drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.backgroundColor = 'rgba(160, 138, 122, 0.1)';
            dropZone.style.transform = 'scale(1.02)';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.backgroundColor = 'white';
            dropZone.style.transform = 'scale(1)';
        });
    });

    dropZone.addEventListener('drop', handleDrop);
    fileInput.addEventListener('change', handleFileSelect);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        [...files].forEach(uploadFile);
    }

    function uploadFile(file) {
        // Validar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            mostrarNotificacion(
                'Archivo demasiado grande',
                'El archivo ' + file.name + ' supera el tamaño máximo de 10MB',
                'error'
            );
            return;
        }

        const formData = new FormData();
        formData.append('document', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Mostrar loader
        const loaderId = 'loader-' + Date.now();
        const loaderHtml = `
            <div class="document-item card border-0 mb-2 p-3" style="background-color: white;" id="${loaderId}">
                <div class="d-flex align-items-center gap-3">
                    <div class="spinner-border spinner-border-sm" style="color: var(--color-primary);" role="status"></div>
                    <span class="text-muted">Subiendo ${file.name}...</span>
                </div>
            </div>
        `;

        const noDocsMsg = document.getElementById('noDocumentsMessage');
        if (noDocsMsg) noDocsMsg.remove();

        documentsList.insertAdjacentHTML('beforeend', loaderHtml);

        fetch(`/info-user-admin/${userId}/documents/upload`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById(loaderId).remove();
            
            if (data.success) {
                addDocumentToList(data.document);
                mostrarNotificacion(
                    '¡Éxito!',
                    'Documento subido correctamente',
                    'success'
                );
            } else {
                mostrarNotificacion(
                    'Error',
                    'Error al subir archivo: ' + (data.message || 'Error desconocido'),
                    'error'
                );
            }
        })
        .catch(error => {
            document.getElementById(loaderId).remove();
            mostrarNotificacion(
                'Error',
                'Error al subir archivo: ' + error.message,
                'error'
            );
        });
    }

    function addDocumentToList(document) {
        let icon = 'ti-file';
        let iconColor = 'var(--color-primary)';

        if (document.file_name.match(/\.pdf$/i)) {
            icon = 'ti-file-type-pdf';
            iconColor = '#dc3545';
        } else if (document.file_name.match(/\.(doc|docx)$/i)) {
            icon = 'ti-file-type-doc';
            iconColor = '#0d6efd';
        } else if (document.file_name.match(/\.(jpg|jpeg|png)$/i)) {
            icon = 'ti-photo';
            iconColor = '#198754';
        }

        const documentHtml = `
            <div class="document-item card border-0 mb-2 p-3" style="background-color: white;" data-document-id="${document.id}">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background-color: rgba(160, 138, 122, 0.1);">
                            <i class="ti ${icon}" style="color: ${iconColor}; font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold" style="color: var(--color-secondary);">${document.file_name}</h6>
                            <small class="text-muted">
                                ${document.formatted_size} • 
                                Subido el ${document.created_at}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="${document.file_url}" target="_blank" 
                           class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-document" 
                                data-document-id="${document.id}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        documentsList.insertAdjacentHTML('beforeend', documentHtml);
    }

    // Eliminar documento
    documentsList.addEventListener('click', function(e) {
        if (e.target.closest('.delete-document')) {
            const btn = e.target.closest('.delete-document');
            const documentId = btn.getAttribute('data-document-id');
            
            Swal.fire({
                title: '¿Eliminar documento?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#A08A7A',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteDocument(documentId);
                }
            });
        }
    });

    function deleteDocument(documentId) {
        fetch(`/info-user-admin/${userId}/documents/${documentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const docElement = document.querySelector(`[data-document-id="${documentId}"]`);
                if (docElement) {
                    docElement.remove();
                }

                // Mostrar mensaje si no hay documentos
                if (documentsList.children.length === 0) {
                    documentsList.innerHTML = `
                        <div class="text-center py-4" id="noDocumentsMessage">
                            <i class="ti ti-files-off mb-2" style="font-size: 2rem; color: var(--color-border);"></i>
                            <p class="text-muted mb-0">No hay documentos subidos</p>
                        </div>
                    `;
                }

                mostrarNotificacion(
                    '¡Eliminado!',
                    'Documento eliminado correctamente',
                    'success'
                );
            } else {
                mostrarNotificacion(
                    'Error',
                    'Error al eliminar documento: ' + (data.message || 'Error desconocido'),
                    'error'
                );
            }
        })
        .catch(error => {
            mostrarNotificacion(
                'Error',
                'Error al eliminar documento: ' + error.message,
                'error'
            );
        });
    }
});
</script>
@endpush
