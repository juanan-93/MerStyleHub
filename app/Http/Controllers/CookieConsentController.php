<?php

namespace App\Http\Controllers;

use App\Models\CookieConsent;
use Illuminate\Http\Request;

class CookieConsentController extends Controller
{
    /**
     * Guardar el consentimiento de cookies del usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'essential' => 'required|boolean',
            'analytics' => 'required|boolean',
            'marketing' => 'required|boolean',
        ]);

        // Registro legal en base de datos
        CookieConsent::create([
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            'essential' => true,
            'analytics' => $validated['analytics'],
            'marketing' => $validated['marketing'],
            'consented_at' => now(),
        ]);

        // Cookie de consentimiento (365 días)
        $consent = json_encode([
            'essential' => true,
            'analytics' => $validated['analytics'],
            'marketing' => $validated['marketing'],
            'date' => now()->toISOString(),
        ]);

        return response()->json(['success' => true])
            ->cookie('cookie_consent', $consent, 60 * 24 * 365, '/', null, true, false);
    }
}
