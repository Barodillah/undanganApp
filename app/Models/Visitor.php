<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'label',
        'group',
        'ip_address',
        'user_agent',
        'device',
        'platform',
        'browser',
        'country',
        'city',
        'page',
        'referrer',
        'is_unique',
        'visit_count',
        'attendance_status',
        'first_visit_at',
        'last_visit_at',
    ];

    protected $casts = [
        'is_unique' => 'boolean',
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
    ];

    /* ================= RELATION ================= */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Visitor.php
    public function getKodeAttribute()
    {
        // Ambil hanya huruf dari nama
        $onlyLetters = preg_replace('/[^A-Za-z]/', '', $this->name);

        // Ambil 3 huruf pertama, atau seluruhnya jika kurang dari 3 huruf
        $firstThree = strtoupper(substr($onlyLetters, 0, 3));

        return $firstThree
            . '-' . str_pad($this->event_id, 3, '0', STR_PAD_LEFT)
            . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}
