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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telephone' => ['required', 'string', 'max:255'],
            'noCivique' => ['required', 'string'],
            'rue' => ['required', 'string', 'max:255'],
            'ville' => ['required'],
            'roles' => ['required', 'array', 'min:1']
        ]);

        $mdpTemp = $this->getTempMDP(10);

        $user = User::create([
            'username' => $request->username,
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'no_civique' => $request->noCivique,
            'no_porte' => $request->appt,
            'rue' => $request->rue,
            'id_ville' => $request->ville,
            'password' => Hash::make($mdpTemp)
        ]);

        $user->roles()->attach($request->roles);

        event(new Registered($user));

        Auth::login($user);

        Mail::to($user)->send(new LoginsInscriptionUtilisateur($request->username, $mdpTemp));

        return redirect(route('dashboard', absolute: false));
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
