@extends('users.form.main')
@section('slot')
<livewire:forms.editor :form="$form->id" />
@endsection