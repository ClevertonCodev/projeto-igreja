<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Users extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'password', 'email', 'active', 'type', 'rg', 'cpf', 'telefone', 'endereço', 'alas_id'];

    public function rules(){
        return [
        'name' => 'required',
        'email' => 'required|unique:Users,email'.$this->id,
        'cpf' => 'required|unique:Users,cpf'.$this->id,
        'telefone'  => 'required', 
        'endereço' => 'required'];

    }

    public function feedback(){
        return [
            'required'=> 'O campo :attribute é obrigatório',
            'email.unique' => 'Esse e-mail já existe',
            'cpf.unique' => 'Esse CPF já consta no sistema',
        ]; 
    }

    public function alas() {
        //um usuario comum pertence a varias alas
        return $this->belongsTo('App\Models\Alas');
    }

   
}


