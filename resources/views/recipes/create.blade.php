@extends('layouts.app')
@section('title', 'Pievienot recepti')

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div class="form-page">
    <a href="{{ route('recipes.index') }}" class="back-btn">&#8592; Atpakaļ</a>
    <h2>Pievienot jaunu recepti</h2>

<form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">      @csrf
      @include('recipes._form', ['recipe' => null])
      <div class="form-actions">
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Atcelt</a>
        <button type="submit" class="btn btn-primary">Saglabāt recepti</button>
      </div>
    </form>
  </div>
</div>
@endsection

@include('recipes._form_scripts')
