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

    /**
     * 
     * Constante con los tipos de entradas validas para los `Anexos`.
     * @var array
     */
    const TYPE_ELEMENTS = [
        'TEXT' => [
            'title' => 'text',
            'icon' =>  'bi bi-text-left'
        ],
        'PARAGRAPHS' => [
            'title' => 'paragraphs',
            'icon' =>  'bi bi-text-paragraph'
        ],
        'RADIO' => [
            'title' => 'radio',
            'icon' =>  'bi bi-ui-radios'
        ],
        'CHECK' => [
            'title' => 'check',
            'icon' =>  'bi bi-ui-checks'
        ],
        'GRID_VERIFY' => [
            'title' => 'grid-verify',
            'icon' =>  'bi bi-ui-checks-grid'
        ],
        'GRID_MULTIPLY' => [
            'title' => 'grid-multiply',
            'icon' =>  'bi bi-ui-radios-grid'
        ],
        'DATE' => [
            'title' => 'date',
            'icon' =>  'bi bi-calendar'
        ],
        'HOUR' => [
            'title' => 'hour',
            'icon' =>  'bi bi-clock'
        ]
    ];

    public static function validations(array $elementos)
    {
        $positionsTexts = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['TEXT']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsDates = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['DATE']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsRadios = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['RADIO']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionCheck = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['CHECK']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionGridMultiply = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['GRID_MULTIPLY']['title'],
            ])->map(fn ($element) => $element['position']);


        $positionGridVerify = collect($elementos)
            ->whereIn('type', [
                self::TYPE_ELEMENTS['GRID_VERIFY']['title'],
            ])->map(fn ($element) => $element['position']);

        $array = array_merge(
            ...Form::getRulesToText($positionsTexts->all()),
            ...Form::getRulesToDate($positionsDates->all()),
            ...Form::getRulesToRadio($positionsRadios->all()),
            ...Form::getRulesToCheck($positionCheck->all(), $elementos),
            ...Form::getRulesToGridMultiply($positionGridMultiply->all(), $elementos),
            ...Form::getRulesToGridVerify($positionGridVerify->all(), $elementos)
        );

        return $array;
    }

    /**
     * Genera las reglas de validacion para los elementos de tipo "TEXT" 
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @return array 
     */
    public static function getRulesToText(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|string|max:100"
        ], $postions);
    }

    /**
     * Genera las reglas de validación para los elemetos de tipo "DATE"
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @return array 
     */
    public static function getRulesToDate(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|date"
        ], $postions);
    }

    /**
     * Genera las reglas de validación para los elemetos de tipo "RADIO"
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @return array 
     */
    public static function getRulesToRadio(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|string"
        ], $postions);
    }

    /**
     * Genera las reglas de validación para los elemetos de tipo "CHECK"
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @param array $elementos Arreglo con los Elementos del "Anexo".
     * @return array 
     */
    public static function getRulesToCheck(array $postions = [], array $elementos): array
    {
        return array_map(function ($position) use ($elementos): array {
            if ($elementos[$position]['required']) {
                return ['answers.' . $position . '.values' => "array|min:1"];
            }
            return [
                'answers.' . $position . '.values' => "array",
            ];
        }, $postions);
    }

    /**
     * Genera las reglas de validación para los elemetos de tipo "GRID_MULTPLY" 
     * - `GRID_MULTPLY` : Elemento de tabla, cuyas filas tiene botones de tipo "checkbox" 
     *      por cada una de las columnas.  
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @param array $elementos Arreglo con los Elementos del "Anexo".
     * @return array 
     */
    public static function getRulesToGridMultiply(array $postions = [], array $elementos): array
    {
        return array_map(function ($position) use ($elementos): array {
            if ($elementos[$position]['required']) {
                return [
                    'answers.' . $position . '.values.*' => "required",
                ];
            }
            return [
                'answers.' . $position . '.values' => "array",
            ];
        }, $postions);
    }

    /**
     * Genera las reglas de validación para los elemetos de tipo "GRID_VERIFY" 
     * - `GRID_VERIFY` : Elemento de tabla, cuyas filas tiene botones de tipo "checkbox" 
     *      por cada una de las columnas.  
     * @param array $positions Posiciones de los elementos a generar la validación.
     * @param array $elementos Arreglo con los Elementos del "Anexo".
     * @return array 
     */
    public static function getRulesToGridVerify(array $postions = [], array $elementos): array
    {
        return array_map(function ($position) use ($elementos): array {
            if ($elementos[$position]['required']) {
                return [
                    'answers.' . $position . '.values.*' => "required|array|min:1",
                ];
            }
            return [
                'answers.' . $position . '.values' => "array",
            ];
        }, $postions);
    }
}
