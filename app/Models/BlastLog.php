<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlastLog extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'gender', 'sender_country_code', 'sender_phone_number', 'recipient_country_code', 'recipient_phone_number', 'message', 'source', 'status'];
}