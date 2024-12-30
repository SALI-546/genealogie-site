<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id',
        'invitee_email',
        'invitee_person_id',
        'token',
        'status',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function inviteePerson()
    {
        return $this->belongsTo(Person::class, 'invitee_person_id');
    }
}
