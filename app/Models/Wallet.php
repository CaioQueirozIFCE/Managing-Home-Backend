<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    public static $relationships = ['usuario'];
    
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
