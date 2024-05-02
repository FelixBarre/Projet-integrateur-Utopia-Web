<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Ville;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $ville = Ville::find($request->user()->id_ville);
        return view('profile.show', [
            'user' => $request->user(),
            'ville' => $ville
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
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

        /*
        $request->user()->fill($request->validated());*/

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'no_porte' => $request->appt,
            'no_civique' => $request->noCivique,
            'rue' => $request->rue,
            'id_ville' => $request->ville,
            'code_postal' => $request->codePostal,
        ]);
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
