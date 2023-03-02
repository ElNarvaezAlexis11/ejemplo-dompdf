@extends('users.form.main')
@section('slot')
<livewire:forms.preview :formModel="$form->id" />
@endsection