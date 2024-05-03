<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // On récupère l’utilisateur authentifié (s’il y en a un).
        $utilisateur = $request->user();

        // On vérifie que l’utilisateur qui a été récupéré est valide (si aucun utilisateur ne
        // s’était authentifié avant que le "middleware" ne soit appelé, la variable $utilisateur
        // sera "null").
        if ($utilisateur !== null)
        {
            // On fait appel à la relation "belongsToMany" de la classe "User", soit la méthode
            // "roles()" dans ce cas-ci, afin de récupérer tous les rôles de l’utilisateur et
            // vérifier si, parmi ceux-ci, se trouve le rôle "admin".
            if ($utilisateur->roles()->where('role', 'like', 'Administrateur')->count() > 0)
            {
                // La méthode "handle()" d’un "middleware" doit toujours retourner "$next($request)"
                // lorsque la règle sur laquelle porte ce "middleware" est satisfaite. Ici, on
                // voulait s’assurer que l’utilisateur authentifié était un administrateur, ce qui
                // est justement le cas si on s’est rendu jusque-là.
                return $next($request);
            }

            if ($request->bearerToken() && $request->accepts('application/json')) {
                return response()->json(['ERREUR' => 'Veuillez vous authentifier avec un compte administrateur'], 400);
            }

            // Si on se rend ici, c’est que l’utilisateur authentifié n’est pas un administrateur.
            // Il faut donc le déconnecter et fermer sa session pour qu’il puisse être redirigé
            // (plus bas dans le code) vers le formulaire de connexion.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Rendu ici, aucun utilisateur ne s’était authentifié ou encore l’utilisateur authentifié
        // n’était pas un administrateur. On le redirige donc vers le formulaire de connexion en
        // inscrivant un message d’avertissement lui indiquant qu’il doit absolument se connecter
        // avec un compte administrateur pour continuer. Le message d’avertissement sera formatté
        // grâce à la vue "messageFlash.blade.php" réalisé plus tôt dans ce laboratoire.
        return redirect('/login')->with('alerte', 'Vous devez être authentifié avec un compte administrateur pour utiliser cette fonctionnalité.');
    }
}
