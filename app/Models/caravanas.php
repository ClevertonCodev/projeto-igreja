<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class caravanas extends Model
{
    use HasFactory;

    protected $fillable = [ 'Nome' , 'Destino', 'Quantidade_passageiros', 'DataHora_partida','DataHora_retorno', 'Status','estacas_id'];

    
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function rules(){
        return  [
        'Nome' => 'required',
        'Destino' => 'required',
        'Quantidade_passageiros' => 'required',
        'DataHora_partida'=> 'required',
        'DataHora_retorno'=> 'required',
        'Status'  => 'required', 
        'estacas_id' => 'required'
        ];
        
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

}
