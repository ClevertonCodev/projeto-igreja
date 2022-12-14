<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alas extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'endereço', 'estacas_id'];

    public function rules(){
        return [
        'name' => 'required', 
        'endereço' => 'required'];

    }

    public function feedback(){
        return [
            'required'=> 'O campo :attribute é obrigatório',
        ]; 
    }

    public function estacas() {
        //um usuario comum da ala pertece a uma estaca
        return $this->belongsTo('App\Models\Estacas');
    }

    public function users() {
        //um para muitos
        return $this->hasMany('App\Models\Users');
    }

    
}
