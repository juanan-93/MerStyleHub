{{-- Banner de Cookies - RGPD / LSSI-CE --}}
@unless(request()->cookie('cookie_consent'))
<div id="cookieBanner" class="cookie-banner" role="dialog" aria-label="Aviso de cookies" aria-modal="false">
    <div class="cookie-container">

        {{-- ========== VISTA PRINCIPAL ========== --}}
        <div id="cookieMain" class="cookie-main">
            <div class="cookie-icon-wrapper">
                <i class="ti ti-cookie"></i>
            </div>
            <div class="cookie-body">
                <h6 class="cookie-title">Utilizamos cookies</h6>
                <p class="cookie-desc">
                    Usamos cookies propias técnicas (necesarias) y podemos usar cookies analíticas o de marketing en el futuro. 
                    Puedes aceptar todas, configurarlas o solo aceptar las necesarias. 
                    <a href="#" class="cookie-policy-link" id="cookiePolicyToggle">Más información</a>
                </p>
            </div>
            <div class="cookie-buttons">
                <button type="button" class="cookie-btn cookie-btn-config" id="cookieSettingsBtn">
                    <i class="ti ti-settings me-1"></i>Configurar
                </button>
                <button type="button" class="cookie-btn cookie-btn-essential" id="cookieRejectBtn">
                    Solo necesarias
                </button>
                <button type="button" class="cookie-btn cookie-btn-accept" id="cookieAcceptBtn">
                    <i class="ti ti-check me-1"></i>Aceptar todas
                </button>
            </div>
        </div>

        {{-- ========== VISTA DE CONFIGURACIÓN ========== --}}
        <div id="cookieSettings" class="cookie-settings" style="display: none;">
            <div class="cookie-settings-top">
                <h6 class="cookie-settings-title">
                    <i class="ti ti-adjustments-horizontal me-2"></i>Configuración de cookies
                </h6>
                <button type="button" class="cookie-close-btn" id="cookieSettingsClose" aria-label="Cerrar configuración">
                    <i class="ti ti-x"></i>
                </button>
            </div>

            <div class="cookie-settings-list">
                {{-- Esenciales (siempre activas) --}}
                <div class="cookie-group">
                    <div class="cookie-group-info">
                        <div class="cookie-group-header">
                            <span class="cookie-group-icon"><i class="ti ti-lock"></i></span>
                            <strong>Cookies esenciales</strong>
                            <span class="cookie-required-badge">Siempre activas</span>
                        </div>
                        <p>Imprescindibles para el funcionamiento de la plataforma: sesión de usuario, protección CSRF y preferencias básicas de navegación.</p>
                    </div>
                    <div class="cookie-switch">
                        <input type="checkbox" id="cookieEssential" checked disabled>
                        <label for="cookieEssential"></label>
                    </div>
                </div>

                {{-- Analíticas --}}
                <div class="cookie-group">
                    <div class="cookie-group-info">
                        <div class="cookie-group-header">
                            <span class="cookie-group-icon"><i class="ti ti-chart-bar"></i></span>
                            <strong>Cookies analíticas</strong>
                        </div>
                        <p>Nos ayudan a entender cómo interactúas con la web para mejorar nuestros servicios. Se utilizan herramientas como Google Analytics.</p>
                    </div>
                    <div class="cookie-switch">
                        <input type="checkbox" id="cookieAnalytics">
                        <label for="cookieAnalytics"></label>
                    </div>
                </div>

                {{-- Marketing --}}
                <div class="cookie-group">
                    <div class="cookie-group-info">
                        <div class="cookie-group-header">
                            <span class="cookie-group-icon"><i class="ti ti-speakerphone"></i></span>
                            <strong>Cookies de marketing</strong>
                        </div>
                        <p>Permiten mostrarte contenido y publicidad personalizada, y medir la efectividad de nuestras campañas en redes sociales.</p>
                    </div>
                    <div class="cookie-switch">
                        <input type="checkbox" id="cookieMarketing">
                        <label for="cookieMarketing"></label>
                    </div>
                </div>
            </div>

            <div class="cookie-settings-bottom">
                <button type="button" class="cookie-btn cookie-btn-essential" id="cookieRejectAllBtn">
                    Rechazar opcionales
                </button>
                <button type="button" class="cookie-btn cookie-btn-accept" id="cookieSaveBtn">
                    <i class="ti ti-check me-1"></i>Guardar preferencias
                </button>
            </div>
        </div>

    </div>
