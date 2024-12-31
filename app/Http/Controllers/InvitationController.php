<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\InvitationMail; 
use App\Models\User;
use App\Notifications\InvitationReceived;

class InvitationController extends Controller
{
    /**
     * Envoyer une invitation.
     */
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'invitee_email' => 'required|email|unique:invitations,invitee_email',
            'person_id' => 'required|exists:people,id',
        ]);

        $token = Str::random(32);

        $invitation = Invitation::create([
            'inviter_id' => Auth::id(),
            'invitee_email' => $request->invitee_email,
            'invitee_person_id' => $request->person_id,
            'token' => $token,
            'status' => 'pending',
        ]);

        // Envoyer l'e-mail d'invitation
        Mail::to($invitation->invitee_email)->send(new InvitationMail($invitation));

        // Envoyer une notification si l'invité est déjà un utilisateur inscrit
    $inviteeUser = User::where('email', $invitation->invitee_email)->first();
    if ($inviteeUser) {
        $inviteeUser->notify(new InvitationReceived($invitation));
    }

        return back()->with('success', 'Invitation envoyée avec succès.');
    }

    /**
     * Accepter une invitation.
     */
    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('error', 'Invitation déjà traitée.');
        }

        // Mettre à jour le statut de l'invitation
        $invitation->update(['status' => 'accepted']);

        // Rediriger vers le formulaire d'inscription avec les informations pré-remplies
        return view('auth.register', ['invitation' => $invitation]);
    }

    public function createInvitation()
{
    // Récupérer les personnes créées par l'utilisateur authentifié
    $people = Person::where('created_by', Auth::id())->get();

    return view('invitations.send', compact('people'));
}
}
