@extends('users.form.main')
@section('slot')
<div id="app" class="w-full min-h-screen m-auto bg-gray-200">

    <form id="form" x-data="recorder" @submit.prevent="submit(event,'{{route('forms.update', $form->id)}}')" class="w-full">

        <header class="w-full flex bg-white shadow-md px-16 py-5">
            <div class="w-full flex truncate">
                <h1 x-text="data.titulo_corto.titulo" class="font-medium flex items-center">
                </h1>
            </div>
            <nav class="w-full flex justify-end">
                <ul class="flex items-center gap-2">
                    <li :class="saved ? 'block' : 'hidden' ">
                        <a href="#" class="text-green-300 text-xs font-semibold">
                            Guardado
                        </a>
                    </li>

                    <!-- Visualizacion -->
                    <li>
                        <a href="#" class=" flex items-center justify-center transition-all duration-150 p-1 text-lg text-gray-800 hover:bg-gray-300 rounded-full w-10 h-10">
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
                            <ul x-show="open" @click.outside="open = false" class="w-full absolute right-0 top-full bg-white z-50 border">
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

        <!-- Cuerpo principal -->
        <main x-data="editor($el)" x-init="$watch('elements', ele => data.elementos = ele )"  class="sm:max-w-full md:max-w-xl mx-auto pb-40 pt-16">

            <!-- Seccion de informacion basica del anexo--->
            <div class="bg-white mb-10 p-3 grid grid-flow-row gap-2 shadow-md">
                <!-- Nombres y descripcion -->
                <section class="grid gap-3 grid-cols-2">
                    <div class="col-span-2">
                        <h2 class="w-full text-gray-800 font-medium p-2">Nombres y descripción.</h2>
                    </div>

                    <!-- Nombre corto -->
                    <div class="col-span-2">
                        <input type="text" x-model="data.titulo_corto.titulo" placeholder="Nombre corto" class="w-full bg-white text-xl rounded p-2 border focus:outline-none focus:border-blue-500 " />
                        <template x-if="data.titulo_corto.error">
                            <p x-text="data.titulo_corto.error" class="w-full text-red-400 px-2"></p>
                        </template>
                    </div>
                    <!-- Nombre corto -->

                    <!-- Nombre largo -->
                    <div class="col-span-2">
                        <input type="text" x-model="data.titulo_largo.titulo" placeholder="Nombre largo" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500" />
                        <template x-if="data.titulo_largo.error">
                            <p x-text="data.titulo_largo.error" class="w-full text-red-400 px-2"></p>
                        </template>
                    </div>
                    <!-- Nombre largo -->

                    <!-- Descripcion -->
                    <div class="col-span-2">
                        <textarea x-model="data.descripcion.titulo" placeholder="Descripción" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500"></textarea>
                        <template x-if="data.descripcion.error">
                            <p x-text="data.descripcion.error" class="w-full text-red-400 px-2"></p>
                        </template>
                    </div>
                    <!-- Descripcion -->

                </section>
                <!-- Nombres y descripcion -->
            </div>
            <!-- Seccion de informacion basica del anexo--->


            <template x-for="element, index in elements" key="element.position">

                <!-- Bloque -->
                <div :data-id="index" class="container-info bg-white mb-10 p-3 grid grid-flow-row gap-2 shadow-md">
                    <section class="drag grid-cols-2">
                        <div class="w-full flex justify-center text-slate-300 hover:text-slate-700 cursor-move">
                            <i class="bi bi-grip-horizontal"></i>
                        </div>
                    </section>

                    <!-- seccion de errores-->
                    <template x-if="element.errors">
                        <section class="grid-cols-2">
                            <p x-text="element.errors" class="w-full text-red-400 px-2"></p>
                        </section>
                    </template>
                    <!-- seccion de errores-->

                    <!-- Principal -->
                    <section class="grid gap-3 grid-cols-2">
                        <input type="text" placeholder="Pregunta" x-model="element.title" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                        <!-- Selector -->
                        <div x-data="{ open: false }" class="col-span-1 relative flex">
                            <p @click="open = !open" class="w-full text-center flex justify-between items-center px-4 border">
                                <span x-text="(element.type ?? 'Seleccione el tipo de entrada')"></span>
                                <i class="bi bi-chevron-down"></i>
                            </p>

                            <!-- Elementos de selector -->
                            <ul x-show="open" @click.outside="open = false" class="w-full absolute left-0 top-full bg-white z-50 border">
                                <template x-for="type in TYPE_INPUTS">
                                    <li @click="setType(event,element,type.title)" class="px-5 py-2 hover:bg-slate-100">
                                        <i :class="type.icon"></i>
                                        <a href="#" x-text="type.title">
                                        </a>
                                    </li>
                                </template>
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

                            <template x-if=" element.type === TYPE_INPUTS.TEXT.title ">
                                <p class="text-gray-300 p-2">Texto de respuesta breve</p>
                            </template>

                            <template x-if=" element.type === TYPE_INPUTS.PARAGRAPHS.title ">
                                <p class="text-gray-300 p-2">Texto de respuesta largo</p>
                            </template>

                            <template x-if=" element.type === TYPE_INPUTS.DATE.title ">
                                <p class="text-gray-300 p-2">Dia, Mes, Año</p>
                            </template>

                            <template x-if="element.type === TYPE_INPUTS.RADIO.title ">

                                <div class="">
                                    <ul class="radio-option w-full">
                                        <template x-for="value, number in element.values">
                                            <li class="flex items-center gap-3 mb-2 w-full">
                                                <span class="text-xl">
                                                    <i class="bi bi-record"></i>
                                                </span>
                                                <input type="text" x-model="element.values[number]" placeholder="opcion" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                <span @click="removeValue(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                    <i class="bi bi-x"></i>
                                                </span>
                                            </li>
                                        </template>
                                    </ul>
                                    <span @click="addValues(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar opcion</span>
                                </div>

                            </template>

                            <template x-if="element.type === TYPE_INPUTS.CHECK.title ">

                                <div class="">
                                    <ul class="radio-option w-full">
                                        <template x-for="value, number in element.values">
                                            <li class="flex items-center gap-3 mb-2 w-full">
                                                <span class="text-xl">
                                                    <i class="bi bi-stop"></i>
                                                </span>
                                                <input type="text" x-model="element.values[number]" placeholder="opcion" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                <span @click="removeValue(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                    <i class="bi bi-x"></i>
                                                </span>
                                            </li>
                                        </template>
                                    </ul>
                                    <span @click="addValues(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar opcion</span>
                                </div>
                            </template>

                            <template x-if="element.type === TYPE_INPUTS.GRID_VERIFY.title">
                                <div class="grid grid-cols-2">
                                    <section class="col-span-2">
                                        <h2 class="px-1 mb-2">Filas</h2>
                                        <ul class="table-rows w-full">
                                            <template x-for="value, number in element.values[0]">
                                                <li class="flex items-center gap-3 mb-2 w-full">
                                                    <input type="text" x-model="element.values[0][number]" placeholder="Nombre de la fila" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                    <span @click="removeRowToGrid(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                        <span @click="addRowToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar fila</span>
                                    </section>
                                </div>
                            </template>

                            <template x-if="element.type === TYPE_INPUTS.GRID_MULTIPLY.title">
                                <div class="grid grid-cols-2">
                                    <section class="col-span-2">
                                        <h2 class="px-1 mb-2">Filas</h2>
                                        <ul class="table-rows w-full">
                                            <template x-for="value, number in element.values[0]">
                                                <li class="flex items-center gap-3 mb-2 w-full">
                                                    <input type="text" x-model="element.values[0][number]" placeholder="Nombre de la fila" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                    <span @click="removeRowToGrid(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                        <span @click="addRowToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar fila</span>
                                    </section>
                                </div>
                            </template>

                            <template x-if="element.type === TYPE_INPUTS.HOUR.title">
                                <p class="text-gray-300 p-2">hora:minutos</p>
                            </template>

                        </div>
                        <!-- Propiedades de los elmentos -->

                        <!-- Propiedades de los elmentos segunda FILA -->
                        <div class="col-span-1">
                            <template x-if="element.type === TYPE_INPUTS.GRID_VERIFY.title">
                                <div class="grid grid-cols-2">
                                    <section class="col-span-2">
                                        <h2 class="px-1 mb-2">Columnas</h2>
                                        <ul class="table-cols w-full">
                                            <template x-for="value, number in element.values[1]">
                                                <li class="flex items-center gap-3 mb-2 w-full">
                                                    <span class="text-xl">
                                                        <i class="bi bi-stop"></i>
                                                    </span>
                                                    <input type="text" x-model="element.values[1][number]" placeholder="Nombre de la columna" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                    <span @click="removeColToGrid(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                        <span @click="addColToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar columna</span>
                                    </section>
                                </div>
                            </template>

                            <template x-if="element.type === TYPE_INPUTS.GRID_MULTIPLY.title">
                                <div class="grid grid-cols-2">
                                    <section class="col-span-2">
                                        <h2 class="px-1 mb-2">Columnas</h2>
                                        <ul class="table-cols w-full">
                                            <template x-for="value, number in element.values[1]">
                                                <li class="flex items-center gap-3 mb-2 w-full">
                                                    <span class="text-xl">
                                                        <i class="bi bi-record"></i>
                                                    </span>
                                                    <input type="text" x-model="element.values[1][number]" placeholder="Nombre de la columna" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1">
                                                    <span @click="removeColToGrid(element,number)" class="cursor-pointer hover:bg-red-300 hover:text-white">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                        <span @click="addColToGrid(element)" class="text-sm font-medium text-blue-600 px-2 cursor-pointer">Agregar columna</span>
                                    </section>
                                </div>
                            </template>
                        </div>
                        <!-- Propiedades de los elmentos  segunda FILA  -->

                        <!-- Seccion intermedia de validaciones -->
                        <div class="col-span-2">

                            <!--Validacion de los parrafos  -->
                            <template x-if=" element.type === TYPE_INPUTS.PARAGRAPHS.title && element.validation !== null">
                                <section class="grid gap-3 grid-cols-2">
                                    <select class="col-span-1" x-model="element.validation.type">
                                        <option value="minum">Cantidad minima de Caracteres</option>
                                        <option value="max">Cantidad maxima de Caracteres</option>
                                    </select>
                                    <input type="number" x-model="element.validation.value" placeholder="Número" min="1" max="250" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                                </section>
                            </template>
                            <!--Validacion de los parrafos  -->

                            <!--Validacion de las fechas  -->
                            <template x-if=" element.type === TYPE_INPUTS.DATE.title && element.validation !== null">
                                <section class="grid gap-3 grid-cols-2">

                                    <select class="col-span-1" x-model="element.validation.type">
                                        <option value="between">Dentro del periodo escolar activo</option>
                                        <option value="after">Despues de</option>
                                        <option value="before">Antes de</option>
                                    </select>
                                    <div class="input-container col-span-1">
                                        <template x-if="element.validation.type === 'after' || element.validation.type === 'before'">
                                            <input type="date" x-model="element.validation.value" placeholder="Fecha" class="w-full bg-white rounded p-2 border focus:outline-none focus:border-blue-500 col-span-1" />
                                        </template>
                                    </div>

                                </section>
                            </template>
                            <!--Validacion de las fechas  -->

                        </div>
                        <!-- Seccion intermedia de validaciones -->

                    </section>
                    <!-- cuerpo -->

                    <!-- pie -->
                    <section class="flex gap-3 justify-end footer">

                        <!-- Icono -->
                        <div @click="remove(index)" class="delete flex justify-center items-center w-9 h-9 text-lg transition-all duration-150 text-gray-500 hover:text-red-500 cursor-pointer hover:bg-red-100 px-2 rounded-full">
                            <i class="bi bi-trash"></i>
                        </div>
                        <!-- Icono -->

                        <!-- Boton de requerido -->
                        <div x-id="['required-input']" class="check flex items-center cursor-pointer">
                            <input type="checkbox" x-model="element.required" :id="$id('required-input')" class="hidden input-check">
                            <label :for="$id('required-input')" class="checkbox cursor-pointer"></label>
                            <label :for="$id('required-input')" class="ml-2 cursor-pointer">
                                Obligatorio
                                <template x-if="element.type === TYPE_INPUTS.GRID_VERIFY.title || element.type === TYPE_INPUTS.GRID_MULTIPLY.title">
                                    <span>un valor por fila.</span>
                                </template>
                            </label>
                        </div>
                        <!-- Boton de requerido -->

                        <!-- Boton de validaciones -->
                        <template x-if="requireValidation(element)">
                            <div x-data="{ open: false }" class="relative flex justify-center items-center">
                                <span @click="open = !open">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </span>
                                <ul x-show="open" @click.outside="open = false" class="absolute right-0 top-full bg-white z-50 border">
                                    <li @click="setValidations(element)" class=" flex px-5 py-2 hover:bg-slate-100">
                                        <template x-if="element.validation">
                                            <i class="bi bi-check mr-2"></i>
                                        </template>
                                        Validaciones
                                    </li>
                                </ul>
                            </div>
                        </template>
                        <!-- Boton de validaciones -->

                    </section>
                    <!-- pie -->

                </div>
                <!-- Bloque -->

            </template>

            <button @click="addElement();" type="button" class="flex w-full justify-center items-center px-3 py-2 bg-white hover:bg-gray-100 text-gray-800 font-semibold rounded transition-all">
                <i class="bi bi-plus-circle mr-3"></i>
                <span>Agregar elmento</span>
            </button>
            <pre x-text="JSON.stringify(elements, null, '\t')"></pre>
        </main>
        <!-- Cuerpo principal -->

    </form>
</div>
@endsection