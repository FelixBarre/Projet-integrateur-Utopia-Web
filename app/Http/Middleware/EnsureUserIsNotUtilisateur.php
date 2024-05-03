<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotUtilisateur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utilisateur = $request->user();

        if ($utilisateur !== null)
        {
            if ($utilisateur->roles()->where('role', 'like', 'Employé')->count() > 0 || $utilisateur->roles()->where('role', 'like', 'Administrateur')->count() > 0)
            {
                return $next($request);
            }

            if ($request->bearerToken() && $request->accepts('application/json')) {
                return response()->json(['ERREUR' => 'Veuillez vous authentifier avec un compte autre qu\'utilisateur'], 400);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/login')->with('alerte', 'Vous devez être authentifié avec un compte autre qu\'utilisateur pour utiliser cette fonctionnalité.');
    }
}
