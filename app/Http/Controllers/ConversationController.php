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
    public function index(Request $request)
    {
        $isApi = $request->routeIs('conversationsApi');

        $id_user = null;

        if (Auth::id()) {
            $id_user = Auth::id();
        }
        else {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Utilisateur non authentifié.'], 400);
            }
            else {
                return redirect()->route('login');
            }
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

        if ($isApi) {
            return ConversationResource::collection($conversations);
        }
        else {
            return view('messagerie.conversations', [
                'conversations' => $conversations,
                'AuthId' => Auth::id()
            ]);
        }
    }

    public function obtenirDestinatairesPossibles() {
        $id_user = null;

        if (Auth::id()) {
            $id_user = Auth::id();
        }
        else {
            return array();
        }

        return User::where('id', '!=', $id_user)
                    ->whereNotIn('id', function($query) use ($id_user) {
                        $query->select('id_envoyeur')
                            ->from('messages')
                            ->join('conversations', 'conversations.id', '=', 'messages.id_conversation')
                            ->where('id_receveur', $id_user)
                            ->where('conversations.ferme', 0);
                    })
                    ->whereNotIn('id', function($query) use ($id_user) {
                        $query->select('id_receveur')
                            ->from('messages')
                            ->join('conversations', 'conversations.id', '=', 'messages.id_conversation')
                            ->where('id_envoyeur', '=', $id_user)
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
        $isApi = $request->routeIs('creerConversationApi');

        $id_user = null;

        if (Auth::id()) {
            $id_user = Auth::id();
        }
        else {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Utilisateur non authentifié.'], 400);
            }
            else {
                return redirect()->route('login');
            }
        }

        $validation = Validator::make($request->all(), [
                'destinataire' => 'required|regex:/^.+ - [0-9]+$/',
                'message' => 'required|max:255'
            ], [
                'destinataire.required' => 'Veuillez entrer un destinataire.',
                'destinataire.regex' => 'Veuillez choisir un destinataire présent dans la liste.',
                'message.required' => 'Veuillez entrer un message.',
                'message.max' => 'Votre message doit avoir 255 caractères ou moins.'
            ]);

        if ($validation->fails()) {
            if ($isApi) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }
            else {
                return back()->withErrors($validation->errors())->withInput();
            }
        }

        $contenuFormulaire = $validation->validated();

        $valeursDestinataire = explode('-', $contenuFormulaire['destinataire']);
        $idDestinataire = intval(end($valeursDestinataire));

        if ($idDestinataire == $id_user) {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Vous ne pouvez pas créer de conversation avec vous-mêmes.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Vous ne pouvez pas créer de conversation avec vous-mêmes.']);
            }
        }

        $destinatairesPossibles = $this->obtenirDestinatairesPossibles();

        if (!$destinatairesPossibles->contains('id', $idDestinataire)) {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Ce destinataire ne fait pas partie des choix disponibles.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Ce destinataire ne fait pas partie des choix disponibles.']);
            }
        }

        $conversation = Conversation::create([
            'ferme' => 0
        ]);

        $message = Message::create([
            'texte' => $contenuFormulaire['message'],
            'id_envoyeur' => $id_user,
            'id_receveur' => $idDestinataire,
            'id_conversation' => $conversation->id
        ]);

        if ($isApi) {
            return response()->json(['SUCCÈS' => 'La conversation a bien été créée.' ], 200);
        }
        else {
            return redirect()->route('conversations');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id, int $id_user = null)
    {
        $isApi = $request->routeIs('conversationApi');

        $conversation = Conversation::find($id);

        $id_user = null;

        if (Auth::id()) {
            $id_user = Auth::id();
        }
        else {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Utilisateur non authentifié.'], 400);
            }
            else {
                return redirect()->route('login');
            }
        }

        if (!$conversation) {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Cette conversation n\'existe pas.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Cette conversation n\'existe pas.']);
            }
        }

        if ($conversation->ferme) {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Cette conversation est fermée.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Cette conversation est fermée.']);
            }
        }

        $premierMessage = Message::where('id_conversation', $conversation->id)->first();

        if (!$premierMessage) {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Cette conversation ne contient aucun message.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Cette conversation ne contient aucun message.']);
            }
        }

        $interlocuteur = null;

        if ($premierMessage->envoyeur->id == $id_user) {
            $interlocuteur = $premierMessage->receveur;
        }
        else if ($premierMessage->receveur->id == $id_user) {
            $interlocuteur = $premierMessage->envoyeur;
        }
        else {
            if ($isApi) {
                return response()->json(['ERREUR' => 'Vous ne faites pas partie de cette conversation.'], 400);
            }
            else {
                return back()->withErrors(['msg' => 'Vous ne faites pas partie de cette conversation.']);
            }
        }

        if ($isApi) {
            return response()->json([
                'conversation' => new ConversationResource($conversation),
                'interlocuteur' => $interlocuteur
            ], 200);
        }
        else {
            return view('messagerie.conversation', [
                'interlocuteur' => $interlocuteur,
                'AuthId' => $id_user,
                'conversation' => $conversation
            ]);
        }
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
    public function destroy(Request $request, int $id)
    {
        $id_user = null;

        if (Auth::id()) {
            $id_user = Auth::id();
        }
        else {
            return response()->json(['ERREUR' => 'Utilisateur non authentifié.'], 400);
        }

        $conversation = Conversation::find($id);

        if (is_null($conversation)) {
            return response()->json(['ERREUR' => 'Aucune conversation ne correspond à cet ID.'], 400);
        }

        $premierMessage = Message::where('id_conversation', $conversation->id)->first();

        if ($premierMessage->envoyeur->id != $id_user && $premierMessage->receveur->id != $id_user) {
            return response()->json(['ERREUR' => 'Vous ne faites pas partie de cette conversation.'], 400);
        }

        $conversation->ferme = 1;
        $conversation->save();

        return response()->json(['SUCCÈS' => 'La conversation a bien été supprimée.'], 200);
    }
}
