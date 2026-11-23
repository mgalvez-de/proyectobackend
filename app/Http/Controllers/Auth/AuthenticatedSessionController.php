<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Solo se respeta la página guardada si es el formulario de un QR (reportar una falla).
        // Cualquier otra puede ser de la sesión de otra persona en el mismo computador
        // (ej: un supervisor terminaba en "Mis reportes" del estudiante y veía un 403).
        $intended = $request->session()->pull('url.intended');

        if ($intended && str_contains(parse_url($intended, PHP_URL_PATH) ?? '', '/tickets/create')) {
            return redirect()->to($intended);
        }

        // el dashboard manda a cada rol a su página (técnico → mis tickets, estudiante → mis reportes)
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
