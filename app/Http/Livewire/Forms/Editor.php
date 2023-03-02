<?php

namespace App\Http\Livewire\Forms;

use App\Models\Form;
use Livewire\Component;

class Editor extends Component
{
    /**
     * Informacion del formulario
     * @var  
     */
    public $form;

    protected function rules(): array
    {
        $array = [
            'form.id' => 'exists:forms,id',
            'form.titulo_corto' => 'required|string|max:50',
            'form.titulo_largo' => 'required|string|max:200',
            'form.descripcion' => 'required|string|max:250',
            'form.status' => '',
            'form.elementos' => 'required',
            'form.elementos.*.title' => 'required|string',
            'form.elementos.*.type' => 'required|string',
            'form.elementos.*.values' => 'array',
            'form.elementos.*.required' => 'sometimes|boolean',
        ];

        $positionsChecksAndRadios =  collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['RADIO']['title'],
                $this->typeElements['CHECK']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsGridVerifyAndGridMultiply = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['GRID_VERIFY']['title'],
                $this->typeElements['GRID_MULTIPLY']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsDates = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['DATE']['title']
            ])
            ->where('validation', '!=', null)
            ->map(fn ($element) => $element['position']);

        $positionsParagrphas = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['PARAGRAPHS']['title']
            ])
            ->where('validation', '!=', null)
            ->map(fn ($element) => $element['position']);

        $array = array_merge(
            $array,
            ...$this->getRulesToChecksAndRadios($positionsChecksAndRadios->all()),
            ...$this->getRulesToGridVerifyAndGridMultiply($positionsGridVerifyAndGridMultiply->all()),
            ...$this->getRulesToDate($positionsDates->all()),
            ...$this->getRulesToParagrphs($positionsParagrphas->all())
        );

        return $array;
    }

    /**
     * Rgresa las reglas de validacion para las entradas de tipo "radio" y paras 
     * las entradas de tipo "check".
     * @param array $position Posicion en las que se encuentran las entradas de tipo
     * "radio" y "check".  
     * @return array Arreglo con las reglas de validacion.
     */
    private function getRulesToChecksAndRadios(array $positions = []): array
    {
        return array_map(fn ($position): array => [
            "form.elementos.$position.values" => 'required|array',
            "form.elementos.$position.values.*" => 'required|string'
        ], $positions);
    }

    /**
     * Rgresa las reglas de validacion para las entradas de tipo "Grid-Verify" y paras 
     * las entradas de tipo "Grid-Multiply".
     * @param array $position Posicion en las que se encuentran las entradas de tipo
     * "radio" y "check".  
     * @return array Arreglo con las reglas de validacion.
     */
    private function getRulesToGridVerifyAndGridMultiply(array $positions = []): array
    {
        return array_map(fn ($position): array => [
            "form.elementos.$position.values" => 'required|array',
            "form.elementos.$position.values.0" => 'required|array',
            "form.elementos.$position.values.1" => 'required|array',
            "form.elementos.$position.values.0.*" => 'required|string',
            "form.elementos.$position.values.1.*" => 'required|string',
        ], $positions);
    }

    /**
     * Regresa las reglas de validacion para los elmentos de tipo "DATE".
     * @param array $position Posicion en las que se encuentran las entradas de tipo "DATE".
     * @return array Arreglo de validaciones para las entradas de tipo "DATE" 
     */
    private function getRulesToDate(array $positions = []): array
    {
        return array_map(fn ($position): array => [
            "form.elementos.$position.validation" => 'required|array',
            "form.elementos.$position.validation.type" => 'required|string|in:between,after,before',
            "form.elementos.$position.validation.value" => "sometimes|nullable|required_if:form.elementos.$position.validation.type,after,before|date",
        ], $positions);
    }

    /**
     * Regresa las reglas de validacion para los elmentos de tipo "PARAGRAPHS".
     * @param array $position Posicion en las que se encuentran las entradas de tipo "PARAGRAPHS".
     * @return array Arreglo de validaciones para las entradas de tipo "PARAGRAPHS" 
     */
    private function getRulesToParagrphs(array $positions = []): array
    {
        return array_map(fn ($position): array => [
            "form.elementos.$position.validation" => 'required|array',
            "form.elementos.$position.validation.type" => 'required|string|in:min.max',
            "form.elementos.$position.validation.value" => "required|numeric|min:0|max:250",
        ], $positions);
    }

    /**
     * @param Form $form
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/properties#initializing-properties
     */
    public function mount(Form $form): void
    {
        $this->fill(['form' => $form->toArray()]);
        if (is_null($this->form['elementos'])) {
            $this->form['elementos'] = [];
        }
        $this->form['elementos'] = json_decode($this->form['elementos'], true);
    }

    /**
     * Regresa el nombre de la llave que identifica a el mensaje de error producido 
     * durante la validacion.
     * @param string $keyName Nombre de la propiedad del elemento dado por "positionElement".
     * @param int $positionElement Posicion del elemento a recupear el error.
     * @param int $numberValue Indice del valor dentro del arreglo de "values" para el elemento dado en "positionElement".
     * @return string Nombre de la llave del error  
     * 
     */
    public function getErrorStringKey(string $keyName, int $positionElement, int $numberValue = -1): string
    {
        if ($numberValue < 0) {
            return "form.elementos.$positionElement.$keyName";
        }
        return "form.elementos.$positionElement.$keyName.$numberValue";
    }

    /**
     * Regresa el nombre de la llave que identifica a el mensaje de error producido 
     * durante la validacion, para los elmentos que requieran una validacion especifica.
     * @param string $keyName Nombre de la propiedad del elemento dado por "positionElement".
     * @param int $positionElement Posicion del elemento a recupear el error.
     */
    public function getErrorStringKeyToValidations(string $keyName, int $positionElement): string
    {
        return "form.elementos.$positionElement.validation.$keyName";
    }

    /**
     * Porpiedad computada que regresa los tipos de entradas validas para el
     * formulario.
     * @return array 
     */
    public function getTypeElementsProperty(): array
    {
        return [
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
    }

    /**
     * Agrega un nuevo elemento a el formulario
     * @return void
     */
    public function addElement(): void
    {
        $this->form['elementos'][] = [
            'title' =>  '',
            'type' =>  'text',
            'values' =>  [],
            'required' =>  false,
            'validation' =>  null,
            'position' =>  count($this->form['elementos'])
        ];
    }

    /**
     * Establece el nuevo orden de los elmentos del "formulario",
     * esta accion es ejecutada cuando el usuario arrastra y suelta alguna de las "entradas" 
     * en el editor.
     * @param array $newPositions Nuevas posiciones de los elementos del "formulario".
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/alpine-js#interacting-with-livewire-from-alpine 
     */
    public function setNewOrder(array $newPositions  = []): void
    {
        foreach ($this->form['elementos'] as $index => $elmento) {
            $position = intval($newPositions[$index]);
            $this->form['elementos'][$position]['position'] = $index;
        }
        $this->sortElement();
    }

    /**
     * Ordena el arreglo de "elementos" dentro de "form" por la propiedad que 
     * cada uno de los elemetos contiene.
     * @return void 
     */
    public function sortElement(): void
    {
        usort(
            $this->form['elementos'],
            fn ($elemento_A, $elemento_B): int =>
            $elemento_A['position'] - $elemento_B['position']
        );
    }


    /**
     * Elimina un valor de la popiedad de "elementos" del arreglo "form"
     * @param int $index Indice del elmento a eliminar.
     * @return void
     */
    public function remove(int $index): void
    {
        unset($this->form['elementos'][$index]);
        $this->sortElement();
        foreach ($this->form['elementos'] as $index => $elmento) {
            $this->form['elementos'][$index]['position'] = $index;
        }
    }

    /**
     * Agrega un nuevo valor a las entradas de tipo "CHECK" y "RADIOS" en la posicion dada.
     * @param int $position Posicion del elmento a la que se le agregará una opcion.
     * @return void  
     */
    public function addValues(int $position): void
    {
        $number = count($this->form['elementos'][$position]['values']) + 1;
        $this->form['elementos'][$position]['values'][] = "New Option " . $number;
    }

    /**
     * Agrega una comlumna a los "valores" del elemento de la posicion dada del arreglo de "formulario"  
     *
     * @param int $position Posicion del elmento al que le agregaremos una nueva columna 
     * en el arreglo de valores.
     * @return void
     */
    public function addColToGrid(int $position): void
    {
        $element = $this->form['elementos'][$position];

        if (!is_array($element['values'][1])) {
            $this->form['elementos'][$position]['values'] = [
                [], //fila
                [], //columnas
            ];
        }
        $numbersOfColumns = count($element['values'][1]) + 1;

        $this->form['elementos'][$position]['values'][1][] = 'Col name' . $numbersOfColumns;
    }

    /**
     * Agrega una fila a los "valores" del elemento de la posicion dada del arreglo de "formulario"
     * @param int $position Posicion del elmento al que le agregaremos una nueva fila 
     * en el arreglo de valores.
     * @return void 
     */
    public function addRowToGrid(int $position): void
    {
        $element = $this->form['elementos'][$position];

        if (!is_array($element['values'][0])) {
            $this->form['elementos'][$position]['values'] = [
                [], //fila
                [], //columnas
            ];
        }
        $numbersOfRows = count($element['values'][0]) + 1;

        $this->form['elementos'][$position]['values'][0][] = 'Row name' . $numbersOfRows;
    }

    /**
     * Elimina la fila dada por la posicion de "numberRow" dentro de los "values" del 
     * elmento dado en la posicion de "positionElement"
     * @param int $positionElement Posicion del elemento dentro de la propiedad "elementos" del arreglo "form".
     * @param int $numberRow Posicion de la fila a eliminar dentro de la propiedad de "Values"  
     * @return void
     */
    public function removeRowToGrid(int $positionElement, int $numberRow): void
    {
        unset($this->form['elementos'][$positionElement]['values'][0][$numberRow]);
    }

    /**
     * Elimina la columna dada por la posicion de "numberCol" dentro de los "values" del 
     * elmento dado en la posicion de "positionElement"
     * @param int $positionElement Posicion del elemento dentro de la propiedad "elementos" del arreglo "form".
     * @param int $numberCol Posicion de la columna a eliminar dentro de la propiedad de "Values"  
     * @return void
     */
    public function removeColToGrid(int $positionElement, int $numberCol): void
    {
        unset($this->form['elementos'][$positionElement]['values'][1][$numberCol]);
    }

    /**
     * Remueve un valor dentro de las opciones de uno de los elementos dados.
     * 
     * @param int $positionElement Posion del elmento dentro del arreglo de "form"
     * @param int $positionValue Posicion dela valor a remover dentro de las propiedades 
     * de un elmento
     * @return void 
     * 
     */
    public function removeValue(int $positionElement, int $positionValue): void
    {
        unset($this->form['elementos'][$positionElement]['values'][$positionValue]);
    }

    /**
     * Evalua si el elemento de la posicion dada requiere de una validacion especifica
     * @param int $position
     * @return boool Si el elemento de la posicion dada, requiere de una validacion especial. 
     */
    public function requiredValidation(int $position): bool
    {
        return collect([
            $this->typeElements['PARAGRAPHS']['title'],
            $this->typeElements['DATE']['title']
        ])
            ->contains($this->form['elementos'][$position]['type']);
    }

    /**
     * Estable o remueve la validacion para el elmento de la posicion dada del arreglo "form"
     * 
     * @param int $position Posicion del "elemento" dentro del arreglo de "form"
     * @return void
     */
    public function setValidations(int $position): void
    {
        if ($this->form['elementos'][$position]['validation']) {
            $this->form['elementos'][$position]['validation'] = null;
            return;
        }

        switch ($this->form['elementos'][$position]['type']) {

            case $this->typeElements['PARAGRAPHS']['title']:
                $this->form['elementos'][$position]['validation'] = [
                    'value' => null,
                    'type ' => 'minum',
                ];
                break;

            case $this->typeElements['DATE']['title']:
                $this->form['elementos'][$position]['validation'] = [
                    'value' => null,
                    'type' => 'between',
                ];
                break;
        }
    }

    /**
     * Establece el tipo de entrada, de la posicion dada.
     * @param int $position Posicion del elemento a cambiar el "Tipo"
     * @param string $type Tipo de campo a asignar.
     * @return void
     */
    public function setType(int $position, string $type): void
    {
        $this->form['elementos'][$position]['type'] = $type;
        if ($type === $this->typeElements['RADIO']['title'] || $type === $this->typeElements['CHECK']['title']) {
            $this->addValues($position);
        }
        if ($type === $this->typeElements['GRID_VERIFY']['title'] || $type === $this->typeElements['GRID_MULTIPLY']['title']) {
            $this->form['elementos'][$position]['values'] = [
                [], //fila
                [], //columnas
            ];
        }
    }

    /**
     * Hook que se ejecuta despues de que la propiedad "form" a acabado de actualizarce
     * @param mixed|Form $value Valor anterior del arreglo "form" 
     * @return void 
     */
    public function updatedForm($value): void
    {
        #
    }

    /**
     * Funcion ejecutada al enviar el formulario de la vista.
     * @return void 
     */
    public function submit(): void
    {
        $this->validate();

        Form::find($this->form['id'])
            ->update($this->form);
        $this->dispatchBrowserEvent('saved');
    }

    /**
     * Renderiza el componente
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/rendering-components#render-method
     */
    public function render()
    {
        return view('livewire.forms.editor');
    }
}
