<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->routeIs('envoiMessage')) {
            $id_user = Auth::id();

            if (!$id_user) {
                return response()->json(['ERREUR' => 'Utilisateur non authentifié.'], 400);
            }

            $validation = Validator::make($request->all(), [
                'pieceJointe' => 'mimes:jpg,jpeg,png,pdf,docx',
                'texte' => 'required|max:255',
                'id_conversation' => 'required|integer',
            ], [
                'pieceJointe.mimes' => 'Veuillez entrez une pièce jointe avec une des extensions acceptées : .jpg, .jpeg, .png, .pdf ou .docx.',
                'texte.required' => 'Veuillez entrer un message.',
                'texte.max' => 'Votre message ne peut pas dépasser 255 caractères.',
                'id_conversation.required' => 'Veuillez inscrire l\'id de la conversation.',
                'id_conversation.integer' => 'L\'id de la conversation doit être un entier.',
            ]);

            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuMessage = $validation->validated();

            $conversation = Conversation::find($contenuMessage['id_conversation']);

            if (!$conversation) {
                return response()->json(['ERREUR' => 'Cette conversation n\'existe pas.'], 400);
            }

            if ($conversation->ferme) {
                return response()->json(['ERREUR' => 'Cette conversation est fermée.'], 400);
            }

            $interlocuteur = null;

            $premierMessage = Message::where('id_conversation', $conversation->id)->first();

            if (!$premierMessage) {
                return response()->json(['ERREUR' => 'Cette conversation est vide! Impossible d\'y envoyer un message.'], 500);
            }

            if ($premierMessage->envoyeur->id == $id_user) {
                $interlocuteur = $premierMessage->receveur;
            }
            else if ($premierMessage->receveur->id == $id_user) {
                $interlocuteur = $premierMessage->envoyeur;
            }
            else {
                return response()->json(['ERREUR' => 'Vous ne faites pas partie de cette conversation.'], 400);
            }

            $contenuMessage['id_envoyeur'] = $id_user;
            $contenuMessage['id_receveur'] = $interlocuteur->id;

            if ($request->hasFile('pieceJointe')) {
                $pieceJointe = $request->file('pieceJointe');

                if ($pieceJointe->isValid()) {
                    $chemin = '/piecesJointes/' . $contenuMessage['id_envoyeur'] . '/' . $pieceJointe->getClientOriginalName();

                    Storage::put('public' . $chemin, $pieceJointe->get());

                    $contenuMessage['chemin_du_fichier'] = '/storage' . $chemin;
                }
            }

            try {
                $message = Message::create($contenuMessage);
            } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'Le message n\'a pas été créé.'], 500);
            }

            return response()->json(['SUCCÈS' => 'Le message a bien été créé.', 'message' => new MessageResource($message) ], 200);
        }
    }

    public function getNewMessages(Request $request, int $id_conversation, int $id_dernier_message) {
        if ($request->routeIs('getNewMessages')) {
            $id_user = Auth::id();
            $conversation = Conversation::find($id_conversation);

            if (!$conversation) {
                return response()->json(['ERREUR' => 'Cette conversation n\'existe pas.'], 400);
            }

            if ($conversation->ferme) {
                return response()->json(['ERREUR' => 'Cette conversation est fermée.'], 400);
            }

            $premierMessage = Message::where('id_conversation', $id_conversation)->first();

            if (!$premierMessage) {
                return response()->json(['ERREUR' => 'Cette conversation ne contient aucun message.'], 500);
            }

            if ($premierMessage->envoyeur->id != $id_user && $premierMessage->receveur->id != $id_user) {
                return response()->json(['ERREUR' => 'Vous ne faites pas partie de cette conversation.'], 400);
            }

            return MessageResource::collection(Message::where('id_conversation', $id_conversation)
                ->where('id', '>', $id_dernier_message)
                ->whereNull('date_heure_supprime')
                ->get());
        }
    }

    public function getUpdatedMessages(Request $request, int $id_conversation, String $date_derniere_update) {
        if ($request->routeIs('getUpdatedMessages')) {
            $id_user = Auth::id();
            $conversation = Conversation::find($id_conversation);

            if (!$conversation) {
                return response()->json(['ERREUR' => 'Cette conversation n\'existe pas.'], 400);
            }

            if ($conversation->ferme) {
                return response()->json(['ERREUR' => 'Cette conversation est fermée.'], 400);
            }

            $premierMessage = Message::where('id_conversation', $id_conversation)->first();

            if (!$premierMessage) {
                return response()->json(['ERREUR' => 'Cette conversation ne contient aucun message.'], 500);
            }

            if ($premierMessage->envoyeur->id != $id_user && $premierMessage->receveur->id != $id_user) {
                return response()->json(['ERREUR' => 'Vous ne faites pas partie de cette conversation.'], 400);
            }

            return MessageResource::collection(Message::where('id_conversation', $id_conversation)
                ->where('updated_at', '>', $date_derniere_update)
                ->get());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        if ($request->routeIs('modificationMessage')) {
            $validation = Validator::make($request->all(), [
                'texte' => 'required|max:255'
            ], [
                'texte.required' => 'Veuillez entrer un message.',
                'texte.max' => 'Votre message ne peut pas dépasser 255 caractères.'
            ]);

            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuMessage = $validation->validated();

            $message = Message::find($id);

            if (is_null($message)) {
                return response()->json(['ERREUR' => 'Aucun message ne correspond à cet ID.'], 400);
            }

            if ($message->id_envoyeur != Auth::id()) {
                return response()->json(['ERREUR' => 'Ce message ne vous appartient pas.'], 400);
            }

            $message->texte = $contenuMessage['texte'];

            $message->save();

            return response()->json(['SUCCÈS' => 'Le message a bien été modifié.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        if ($request->routeIs('suppressionMessage')) {
            $message = Message::find($id);

            if (is_null($message)) {
                return response()->json(['ERREUR' => 'Aucun message ne correspond à cet ID.'], 400);
            }

            if ($message->id_envoyeur != Auth::id()) {
                return response()->json(['ERREUR' => 'Ce message ne vous appartient pas.'], 400);
            }

            $message->date_heure_supprime = now();
            $message->save();

            return response()->json(['SUCCÈS' => 'Le message a bien été supprimé.'], 200);
        }
    }
}
