<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'nationality',
        'country',
        'organization',
        'industry',
        'org_type',
        'attending_as',
        'sector',
        'deal_room',
        'deal_room_role',
        'attendance_mode',
        'dietary',
        'accessibility',
        'other_requests',
        'consent_updates',
        'consent_media',
        'heard_about',
        'objective',
        'confirmed_at',
    ];

    protected $casts = [
        'consent_updates' => 'boolean',
        'consent_media' => 'boolean',
        'confirmed_at' => 'datetime',
    ];
}
