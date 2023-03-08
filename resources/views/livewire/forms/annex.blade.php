<div id="app" class="w-full min-h-screen m-auto bg-gray-200">
    <div class="sm:max-w-full md:max-w-2xl mx-auto pb-40">

        <div class="pt-20">
            {{-- Seccion de informacion basica del anexo --}}
            <div class="bg-white mb-10 p-3 grid grid-flow-row gap-4 shadow-md border-t-8 border-blue-600">
                {{-- Nombres y descripcion --}}
                <section class="grid gap-3 grid-cols-2">

                    {{-- Nombre corto --}}
                    <div class="col-span-2">
                        <h1 class="w-full bg-white text-5xl border-b-2 border-gray-200 py-3">{{$this->form['titulo_corto']}}</h1>
                    </div>
                    {{-- Nombre corto --}}

                    {{-- Nombre largo --}}
                    <div class="col-span-2">
                        <h3 class="w-full bg-white text-base text-gray-500 py-3">{{$this->form['titulo_largo']}}</h3>
                    </div>
                    {{-- Nombre largo --}}

                    {{-- Descripcion --}}
                    <div class="col-span-2">
                        <p class="text-gray-400">
                            {{$this->form['descripcion']}}
                        </p>
                    </div>
                    {{-- Descripcion --}}

                    {{-- Requerido --}}
                    <div class="col-span-2">
                        <p class="text-red-500 pb-2">
                            * Obligatorio
                        </p>
                    </div>
                    {{-- Requerido --}}

                </section>
                {{-- Nombres y descripcion --}}
            </div>
            {{-- Seccion de informacion basica del anexo --}}
        </div>

        @if ($errors->any())
        <div class="bg-white mb-10 p-3 grid grid-flow-row gap-2 shadow-md">
            <x-validation-errors />
        </div>
        @endif

        <form wire:submit.prevent="submit">
            @foreach($this->form['elementos'] as $index => $elemento)
            <!-- Bloque -->
            <div class="bg-white mb-10 p-3 grid grid-flow-row gap-2 {{ 
                    $errors->has('answers.'.$index.'.values') || 
                    $errors->has('answers.'.$index.'.values.*')
                    ? 
                        'border border-red-600' : 
                        'shadow-md' }}">

                @switch($elemento['type'])

                @case($this->typeElements['TEXT']['title'])
                <section class="grid gap-3 grid-cols-2 ">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 sm:col-span-2 md:col-span-1">
                        <input type="text" placeholder="Respuesta breve" wire:model.defer="answers.{{$index}}.values" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                    </div>
                </section>
                @break

                @case($this->typeElements['PARAGRAPHS']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2">
                        <textarea placeholder="Respuesta larga" wire:model.defer="answers.{{$index}}.values" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1"></textarea>
                    </div>
                </section>
                @break

                @case($this->typeElements['DATE']['title'])
                <section class="grid gap-3 grid-cols-2  ">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 sm:col-span-2 md:col-span-1">
                        <input type="date" placeholder="Respuesta breve" wire:model.defer="answers.{{$index}}.values" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                    </div>
                </section>
                @break

                @case($this->typeElements['RADIO']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 sm:col-span-2 md:col-span-1">
                        <ul class="w-full">
                            @foreach($elemento['values'] as $position => $value )
                            <li x-init="{}" x-id="['radio']" class="flex items-center gap-3 mb-2 w-full">
                                <input type="radio" wire:model.defer="answers.{{$index}}.values" name="{{$elemento['title']}}" value="{{$value}}" :id="$id('radio')" />
                                <label :for="$id('radio')">{{$value}}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
                @break

                @case($this->typeElements['CHECK']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 sm:col-span-2 md:col-span-1">
                        <ul class="w-full">
                            @foreach($elemento['values'] as $position => $value )
                            <li x-init="{}" x-id="['radio']" class="flex items-center gap-3 mb-2 w-full">
                                <input wire:model.defer="answers.{{$index}}.values.{{$position}}" value="{{$value}}" type="checkbox" name="{{$value}}" :id="$id('radio')" />
                                <label :for="$id('radio')">{{$value}}</label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
                @break

                @case($this->typeElements['GRID_VERIFY']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 overflow-auto">
                        <!-- Inicio de la tabla -->
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="first:bg-white sticky left-0"></th>
                                    @foreach($elemento['values'][1] as $header )
                                    <th>{{ $header}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($elemento['values'][0] as $rowNumber => $rowTitle )
                                <tr>
                                    <td class="p-2 border-b border-gray-300 sticky left-0 first:bg-white0">{{$rowTitle}}</td>
                                    @foreach($elemento['values'][1] as $numberColumn => $value)
                                    <td class="p-2 border-b border-gray-300">
                                        <div class="flex w-full">
                                            <input wire:model.defer="answers.{{$index}}.values.{{$rowNumber}}.{{$numberColumn}}" type="checkbox" value="{{$value}}" name="{{$rowTitle}}" class="mx-auto" />
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Inicio de la tabla -->
                    </div>
                </section>
                @break

                @case($this->typeElements['GRID_MULTIPLY']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 overflow-auto">
                        <!-- Inicio de la tabla -->
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="first:bg-white sticky left-0"></th>
                                    @foreach($elemento['values'][1] as $header )
                                    <th>{{ $header}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($elemento['values'][0] as $rowNumber => $rowTitle )
                                <tr>
                                    <td class="p-2 border-b border-gray-300 sticky left-0 first:bg-white">{{$rowTitle}}</td>
                                    @foreach($elemento['values'][1] as $numberColumn => $value)
                                    <td class="p-2 border-b border-gray-300">
                                        <div class="flex w-full">
                                            <input wire:model.defer="answers.{{$index}}.values.{{$rowNumber}}.{{$numberColumn}}" type="radio" value="{{$value}}" name="{{$rowTitle}}" class="mx-auto" />
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <!-- Inicio de la tabla -->
                    </div>
                </section>
                @break

                @case($this->typeElements['HOUR']['title'])
                <section class="grid gap-3 grid-cols-2">
                    <h2 class="col-span-2 px-2 py-1">
                        {{$elemento['title']}}
                        @if($elemento['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </h2>
                    <div class="col-span-2 sm:col-span-2 md:col-span-1">
                        <input type="time" placeholder="" name="{{$elemento['title']}}" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                    </div>
                </section>
                @break

                @endswitch
            </div>
            <!-- Bloque -->
            @endforeach

            <button type="submit" class="bg-blue-600 text-white p-2 rounded-md">Enviar respuesta.<button>
        </form>
    </div>
</div>