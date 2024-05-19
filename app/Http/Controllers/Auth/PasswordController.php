<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        if ($request->routeIs('passwordApi')) {
            $validation = Validator::make($request->all(), [
                'current_password:api' => 'required|current_password',
                'password' => 'required|confirmed|min:8'
            ], [
                'current_password:api.required' => 'Veuillez inscrire l\'ancien mot de passe',
                'current_password.current_password' => 'L\'ancien mot de passe ne correspond pas',
                'password.required' => 'Veuillez inscrire le nouveau mot de passe',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
                'password.min' => 'Le mot de passe doit contenir au minimum 8 charactères',
            ]);

            if($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuDecode = $validation->validated();

            $request->user()->password = Hash::make($contenuDecode['password']);


            if ($request->user()->save())
                return response()->json(['SUCCÈS' => 'Mot de passe changé avec succès'], 200);
            else
                return response()->json(['ERREUR' => 'La modification du mot de passe a échouée.'], 400);
        } else {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('status', 'password-updated');
        }
    }

    public function show(Request $request)
    {
        return view('profile/updatePassword', [
            'user' => $request->user(),
        ]);
    }
}
