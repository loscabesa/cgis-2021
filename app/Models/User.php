<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'long_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function medico()
    {
        return $this->hasOne(Medico::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    /* Las funciones que se llaman getXXXXAttribute se llaman accesors y
     permiten su acceso mediante llamada como parámetro mágico:
    $user->tipo_usuario_id
    (https://laravel.com/docs/8.x/eloquent-mutators)
    */
    public function getTipoUsuarioIdAttribute(){
        if ($this->medico()->exists()){
            return 1;
        }
        elseif($this->paciente()->exists()){
            return 2;
        }
        else{
            return 3;
        }
    }

    public function getTipoUsuarioAttribute(){
        $tipos_usuario = [1 => trans('Médico'), 2 => trans('Paciente'), 3 => trans('Administrador')];
        return $tipos_usuario[$this->tipo_usuario_id];
    }
}
