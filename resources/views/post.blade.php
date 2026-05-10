@extends('layouts.main')

@section('content')
<livewire:post :id="request()->route('id')"/>
@endsection