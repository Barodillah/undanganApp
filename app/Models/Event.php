<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'description',
        'notes',
        'event_date',
        'start_time',
        'end_time',
        'timezone',
        'venue_name',
        'venue_address',
        'venue_city',
        'venue_maps_link',
        'cover_image',
        'banner_image',
        'gallery',
        'max_guests',
        'guests_attending',
        'rsvp_enabled',
        'rsvp_deadline',
        'status',
        'theme',
        'primary_color',
        'font_family',
        'meta_title',
        'meta_description',
        'views',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'event_date'        => 'date',
        'start_time'        => 'datetime:H:i',
        'end_time'          => 'datetime:H:i',
        'rsvp_deadline'     => 'date',
        'rsvp_enabled'      => 'boolean',
        'gallery'           => 'array',
        'views'             => 'integer',
        'max_guests'        => 'integer',
        'guests_attending'  => 'integer',
    ];

    /**
     * Default value
     */
    protected $attributes = [
        'status' => 'draft',
        'views' => 0,
        'rsvp_enabled' => true,
    ];

    /**
     * RELATIONSHIPS
     */

    // Event dimiliki oleh user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * SCOPES
     */

    // Event yang publish
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Event akan datang
    public function scopeUpcoming($query)
    {
        return $query->whereDate('event_date', '>=', now()->toDateString());
    }

    // Event sudah selesai
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    /**
     * ACCESSORS
     */

    // URL event
    public function getUrlAttribute()
    {
        return route('event.show', $this->slug);
    }

    // Status label (untuk badge)
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'published' => 'Published',
            'private'   => 'Private',
            'cancelled' => 'Cancelled',
            'finished'  => 'Finished',
            default     => ucfirst($this->status),
        };
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
