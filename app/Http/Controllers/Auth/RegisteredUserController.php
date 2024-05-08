<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginsInscriptionUtilisateur;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Role;
use App\Models\Ville;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'roles' => Role::All(),
            'villes' => Ville::All()
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        $validation = Validator::make($request->all(), [
            'prenom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
            'nom' => 'required|regex:/^[A-ZÀ-Ù]{1}[a-za-ù]*([-]?[A-ZÀ-Ù]{1}[a-za-ù]*)?$/',
            'email' => 'required|email|regex:/^[A-zÀ-ú\d]+[@]{1}[A-zÀ-ú\d]+[.]{1}[A-zÀ-ú]{2,}$/|unique:users,email',
            'telephone' => 'required|regex:/^\d{3}[ ]?\d{3}[- ]?\d{4}$/',
            'noCivique' => 'required|regex:/^\d{1,5}$/',
            'rue' => 'required|regex:/^[A-zÀ-ú\d ]+$/',
            'ville' => 'required',
            'codePostal' => 'required|regex:/^[A-Z]{1}\d{1}[A-Z]{1}[ ]?\d{1}[A-Z]{1}\d{1}$/',
            'appt' => 'nullable|regex:/^[A-Za-z\d]+$/',
            'roles' => 'required|min:1'
           ], [
            'prenom.required' => 'Veuillez entrer le prénom.',
            'prenom.regex' => 'Format de prénom invalide',
            'nom.required' => 'Veuillez entrer le nom.',
            'nom.regex' => 'Format de nom invalide',
            'email.required' => 'Veuillez entrer l\'adresse courriel',
            'email.regex' => 'Format de courriel invalide',
            'email.unique' => 'Email non unique',
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
            'roles.required' => 'Veuillez entrer le rôle de l\'utilisateur',
            'roles.min' => 'Veuillez entrer au moins un rôle pour l\'utilisateur'
        ]);

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

        $mdpTemp = $this->getTempMDP(10);

        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'no_civique' => $request->noCivique,
            'no_porte' => $request->appt,
            'rue' => $request->rue,
            'id_ville' => $request->ville,
            'code_postal' => $request->codePostal,
            'password' => Hash::make($mdpTemp)
        ]);

        $user->roles()->attach($request->roles);

        event(new Registered($user));

        Mail::to($user)->send(new LoginsInscriptionUtilisateur($mdpTemp));

        return redirect(route('accueil', absolute: false));
    }

    public function getTempMDP($length)
    {
        $mdp = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $mdp .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $mdp;
    }

    public function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
}
