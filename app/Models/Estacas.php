<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacas extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'endereço'];

    public function rules(){
        return [
        'nome' => 'required', 
        'endereço' => 'required'];

    }

    public function feedback(){
        return [
            'required'=> 'O campo :attribute é obrigatório',
        ]; 
    }

}
