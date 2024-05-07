<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Http\Resources\ConversationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $id_user = null)
    {
        if (Auth::id()) {
            $id_user = Auth::id();
        }

        $conversations = Conversation::select('conversations.*')
            ->distinct()
            ->where('ferme', 0)
            ->join('messages', 'messages.id_conversation', '=', 'conversations.id')
            ->where(function ($query) use ($id_user) {
                   $query->where('messages.id_envoyeur', $id_user)
                   ->orWhere('messages.id_receveur', $id_user);
            })
            ->orderBy('messages.created_at', 'desc')
            ->get();

        if ($request->routeIs('conversations')) {
            return view('messagerie.conversations', [
                'conversations' => $conversations,
                'AuthId' => Auth::id()
            ]);
        }
        else if ($request->routeIs('conversationsApi')) {
            return ConversationResource::collection($conversations);
        }
    }

    public function obtenirDestinatairesPossibles() {
        return User::where('id', '!=', Auth::id())
                    ->whereNotIn('id', function($query) {
                        $query->select('id_envoyeur')
                            ->from('messages')
                            ->join('conversations', 'conversations.id', '=', 'messages.id_conversation')
                            ->where('id_receveur', Auth::id())
                            ->where('conversations.ferme', 0);
                    })
                    ->whereNotIn('id', function($query) {
                        $query->select('id_receveur')
                            ->from('messages')
                            ->join('conversations', 'conversations.id', '=', 'messages.id_conversation')
                            ->where('id_envoyeur', '=', Auth::id())
                            ->where('conversations.ferme', 0);
                    })
                    ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinataires = $this->obtenirDestinatairesPossibles();

        if (count($destinataires) == 0) {
            return back()->with('alerte', 'Vous n\'avez aucun nouveau destinataire possible! Vous avez déjà une conversation ouverte avec chacun des usagers.');
        }

        return view('messagerie.nouvelleConversation', [
            'destinataires' => $destinataires
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

        $destinatairesPossibles = $this->obtenirDestinatairesPossibles();

        if (!$destinatairesPossibles->contains('id', $idDestinataire)) {
            return back()->withErrors(['msg' => 'Ce destinataire ne fait pas partie des choix disponibles.']);
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

        return redirect()->route('conversations');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        $conversation = Conversation::find($id);

        $messages = $conversation->messages()
            ->whereNull('date_heure_supprime')
            ->orderBy('created_at')->get();

        $premierMessage = $conversation->messages()->first();

        $interlocuteur = null;

        if ($premierMessage->envoyeur->id == Auth::id()) {
            $interlocuteur = $premierMessage->receveur;
        }
        else if ($premierMessage->receveur->id == Auth::id()) {
            $interlocuteur = $premierMessage->envoyeur;
        }
        else {
            return back()->withErrors(['msg' => 'Vous ne faites pas partie de cette conversation.']);
        }

        return view('messagerie.conversation', [
            'messages' => $messages,
            'interlocuteur' => $interlocuteur,
            'AuthId' => Auth::id(),
            'conversation' => Conversation::find($id)
        ]);
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
