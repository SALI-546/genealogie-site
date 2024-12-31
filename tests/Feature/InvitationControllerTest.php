<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_send_invitation()
    {
        Mail::fake();

        $user = User::factory()->create();
        $person = Person::create(['created_by' => $user->id, 'first_name' => 'John', 'last_name' => 'Doe']);

        $response = $this->actingAs($user)->post(route('invitations.send'), [
            'invitee_email' => 'jane@example.com',
            'person_id' => $person->id,
        ]);

        $response->assertSessionHas('success', 'Invitation envoyée avec succès.');

        Mail::assertSent(InvitationMail::class, function ($mail) use ($user) {
            return $mail->invitation->inviter_id === $user->id;
        });
    }

    /** @test */
    public function invitation_requires_valid_email_and_person()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('invitations.send'), [
            'invitee_email' => 'invalid-email',
            'person_id' => 999, // Non existant
        ]);

        $response->assertSessionHasErrors(['invitee_email', 'person_id']);
    }
}
