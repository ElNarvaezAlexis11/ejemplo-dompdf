<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* @author 
*
* @property string|mixed $titulo_corto Titulo corto para el anexo.
* @property string|mixed $titulo_largo Titutlo largo para el anexo
* @property boolean|mixed $status Estado de activo o inactivo del Anexo.
* @property string|mixed $elementos Elementos que forman parte del Anexo, 
* este campo debe de ser en formato JSON. 
* @property \Illuminate\Support\Carbon $created_at Fecha de creacion
* @property \Illuminate\Support\Carbon $updated_at Ultima fecha de modificacion
* @property \Illuminate\Support\Carbon $deleted_at
*
*/
class Form extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * Los atributos para la asignacion masiva.
     *
     * @var array
     * @see https://laravel.com/docs/8.x/eloquent#mass-assignment
     */
    protected $fillable = [
        'titulo_corto',
        'titulo_largo',
        'descripcion',
        'status',
        'elementos',
    ];
}
