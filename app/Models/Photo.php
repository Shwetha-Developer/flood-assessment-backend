<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'local_id',
        'assessment_id',
        'file_path',
        'base64_data',
    ];

    // Hide base64_data from normal responses (too big)
    protected $hidden = ['base64_data'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
