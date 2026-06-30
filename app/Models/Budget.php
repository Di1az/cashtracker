<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['name', 'amount', 'type', 'user_id'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

}
