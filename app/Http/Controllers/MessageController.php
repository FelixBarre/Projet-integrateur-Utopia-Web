<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $validation = Validator::make($request->all(), [
                'texte' => 'required|max:255',
                'id_envoyeur' => 'required|integer',
                'id_receveur' => 'required|integer',
                'id_conversation' => 'required|integer',
            ], [
                'texte.required' => 'Veuillez entrer un message.',
                'texte.max' => 'Votre message ne peut pas dépasser 255 caractères.',
                'id_envoyeur.required' => 'Veuillez inscrire l\'id de l\'envoyeur.',
                'id_envoyeur.integer' => 'L\'id de l\'envoyeur doit être un entier.',
                'id_receveur.required' => 'Veuillez inscrire l\'id du receveur.',
                'id_receveur.integer' => 'L\'id du receveur doit être un entier.',
                'id_conversation.required' => 'Veuillez inscrire l\'id de la conversation.',
                'id_conversation.integer' => 'L\'id de la conversation doit être un entier.',
            ]);

            if ($validation->fails()) {
                return response()->json(['ERREUR' => $validation->errors()], 400);
            }

            $contenuMessage = $validation->validated();

            try {
                $message = Message::create([
                    'texte' => $contenuMessage['texte'],
                    'id_envoyeur' => $contenuMessage['id_envoyeur'],
                    'id_receveur' => $contenuMessage['id_receveur'],
                    'id_conversation' => $contenuMessage['id_conversation']
                ]);
            } catch (QueryException $erreur) {
                report($erreur);
                return response()->json(['ERREUR' => 'Le message n\'a pas été créé.'], 500);
            }

            return response()->json(['SUCCÈS' => 'Le message a bien été créé.', 'id' => $message->id ], 200);
        }
    }

    public function getNewMessages(Request $request, int $id_conversation, int $id_dernier_message) {
        if ($request->routeIs('getNewMessages')) {
            return MessageResource::collection(Message::where('id_conversation', $id_conversation)
                ->where('id', '>', $id_dernier_message)
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

            $message->texte = $contenuMessage['texte'];

            $message->save();

            return response()->json(['SUCCÈS' => 'Le message a bien été modifié.'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
