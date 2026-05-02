<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'local_id',
        'user_id',
        'latitude',
        'longitude',
        'address',
        'condition',
        'total_chickens',
        'notes',
        'synced_at',
        'assessed_at',
    ];

    // Assessment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Assessment has many photos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
