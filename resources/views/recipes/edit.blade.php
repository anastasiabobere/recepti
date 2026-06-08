@extends('layouts.app')
@section('title', 'Rediģēt: ' . $recipe->title)

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div class="form-page">
    <a href="{{ route('recipes.show', $recipe) }}" class="back-btn">&#8592; Atpakaļ uz recepti</a>
    <h2>Rediģēt: {{ $recipe->title }}</h2>

    <form method="POST" action="{{ route('recipes.update', $recipe) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      @include('recipes._form', ['recipe' => $recipe])
      <div class="form-actions">
        <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-secondary">Atcelt</a>
        <button type="submit" class="btn btn-primary">Saglabāt izmaiņas</button>
      </div>
    </form>
  </div>
</div>
@endsection

@include('recipes._form_scripts')
