<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{route('forms.store')}}" method="post">
                    @csrf
                    <x-button>Agregra formulario</x-button>
                </form>

                <ul>
                    @foreach($formularios as $form)
                    <li>
                        <a href="{{route('forms.edit',$form->id)}}">{{$form->id}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>