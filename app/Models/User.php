<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'password', 'email', 'active', 'type', 'rg', 'cpf', 'telefone', 'endereço', 'alas_id'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function rules(){
        return  [
        'name' => 'required',
        'email' => 'required|unique:Users,email,'.$this->id,
        'cpf' => 'required|unique:Users,cpf,'.$this->id,
        'active'=> 'required',
        'password'=> 'required',
        'telefone'  => 'required',
        'endereço' => 'required'
        ];

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


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'active' => 'active',

    // ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['name' => $this->name,
                'type' => $this->type];
    }

}
