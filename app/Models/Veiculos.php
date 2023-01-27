<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculos extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_veiculos_id',
        'name',
        'quantidade_lugares',
        
    ];
    
    public function rules()
    {
        return [
        'tipo_veiculos_id' => 'required',
        'name' => 'required',
        'quantidade_lugares' => 'required',
        
    ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório'
        ];
    }
    public function tipo_veiculos() {
        
        return $this->belongsTo('App\Models\TipoVeiculos');
    }

    public function caravanas() {
        
        return $this->belongsToMany(caravanas::class);
    }

   
}
