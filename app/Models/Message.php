<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'receiver_id',
        'sender_id',
        'iv'
    ];

    public function scopeByUser($query, $userId)
    {
        $query->whereHas('receiver', function ($q) use ($userId) {
            $q->where('receiver_id', $userId);
        });
    }
    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
