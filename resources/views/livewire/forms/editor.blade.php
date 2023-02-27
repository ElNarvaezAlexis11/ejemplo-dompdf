<div id="app" x-data="{ data: @entangle('form').defer }" class="w-full min-h-screen m-auto bg-gray-200">

    <form id="form" wire:submit.prevent="submit" class="w-full">

        <header class="w-full flex bg-white shadow-md px-16 py-5 sticky top-0 z-50">
            <div class="w-full flex truncate">
                <h1 x-text="data.titulo_corto" class="font-medium flex items-center">
                </h1>
            </div>
            <nav class="w-full flex justify-end">
                <ul class="flex items-center gap-2">
                    <li class="hidden" wire:loading.class.remove="hidden">
                        <a href="javascript: void(0)" class="text-yellow-500 text-xs font-semibold">
                            Cargando
                        </a>
                    </li>
                    <li wire:loading.remove x-data="{ show: false }" x-init="" x-show="show" @saved.window="() => {
                        show = true
                        setTimeout( ()=>{ show = false; }, 800 )
                    }">
                        <a href="javascript: void(0)" class="text-green-500 text-xs font-semibold">
                            Guardo
                        </a>
                    </li>

                    <!-- Visualizacion -->
                    <li>
                        <a href="javascript: void(0)" class=" flex items-center justify-center transition-all duration-150 p-1 text-lg text-gray-800 hover:bg-gray-300 rounded-full w-10 h-10">
                            <i class="bi bi-eye"></i>
                        </a>
                    </li>
                    <!-- Visualizacion -->

                    <!-- Selector -->
                    <li>
                        <div x-data="{ open: false }" class="relative flex">
                            <p @click="open = !open" class="w-full text-center flex gap-2 justify-between items-center px-4 py-1 border">
                                <span>Seleccione el destinatario</span>
                                <i class="bi bi-chevron-down"></i>
                            </p>
                            <ul x-show="open" @click.outside="open = false" class="w-full absolute right-0 top-full bg-white z-40 border">
                                <li class=" flex px-5 py-2 hover:bg-slate-100">
                                    Alumnos
                                </li>
                                <li class=" flex px-5 py-2 hover:bg-slate-100">
                                    Docente
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Selector -->

                    <!-- Boton guardar-->
                    <li>
                        <button type="submit" class="flex px-3 py-1 bg-gray-100 text-gray-800 font-semibold rounded">
                            <i class="bi bi-sd-card mr-3"></i>Guardar
                        </button>
                    </li>
                    <!-- Boton guardar-->

                    <!-- Boton publicar-->
                    <li>
                        <button class="flex px-3 py-1 bg-blue-700 mr-1 text-white font-semibold rounded">
                            <i class="bi bi-send mr-3"></i>Publicar
                        </button>
                    </li>
                    <!-- Boton publicar-->
                </ul>
            </nav>
        </header>

        <x-validation-errors />

        <!-- Cuerpo principal -->
        <main  x-data="editor($el,$wire)" class="sm:max-w-full md:max-w-xl mx-auto pb-40 pt-16">

            <!-- Seccion de informacion basica del anexo--->
            <div class="bg-white mb-10 p-3 grid grid-flow-row gap-2 shadow-md">
                <!-- Nombres y descripcion -->
                <section class="grid gap-3 grid-cols-2">
                    <div class="col-span-2">
                        <h2 class="w-full text-gray-800 font-medium p-2">Nombres y descripción.</h2>
                    </div>

                    <!-- Nombre corto -->
                    <div class="col-span-2">
                        <input type="text" x-model="data.titulo_corto" placeholder="Nombre corto" class="w-full bg-white text-xl rounded p-2 border focus:outline-none focus:border-blue-500 " />
                        @error('form.titulo_corto')
                        <p class="text-red-600">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- Nombre corto -->

                    <!-- Nombre largo -->
                    <div class="col-span-2">
                        <input type="text" x-model="data.titulo_largo" placeholder="Nombre largo" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500" />
                        @error('form.titulo_largo')
                        <p class="text-red-600">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- Nombre largo -->

                    <!-- Descripcion -->
                    <div class="col-span-2">
                        <textarea x-model="data.descripcion" placeholder="Descripción" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500"></textarea>
                        @error('form.descripcion')
                        <p class="text-red-600">{{$message}}</p>
                        @enderror
                    </div>
                    <!-- Descripcion -->

                </section>
                <!-- Nombres y descripcion -->
            </div>
            <!-- Seccion de informacion basica del anexo--->

            @foreach($form['elementos'] as $index => $elemento)
            <!-- Bloque -->
            <div :data-id="{{$index}}" class="container-info bg-white mb-10 p-3 grid grid-flow-row gap-2 shadow-md">
                <section class="drag grid-cols-2">
                    <div class="w-full flex justify-center text-slate-300 hover:text-slate-700 cursor-move">
                        <i class="bi bi-grip-horizontal"></i>
                    </div>
                </section>

                <!-- seccion de errores-->
                @if($errors->has('form.elementos.'.$index.'.*'))
                <ul>
                    @error('form.elementos.'.$index.'.title')
                    <li class="text-red-600">{{$message}}</li>
                    @enderror

                    @error('form.elementos.'.$index.'.type')
                    <li class="text-red-600">{{$message}}</li>
                    @enderror
                </ul>
                @endif
                <!-- seccion de errores-->

                <!-- Principal -->
                <section class="grid gap-3 grid-cols-2">
                    <div class="col-span-1">
                        <input type="text" placeholder="Pregunta" wire:model.lazy="form.elementos.{{$index}}.title" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 {{ $errors->has($this->getErrorStringKey('title',$index)) ? 'border-red-600' : ''  }}" />
                    </div>
                    <!-- Selector -->
                    <div x-data="{ open: false }" class="col-span-1 relative flex">
                        <p @click="open = !open" class="w-full text-center flex justify-between items-center px-4 border {{ $errors->has($this->getErrorStringKey('type',$index)) ? 'border-red-600' : '' }}">
                            <span>{{$form['elementos'][$index]['type'] ?? 'Seleccione el tipo de entrada' }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </p>

                        <!-- Elementos de selector -->
                        <ul x-show="open" @click.outside="open = false" class="w-full absolute left-0 top-full bg-white z-40 border">
                            @foreach($this->typeElements as $position => $type )
                            <li wire:click="setType( {{$index}}, '{{ $type['title'] }}' )" class="px-5 py-2 hover:bg-slate-100">
                                <i class="{{$type['icon']}}"></i>
                                <a href="javascript: void(0)">{{$type['title']}}</a>
                            </li>
                            @endforeach
                        </ul>
                        <!-- Elementos de selector -->

                    </div>
                    <!-- Selector -->
                </section>
                <!-- Principal -->

                <!-- cuerpo -->
                <section class="grid gap-2 grid-cols-2">
                    <!-- Propiedades de los elmentos -->
                    <div class="col-span-1">

                        @switch($elemento['type'])

                        @case($this->typeElements['TEXT']['title'])
                        <p class="text-gray-300 p-2">Texto de respuesta breve</p>
                        @break

                        @case($this->typeElements['PARAGRAPHS']['title'])
                        <p class="text-gray-300 p-2">Texto de respuesta largo</p>
                        @break

                        @case($this->typeElements['RADIO']['title'])
                        <div class="">
                            <ul class="radio-option w-full">
                                @foreach($elemento['values'] as $number => $value)
                                <li class="flex items-center gap-3 mb-2 w-full">
                                    <span class="text-xl">
                                        <i class="bi bi-record"></i>
                                    </span>
                                    <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.{{$number}}" placeholder="opcion" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKey('values',$index, $number)) ? 'border-red-600' : '' }}">
                                    <span wire:click="removeValue( {{ $index }},{{ $number }} )" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                        <i class="bi bi-x"></i>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            <span wire:click="addValues({{$index}})" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar opcion</span>
                        </div>
                        @break

                        @case($this->typeElements['CHECK']['title'])
                        <div class="">
                            <ul class="radio-option w-full">
                                @foreach($elemento['values'] as $number => $value)
                                <li class="flex items-center gap-3 mb-2 w-full">
                                    <span class="text-xl">
                                        <i class="bi bi-stop"></i>
                                    </span>
                                    <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.{{$number}}" placeholder="opcion" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKey('values',$index, $number)) ? 'border-red-600' : '' }}">
                                    <span wire:click="removeValue( {{ $index }},{{ $number }} )" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                        <i class="bi bi-x"></i>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            <span wire:click="addValues({{$index}})" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar opcion</span>
                        </div>
                        @break

                        @case($this->typeElements['GRID_VERIFY']['title'])
                        <div class="grid grid-cols-2">
                            <section class="col-span-2">
                                <h2 class="px-1 mb-2">Filas</h2>
                                <ul class="table-rows w-full">
                                    @foreach($elemento['values'][0] as $number => $value)
                                    <li class="flex items-center gap-3 mb-2 w-full">
                                        <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.0.{{$number}}" placeholder="Nombre de la fila" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKey('values',$index,0).'.'.$number ) ? 'border-red-600' : '' }}">
                                        <span wire:click="removeRowToGrid({{$index}},{{$number}})" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                            <i class="bi bi-x"></i>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                                <span wire:click="addRowToGrid({{$index}})" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar fila</span>
                            </section>
                        </div>
                        @break

                        @case($this->typeElements['GRID_MULTIPLY']['title'])
                        <div class="grid grid-cols-2">
                            <section class="col-span-2">
                                <h2 class="px-1 mb-2">Filas</h2>
                                <ul class="table-rows w-full">
                                    @foreach($elemento['values'][0] as $number => $value)
                                    <li class="flex items-center gap-3 mb-2 w-full">
                                        <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.0.{{$number}}" placeholder="Nombre de la fila" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has( $this->getErrorStringKey('values',$index,0).'.'.$number ) ? 'border-red-600' : '' }}">
                                        <span wire:click="removeRowToGrid({{$index}},{{$number}})" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                            <i class="bi bi-x"></i>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                                <span wire:click="addRowToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar fila</span>
                            </section>
                        </div>
                        @break

                        @case($this->typeElements['DATE']['title'])
                        <p class="text-gray-300 p-2">Dia, Mes, Año</p>
                        @break

                        @case($this->typeElements['HOUR']['title'])
                        <p class="text-gray-300 p-2">hora:minutos</p>
                        @break

                        @endswitch

                    </div>
                    <!-- Propiedades de los elmentos -->

                    <!-- Propiedades de los elmentos segunda FILA -->
                    <div class="col-span-1">
                        @switch($elemento['type'])

                        @case($this->typeElements['GRID_VERIFY']['title'])
                        <div class="grid grid-cols-2">
                            <section class="col-span-2">
                                <h2 class="px-1 mb-2">Columnas</h2>
                                <ul class="table-cols w-full">
                                    @foreach($elemento['values'][1] as $number => $value)
                                    <li class="flex items-center gap-3 mb-2 w-full">
                                        <span class="text-xl">
                                            <i class="bi bi-stop"></i>
                                        </span>
                                        <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.1.{{$number}}" placeholder="Nombre de la columna" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKey('values',$index,1).'.'.$number ) ? 'border-red-600' : '' }}">
                                        <span wire:click="removeColToGrid({{$index}},{{$number}})" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                            <i class="bi bi-x"></i>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                                <span wire:click="addColToGrid({{$index}})" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar columna</span>
                            </section>
                        </div>
                        @break

                        @case($this->typeElements['GRID_MULTIPLY']['title'])
                        <div class="grid grid-cols-2">
                            <section class="col-span-2">
                                <h2 class="px-1 mb-2">Columnas</h2>
                                <ul class="table-cols w-full">
                                    @foreach($elemento['values'][1] as $number => $value)
                                    <li class="flex items-center gap-3 mb-2 w-full">
                                        <span class="text-xl">
                                            <i class="bi bi-record"></i>
                                        </span>
                                        <input type="text" wire:model.lazy="form.elementos.{{$index}}.values.1.{{$number}}" placeholder="Nombre de la columna" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKey('values',$index,1).'.'.$number ) ? 'border-red-600' : '' }}">
                                        <span wire:click="removeColToGrid({{$index}},{{$number}})" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                            <i class="bi bi-x"></i>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                                <span wire:click="addColToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar columna</span>
                            </section>
                        </div>
                        @break

                        @endswitch
                    </div>
                    <!-- Propiedades de los elmentos  segunda FILA  -->

                    <!-- Seccion intermedia de validaciones -->
                    <div class="col-span-2">

                        <!--Validacion de los parrafos  -->
                        @if($elemento['type'] === $this->typeElements['PARAGRAPHS']['title'] && $elemento['validation'] )
                        <section class="grid gap-3 grid-cols-2">
                            <select wire:model.lazy="form.elementos.{{$index}}.validation.type" class="col-span-1 {{ $errors->has($this->getErrorStringKeyToValidations('type',$index) ) ? 'border-red-600' : '' }}">
                                <option value="minum">Cantidad minima de Caracteres</option>
                                <option value="max">Cantidad maxima de Caracteres</option>
                            </select>
                            <input type="number" wire:model.lazy="form.elementos.{{$index}}.validation.value" placeholder="Número" min="1" max="250" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                        </section>
                        @endif
                        <!--Validacion de los parrafos  -->

                        <!--Validacion de las fechas  -->
                        @if($elemento['type'] === $this->typeElements['DATE']['title'] && $elemento['validation'] )
                        <section class="grid gap-3 grid-cols-2">

                            <select wire:model.lazy="form.elementos.{{$index}}.validation.type" class="col-span-1 {{ $errors->has($this->getErrorStringKeyToValidations('type',$index) ) ? 'border-red-600' : '' }}">
                                <option value="between">Dentro del periodo escolar activo</option>
                                <option value="after">Despues de</option>
                                <option value="before">Antes de</option>
                            </select>
                            <div class="input-container col-span-1">
                                @if($elemento['validation']['type'] === 'after' || $elemento['validation']['type'] === 'before' )
                                <input type="date" wire:model.lazy="form.elementos.{{$index}}.validation.value" placeholder="Fecha" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1 {{ $errors->has($this->getErrorStringKeyToValidations('value',$index) ) ? 'border-red-600' : '' }}" />
                                @endif
                            </div>

                        </section>
                        @endif
                        <!--Validacion de las fechas  -->

                    </div>
                    <!-- Seccion intermedia de validaciones -->

                </section>
                <!-- cuerpo -->

                <!-- pie -->
                <section class="flex gap-3 justify-end footer">

                    <!-- Icono -->
                    <div wire:click="remove({{$index}})" class="delete flex justify-center items-center w-9 h-9 text-lg transition-all duration-150 text-gray-500 hover:text-red-500 cursor-pointer hover:bg-red-100 px-2 rounded-full">
                        <i class="bi bi-trash"></i>
                    </div>
                    <!-- Icono -->

                    <!-- Boton de requerido -->
                    <div x-id="['required-input']" class="check flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.lazy="form.elementos.{{$index}}.required" :id="$id('required-input')" class="hidden input-check">
                        <label :for="$id('required-input')" class="checkbox cursor-pointer"></label>
                        <label :for="$id('required-input')" class="ml-2 cursor-pointer">
                            Obligatorio
                            @if($elemento['type'] === $this->typeElements['GRID_VERIFY']['title'] || $elemento['type'] === $this->typeElements['GRID_MULTIPLY']['title'] )
                            <span>un valor por fila.</span>
                            @endif
                        </label>
                    </div>
                    <!-- Boton de requerido -->

                    <!-- Boton de validaciones -->
                    @if($this->requiredValidation($index))
                    <div x-data="{ open: false }" class="relative flex justify-center items-center">
                        <span @click="open = !open">
                            <i class="bi bi-three-dots-vertical"></i>
                        </span>
                        <ul x-show="open" @click.outside="open = false" class="absolute right-0 top-full bg-white z-40 border">
                            <li wire:click="setValidations( {{$index}} )" class=" flex px-5 py-2 hover:bg-slate-100">
                                @if($form['elementos'][$index]['validation'])
                                <i class="bi bi-check mr-2"></i>
                                @endif
                                Validaciones
                            </li>
                        </ul>
                    </div>
                    @endif
                    <!-- Boton de validaciones -->

                </section>
                <!-- pie -->

            </div>
            <!-- Bloque -->
            @endforeach

            <button wire:click="addElement" type="button" class="flex w-full justify-center items-center px-3 py-2 bg-white hover:bg-gray-100 text-gray-800 font-semibold rounded transition-all">
                <i class="bi bi-plus-circle mr-3"></i>
                <span>Agregar elmento</span>
            </button>
        </main>
        <!-- Cuerpo principal -->

    </form>
</div>