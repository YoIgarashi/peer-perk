<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use softDeletes;
    protected $dates = ['created_at', 'updated_at', 'date', 'deleted_at', 'completed_at'];
    public static function getEventIds()
    {
        return self::pluck('id')->toArray();
    }
    public function eventParticipants()
    {
        return $this->hasMany(EventParticipantLog::class);
    }
    public function eventTags()
    {
        return $this->hasMany(EventTag::class);
    }
    public function eventLikes()
    {
        return $this->hasMany(EventLike::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeCompletedEvents($query)
    {
        return $query->whereNotNull('completed_at');
    }
}