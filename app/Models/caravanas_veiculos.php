<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caravanas_veiculos extends Model
{
    use HasFactory;

    protected $fillable = ['caravanas_id', 'veiculos_id'];

    public function rules(){
        return  [
        'veiculos_id' => 'required|unique:Users,email,'.$this->id,
        ];

    }

    public function feedback(){
        return [

            'veiculos_id.unique' => 'Esse veiculoestá em uso',

        ];
    }
}
