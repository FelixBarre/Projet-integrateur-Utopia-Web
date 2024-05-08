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

class ProfileController extends Controller
{
    public function getUserByEmail(Request $request, string $email)
    {
        $user = User::where('email', $email)->get();

        if ($user->isEmpty())
            return response()->json(['ERREUR' => 'Aucun utilisateur n\'est lié à ce courriel'], 400);

        return ProfileResource::collection($user);
    }

    public function show(Request $request): View
    {
        $ville = Ville::find($request->user()->id_ville);
        return view('profile.show', [
            'user' => $request->user(),
            'ville' => $ville
        ]);
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

    public function showUser(Request $request): View
    {
        $userSpecific = User::find($request->id_user);
        $ville = Ville::find($userSpecific->id_ville);
        return view('profile.show', [
            'user' => $userSpecific,
            'ville' => $ville
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $userSpecific = User::find($request->id_user);
        if ($userSpecific) {
            $userSpecificChecked = $userSpecific;
        } else {
            $userSpecificChecked = $request->user();
        }
        return view('profile.edit', [
            'user' => $userSpecificChecked,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
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
            'appt' => 'nullable|regex:/^[A-Za-z\d]+$/',
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
        ]);

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

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
