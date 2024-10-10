<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transaction_code', 'amount', 'transaction_type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}