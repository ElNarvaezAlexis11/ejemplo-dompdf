<?php

namespace App\Http\Livewire\Forms;

use App\Models\Form;
use Livewire\Component;

class Preview extends Component
{
    /**
     * Informacion del formulario
     * @var mixed 
     */
    public array $form;

    /**
     * Arreglo de valores que son rellenados en el formulario. 
     */
    public array $answers;

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

    protected function rules(): array
    {
        $positionsTexts = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['TEXT']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsDates = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['DATE']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsRadios = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['RADIO']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionCheck = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['CHECK']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionGridMultiply = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['GRID_MULTIPLY']['title'],
            ])->map(fn ($element) => $element['position']);


        $positionGridVerify = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['GRID_VERIFY']['title'],
            ])->map(fn ($element) => $element['position']);

        $array = array_merge(
            ...$this->getRulesToText($positionsTexts->all()),
            ...$this->getRulesToDate($positionsDates->all()),
            ...$this->getRulesToRadio($positionsRadios->all()),
            ...$this->getRulesToCheck($positionCheck->all()),
            ...$this->getRulesToGridMultiply($positionGridMultiply->all()),
            ...$this->getRulesToGridVerify($positionGridVerify->all())
        );

        return $array;
    }


    /**
     * Estas reglas de validaciones las voy a poner en el modelo principal del formuiario 
     */

    public function getRulesToText(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|string|max:100"
        ], $postions);
    }

    public function getRulesToDate(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|date"
        ], $postions);
    }

    public function getRulesToRadio(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "required_if:form.elementos.$position.required,true|string"
        ], $postions);
    }

    public function getRulesToCheck(array $postions = []): array
    {
        return array_map(function ($position): array {
            if ($this->form['elementos'][$position]['required']) {
                return ['answers.' . $position . '.values' => "array|min:1"];
            }
            return [
                'answers.' . $position . '.values' => "array",
            ];
        }, $postions);
    }

    public function getRulesToGridMultiply(array $postions = []): array
    {
        return array_map(fn ($position): array => [
            'answers.' . $position . '.values' => "array",
            'answers.' . $position . '.values.*' => "required_if:form.elementos.$position.required,true",
        ], $postions);
    }

    public function getRulesToGridVerify(array $postions = []): array
    {
        return array_map(function ($position): array {
            if ($this->form['elementos'][$position]['required']) {
                return [
                    'answers.' . $position . '.values.*' => "required|array|min:1",
                ];
            }
            return [
                'answers.' . $position . '.values' => "array",
            ];
        }, $postions);
    }

    /**
     * Estabalece la estructura del arreglo de valores que deben de 
     * contener cada uno de los elementos del formulario
     * @param int $position Posicion dentro de los elementos de "form" donde encuentran las grillas 
     * @return array Arreglo con ta estructura de LLave valor para las grllas
     */
    public function getStructorValues(int $position): array
    {
        switch ($this->form['elementos'][$position]['type']) {
            case $this->typeElements['GRID_VERIFY']['title']:
                return array_fill(0, count($this->form['elementos'][$position]['values'][0]), null);
            case $this->typeElements['GRID_MULTIPLY']['title']:
                return array_fill(0, count($this->form['elementos'][$position]['values'][0]), null);
        }
        return [];
    }

    /**
     * Funcion ejecutada al intentar de enviar un anexos en modo de edicion.
     * - Realiza la validacion para cada una de las entrdas dadas.
     * @return void 
     */
    public function test(): void
    {
        $this->resetErrorBag();
        $this->validate();
        dd("xvcxcvxcvxcv");
    }

    /**
     * @param Form $form
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/properties#initializing-properties
     */
    public function mount(Form $formModel): void
    {
        $this->fill(['form' => $formModel->toArray()]);

        if (is_null($this->form['elementos'])) {
            $this->form['elementos'] = [];
        }
        $this->form['elementos'] = json_decode($this->form['elementos'], true);

        $this->fill(['answers' => array_map(fn ($element): array => [
            'label' => $element['title'],
            'values' => [...$this->getStructorValues($element['position'])]
        ], $this->form['elementos'])]);
    }


    /**
     * Renderiza el componente
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/rendering-components#render-method
     */
    public function render()
    {
        return view('livewire.forms.preview');
    }
}
