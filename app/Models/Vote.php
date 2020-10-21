<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['qty'];

    public $incrementing = false;

    public function vote()
    {
        return $this->belongsTo('App\Models\Option');
    }
}
