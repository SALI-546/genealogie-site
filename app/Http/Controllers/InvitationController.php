<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\InvitationMail;


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
       // Mail::to($invitation->invitee_email)->send(new \App\Mail\InvitationMail($invitation));

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

        // Afficher le formulaire d'inscription pré-rempli avec les informations de la fiche personne
        return view('auth.register', ['invitation' => $invitation]);
    }
}
