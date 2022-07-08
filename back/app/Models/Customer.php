<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    use  HasFactory;
    public $timestamps = false;
    protected $table = 'customers';
    protected $fillable = [
        'id',
        'name',
        'cpf',
        'email'   
    ];
}