</div>

<style>
    /* ========== COOKIE BANNER - MerStyleHub Style ========== */
    .cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        background: #343434;
        border-top: 3px solid #A08A7A;
        padding: 0;
        animation: cookieSlideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes cookieSlideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .cookie-banner.cookie-closing {
        animation: cookieSlideDown 0.35s ease-in forwards;
    }

    @keyframes cookieSlideDown {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(100%); opacity: 0; }
    }

    .cookie-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.25rem 1.5rem;
    }

    /* ===== VISTA PRINCIPAL ===== */
    .cookie-main {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .cookie-icon-wrapper {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #A08A7A 0%, #8f7668 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cookie-icon-wrapper i {
        font-size: 1.5rem;
        color: #fff;
    }

    .cookie-body {
        flex: 1;
        min-width: 0;
    }

    .cookie-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0 0 0.3rem 0;
    }

    .cookie-desc {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.8rem;
        line-height: 1.55;
        margin: 0;
    }

    .cookie-policy-link {
        color: #A08A7A;
        text-decoration: underline;
        font-weight: 500;
        transition: color 0.2s;
    }

    .cookie-policy-link:hover {
        color: #c4a88e;
    }

    .cookie-buttons {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
        align-items: center;
    }

    /* ===== BOTONES ===== */
    .cookie-btn {
        border: none;
        border-radius: 50px;
        padding: 0.65rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        font-family: 'Inter', sans-serif;
    }

    .cookie-btn-accept {
        background: linear-gradient(135deg, #A08A7A 0%, #8f7668 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(160, 138, 122, 0.35);
    }

    .cookie-btn-accept:hover {
        background: linear-gradient(135deg, #8f7668 0%, #7d6557 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(160, 138, 122, 0.45);
    }

    .cookie-btn-essential {
        background: rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .cookie-btn-essential:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    .cookie-btn-config {
        background: transparent;
        color: #A08A7A;
        padding: 0.65rem 0.9rem;
    }

    .cookie-btn-config:hover {
        color: #c4a88e;
        background: rgba(160, 138, 122, 0.1);
    }

    /* ===== VISTA CONFIGURACIÓN ===== */
    .cookie-settings-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .cookie-settings-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .cookie-settings-title i {
        color: #A08A7A;
    }

    .cookie-close-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        color: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .cookie-close-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    .cookie-settings-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        max-height: 50vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(160, 138, 122, 0.4) transparent;
    }

    .cookie-settings-list::-webkit-scrollbar { width: 4px; }
    .cookie-settings-list::-webkit-scrollbar-track { background: transparent; }
    .cookie-settings-list::-webkit-scrollbar-thumb { background: rgba(160, 138, 122, 0.4); border-radius: 4px; }

    .cookie-group {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 1rem 1.15rem;
        transition: background 0.2s;
    }

    .cookie-group:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    .cookie-group-info {
        flex: 1;
        min-width: 0;
    }

    .cookie-group-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }

    .cookie-group-icon {
        width: 28px;
        height: 28px;
        background: rgba(160, 138, 122, 0.15);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cookie-group-icon i {
        font-size: 0.85rem;
        color: #A08A7A;
    }

    .cookie-group-header strong {
        color: #fff;
        font-size: 0.88rem;
    }

    .cookie-required-badge {
        font-size: 0.65rem;
        background: rgba(160, 138, 122, 0.2);
        color: #A08A7A;
        padding: 0.15rem 0.5rem;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .cookie-group-info p {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.76rem;
        line-height: 1.5;
        margin: 0;
    }

    /* ===== TOGGLE SWITCH ===== */
    .cookie-switch {
        flex-shrink: 0;
        padding-top: 0.15rem;
    }

    .cookie-switch input {
        display: none;
    }

    .cookie-switch label {
        display: block;
        width: 44px;
        height: 24px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 24px;
        cursor: pointer;
        position: relative;
        transition: background 0.3s;
    }

    .cookie-switch label::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }

    .cookie-switch input:checked + label {
        background: #A08A7A;
    }

    .cookie-switch input:checked + label::after {
        transform: translateX(20px);
    }

    .cookie-switch input:disabled + label {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .cookie-switch input:disabled:checked + label {
        background: #A08A7A;
        opacity: 0.7;
    }

    .cookie-settings-bottom {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .cookie-container {
            padding: 1.15rem 1.25rem;
        }

        .cookie-main {
            flex-wrap: wrap;
        }

        .cookie-buttons {
            width: 100%;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 768px) {
        .cookie-container {
            padding: 1rem;
        }

        .cookie-main {
            flex-direction: column;
            align-items: stretch;
            gap: 0.85rem;
        }

        .cookie-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
        }

        .cookie-icon-wrapper i {
            font-size: 1.3rem;
        }

        .cookie-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .cookie-btn {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            min-height: 46px;
        }

        .cookie-btn-config {
            order: 3;
            background: rgba(255, 255, 255, 0.04);
        }

        .cookie-btn-accept {
            order: 1;
        }

        .cookie-btn-essential {
            order: 2;
        }

        /* Settings vista móvil */
        .cookie-settings-bottom {
            flex-direction: column;
        }

        .cookie-settings-bottom .cookie-btn {
            width: 100%;
        }

        .cookie-group {
            padding: 0.85rem;
        }

        .cookie-group-header strong {
            font-size: 0.82rem;
        }
    }

    @media (max-width: 375px) {
        .cookie-container {
            padding: 0.85rem;
        }

        .cookie-title {
            font-size: 0.95rem;
        }

        .cookie-desc {
            font-size: 0.75rem;
        }
    }
</style>

<script>
(function() {
    const banner = document.getElementById('cookieBanner');
    if (!banner) return;

    const mainView = document.getElementById('cookieMain');
    const settingsView = document.getElementById('cookieSettings');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function closeBanner() {
        banner.classList.add('cookie-closing');
        setTimeout(() => {
            banner.remove();
        }, 350);
    }

    function saveConsent(analytics, marketing) {
        fetch('{{ route("cookie.consent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                essential: true,
                analytics: analytics,
                marketing: marketing,
            }),
        })
        .then(response => {
            if (response.ok) {
                closeBanner();
            } else {
                // Fallback: guardar cookie manualmente y cerrar
                document.cookie = 'cookie_consent=' + encodeURIComponent(JSON.stringify({
                    essential: true,
                    analytics: analytics,
                    marketing: marketing,
                    date: new Date().toISOString()
                })) + '; path=/; max-age=' + (365 * 24 * 60 * 60) + '; SameSite=Lax';
                closeBanner();
            }
        })
        .catch(() => {
            // Fallback en caso de error de red
            document.cookie = 'cookie_consent=' + encodeURIComponent(JSON.stringify({
                essential: true,
                analytics: analytics,
                marketing: marketing,
                date: new Date().toISOString()
            })) + '; path=/; max-age=' + (365 * 24 * 60 * 60) + '; SameSite=Lax';
            closeBanner();
        });
    }

    // Aceptar todas
    document.getElementById('cookieAcceptBtn').addEventListener('click', function() {
        saveConsent(true, true);
    });

    // Solo necesarias
    document.getElementById('cookieRejectBtn').addEventListener('click', function() {
        saveConsent(false, false);
    });

    // Abrir configuración
    document.getElementById('cookieSettingsBtn').addEventListener('click', function() {
        mainView.style.display = 'none';
        settingsView.style.display = 'block';
    });

    // Cerrar configuración → volver a vista principal
    document.getElementById('cookieSettingsClose').addEventListener('click', function() {
        settingsView.style.display = 'none';
        mainView.style.display = 'flex';
    });

    // Rechazar opcionales desde configuración
    document.getElementById('cookieRejectAllBtn').addEventListener('click', function() {
        saveConsent(false, false);
    });

    // Guardar preferencias personalizadas
    document.getElementById('cookieSaveBtn').addEventListener('click', function() {
        const analytics = document.getElementById('cookieAnalytics').checked;
        const marketing = document.getElementById('cookieMarketing').checked;
        saveConsent(analytics, marketing);
    });

    // Link "Más información" abre configuración
    const policyToggle = document.getElementById('cookiePolicyToggle');
    if (policyToggle) {
        policyToggle.addEventListener('click', function(e) {
            e.preventDefault();
            mainView.style.display = 'none';
            settingsView.style.display = 'block';
        });
    }
})();
</script>
@endunless
