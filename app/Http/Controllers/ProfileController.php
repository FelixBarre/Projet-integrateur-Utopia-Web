<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Ville;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProfileResource;
use App\Models\RolesUsers;

class ProfileController extends Controller
{
    public function getUserByEmail(Request $request, string $email)
    {
        $user = User::where('email', $email)->get();

        if ($user->isEmpty())
            return response()->json(['ERREUR' => 'Aucun utilisateur n\'est lié à ce courriel'], 400);

        return ProfileResource::collection($user);
    }

    public function show(Request $request)
    {
        if ($request->routeIs('getUserApi')) {
            if (isset($request['id'])) {
                $user = User::where('id', $request['id'])->get();
                if ($user->isEmpty())
                    return response()->json(['ERREUR' => 'Aucun utilisateur n\'est lié à cet ID'], 400);

                $rolesUserSpecific = DB::select('SELECT * FROM roles_users
                                    WHERE id_user =' . $request['id']);

                $roleEmploye = Role::where('role', "Employé")->get();
                $roleAdmin = Role::where('role', "Administrateur")->get();

                $userIsAdmin = false;
                $userSpecificNeedAdmin = false;

                foreach($request->user()->roles as $role) {
                    if ($role->role == "Administrateur") {
                        $userIsAdmin = true;
                    }
                }

                if (!$userIsAdmin) {
                    foreach ($rolesUserSpecific as $role) {
                        if ($role->id_role == $roleEmploye[0]->id || $role->id_role == $roleAdmin[0]->id) {
                            $userSpecificNeedAdmin = true;
                        }
                    }
                }

                if ($userIsAdmin || !$userSpecificNeedAdmin) {
                    return ProfileResource::collection($user);
                } else {
                    return response()->json(['ERREUR' => 'Vous n\'êtes pas autorisé à voir ce profil'], 400);
                }
            } else {
                return response()->json(['ERREUR' => 'Veuillez spécifier l\'ID'], 400);
            }
        } else {
            $ville = Ville::find($request->user()->id_ville);
            return view('profile.show', [
                'user' => $request->user(),
                'ville' => $ville
            ]);
        }
    }

    public function showUsers(Request $request): View
    {
        $admin = false;
        $rolesUser = $request->user()->roles;
        foreach($rolesUser as $role) {
            if ($role->role == "Administrateur") {
                $admin = true;
            }
        }

        if ($admin) {
            $users = DB::select('SELECT * FROM users
                                ORDER BY created_at DESC
                                LIMIT 5');
        } else {
            $users = DB::select('SELECT * FROM users
                                INNER JOIN roles_users
                                ON users.id = roles_users.id_user
                                WHERE roles_users.id_role = 3
                                ORDER BY created_at DESC
                                LIMIT 5');
        }

        return view('profile.showUsers', [
            'roles' => Role::All(),
            'users' => $users,
            'userLogged' => Auth::user()
        ]);
    }

    public function showUser(Request $request)
    {
        $userIsAdmin = false;
        $userSpecificNeedAdmin = false;

        $userSpecific = User::find($request->id_user);
        $ville = Ville::find($userSpecific->id_ville);

        foreach(Auth::user()->roles as $role) {
            if ($role->role == "Administrateur") {
                $userIsAdmin = true;
            }
        }

        if (!$userIsAdmin) {
            foreach ($userSpecific->roles as $role) {
                if ($role->role == "Employé" || $role->role == "Administrateur") {
                    $userSpecificNeedAdmin = true;
                }
            }
        }

        if ($userIsAdmin || !$userSpecificNeedAdmin) {
            return view('profile.show', [
                'user' => $userSpecific,
                'ville' => $ville
            ]);
        } else {
            return Redirect::route('users.show')->with('status', 'non-authorized');
        }
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $userIsAdmin = false;
        $userSpecificNeedAdmin = false;

        foreach(Auth::user()->roles as $role) {
            if ($role->role == "Administrateur") {
                $userIsAdmin = true;
            }
        }

        $userSpecific = User::find($request->id_user);
        if ($userSpecific) {
            if (!$userIsAdmin) {
                foreach ($userSpecific->roles as $role) {
                    if ($role->role == "Employé" || $role->role == "Administrateur") {
                        $userSpecificNeedAdmin = true;
                    }
                }
            }
            $userSpecificChecked = $userSpecific;
        } else {
            $userSpecificChecked = $request->user();
        }

        if ($userIsAdmin || !$userSpecificNeedAdmin) {
            return view('profile.edit', [
                'user' => $userSpecificChecked,
            ]);
        } else {
            return Redirect::route('users.show')->with('status', 'non-authorized');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $userSpecific = User::find($request->id_user);

        $validation = Validator::make($request->all(), [
            'prenom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
            'nom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
            'telephone' => 'required|regex:/^\d{3}[ ]?\d{3}[- ]?\d{4}$/',
            'noCivique' => 'required|regex:/^\d{1,5}$/',
            'rue' => 'required|regex:/^[A-zÀ-ú\d ]+$/',
            'ville' => 'required',
            'codePostal' => 'required|regex:/^[A-Z]{1}\d{1}[A-Z]{1}[ ]?\d{1}[A-Z]{1}\d{1}$/',
            'appt' => 'nullable|max:11|regex:/^\d+$/',
           ], [
            'prenom.required' => 'Veuillez entrer le prénom.',
            'prenom.regex' => 'Format de prénom invalide',
            'nom.required' => 'Veuillez entrer le nom.',
            'nom.regex' => 'Format de nom invalide',
            'telephone.required' => 'Veuillez entrer le numéro de téléphone',
            'telephone.regex' => 'Format de téléphone invalide',
            'noCivique.required' => 'Veuillez entrer le numéro civique',
            'noCivique.regex' => 'Format de numéro civique invalide',
            'rue.required' => 'Veuillez entrer le nom de la rue',
            'rue.regex' => 'Format de rue invalide',
            'ville.required' => 'Veuillez entrer la ville',
            'codePostal.required' => 'Veuillez entrer le code postal',
            'codePostal.regex' => 'Format de code postal invalide',
            'appt.regex' => 'Format de numéro de porte invalide',
            'appt.max' => 'Le numéro de porte doit contenir 11 caractères au maximum'
        ]);

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

        if ($userSpecific->isDirty('email')) {
            $userSpecific->email_verified_at = null;
        }

        $userSpecific->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'no_porte' => $request->appt,
            'no_civique' => $request->noCivique,
            'rue' => $request->rue,
            'id_ville' => $request->ville,
            'code_postal' => $request->codePostal,
        ]);

        $userSpecific->save();
        return Redirect::route('accueil')->with('status', 'profile-updated');
    }

    public function updateApi(Request $request, User $user) {
        $userIsAdmin = false;
        $userSpecificNeedAdmin = false;

        if ($request->routeIs('updateUserApi')) {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'prenom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
                'nom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
                'telephone' => 'required|regex:/^\d{3}[ ]?\d{3}[- ]?\d{4}$/',
                'noCivique' => 'required|regex:/^\d{1,5}$/',
                'rue' => 'required|regex:/^[A-zÀ-ú\d ]+$/',
                'id_ville' => 'required',
                'codePostal' => 'required|regex:/^[A-Z]{1}\d{1}[A-Z]{1}[ ]?\d{1}[A-Z]{1}\d{1}$/',
                'appt' => 'nullable|max:11|regex:/^\d+$/',
            ], [
                'id.required' => 'Veuillez entrer l\'ID',
                'prenom.required' => 'Veuillez entrer le prénom.',
                'prenom.regex' => 'Format de prénom invalide',
                'nom.required' => 'Veuillez entrer le nom.',
                'nom.regex' => 'Format de nom invalide',
                'telephone.required' => 'Veuillez entrer le numéro de téléphone',
                'telephone.regex' => 'Format de téléphone invalide',
                'noCivique.required' => 'Veuillez entrer le numéro civique',
                'noCivique.regex' => 'Format de numéro civique invalide',
                'rue.required' => 'Veuillez entrer le nom de la rue',
                'rue.regex' => 'Format de rue invalide',
                'id_ville.required' => 'Veuillez entrer l\'ID de la ville',
                'codePostal.required' => 'Veuillez entrer le code postal',
                'codePostal.regex' => 'Format de code postal invalide',
                'appt.regex' => 'Format de numéro de porte invalide',
                'appt.max' => 'Le numéro de porte doit contenir 11 caractères au maximum'
            ]);

            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDeCode = $validation->validated();

            if (!User::find($contenuDeCode['id'])) {
                return response()->json(['ERREUR' => 'Utilisateur introuvable'], 400);
            }

            if (!Ville::find($contenuDeCode['id_ville'])) {
                return response()->json(['ERREUR' => 'Ville introuvable'], 400);
            }

            $user = User::find($contenuDeCode['id']);

            foreach($request->user()->roles as $role) {
                if ($role->role == "Administrateur") {
                    $userIsAdmin = true;
                }
            }

            if (!$userIsAdmin) {
                foreach ($user->roles as $role) {
                    if ($role->role == "Employé" || $role->role == "Administrateur") {
                        $userSpecificNeedAdmin = true;
                    }
                }
            }


            if ($userIsAdmin || !$userSpecificNeedAdmin) {
                if (!isset($contenuDeCode['appt'])) {
                    $contenuDeCode['appt'] = null;
                }

                $user->prenom = $contenuDeCode['prenom'];
                $user->nom = $contenuDeCode['nom'];
                $user->telephone = $contenuDeCode['telephone'];
                $user->no_civique = $contenuDeCode['noCivique'];
                $user->rue = $contenuDeCode['rue'];
                $user->id_ville = $contenuDeCode['id_ville'];
                $user->code_postal = $contenuDeCode['codePostal'];
                $user->no_porte = $contenuDeCode['appt'];

                if ($user->save())
                    return response()->json(['SUCCÈS' => 'La modification du profil a bien été effectuée'], 200);
                else
                    return response()->json(['ERREUR' => 'La modification du profil a échouée'], 400);
            } else {
                return response()->json(['ERREUR' => 'Vous n\'êtes pas autorisés à modifier ce profil'], 400);
            }
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
