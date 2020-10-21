<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['option_description'];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function getOptionIdAttribute()
    {
        return $this->attributes['option_id'] = $this->attributes['id'];
    }
}
