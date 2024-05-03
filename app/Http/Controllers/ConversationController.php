<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
