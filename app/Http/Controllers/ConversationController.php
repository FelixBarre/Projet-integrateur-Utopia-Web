<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('messagerie.conversations', [
            'conversations' => Conversation::select('conversations.*')
                ->distinct()
                ->where('ferme', 0)
                ->join('messages', 'messages.id_conversation', '=', 'conversations.id')
                ->join('users AS A', 'A.id', '=', 'messages.id_envoyeur')
                ->join('users AS B', 'B.id', '=', 'messages.id_receveur')
                ->where('A.id', Auth::id())
                ->orWhere('B.id', Auth::id())
                ->orderBy('messages.created_at', 'desc')
                ->get(),
            'AuthId' => Auth::id()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('messagerie.nouvelleConversation', [
            'destinataires' => User::where('id', '!=', Auth::id())->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
                'destinataire' => 'required|regex:/^.+ - [0-9]+$/',
                'message' => 'required|max:255'
            ], [
                'destinataire.required' => 'Veuillez entrer un destinataire.',
                'destinataire.regex' => 'Veuillez choisir un destinataire présent dans la liste.',
                'message.required' => 'Veuillez entrer un message.',
                'message.max' => 'Votre message doit avoir 255 caractères ou moins.'
            ]);

        if ($validation->fails())
            return back()->withErrors($validation->errors())->withInput();

        $contenuFormulaire = $validation->validated();

        $valeursDestinataire = explode('-', $contenuFormulaire['destinataire']);
        $idDestinataire = intval(end($valeursDestinataire));

        if ($idDestinataire == Auth::id()) {
            return back()->withErrors(['msg' => 'Vous ne pouvez pas créer de conversation avec vous-mêmes.']);
        }

        $conversation = Conversation::create([
            'ferme' => 0
        ]);

        $message = Message::create([
            'texte' => $contenuFormulaire['message'],
            'id_envoyeur' => Auth::id(),
            'id_receveur' => $idDestinataire,
            'id_conversation' => $conversation->id
        ]);

        return $this->index($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
