<?php

namespace App\Models;

use App\BudgetType;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['name', 'amount', 'type', 'user_id'];

    //Convertir el enum a string para poder compararlos e imprimirlos
    protected $casts = [
        'type' => BudgetType::class
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function isGeneral() : bool
    {
        return $this->type === BudgetType::GENERAL; 
    }

    public function isGoal() : bool
    {
        return $this->type === BudgetType::GOAL;
    }

}
