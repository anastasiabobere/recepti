@extends('layouts.app')
@section('title', __('app.edit_recipe_title', ['title' => $recipe->title]))

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div class="form-page">
    <a href="{{ route('recipes.show', $recipe) }}" class="back-btn">{{ __('app.back_to_recipe') }}</a>
    <h2>{{ __('app.edit_recipe_title', ['title' => $recipe->title]) }}</h2>

<form method="POST" action="{{ route('recipes.update', $recipe) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      @include('recipes._form', ['recipe' => $recipe])
      <div class="form-actions">
        <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('app.save_changes') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection

@include('recipes._form_scripts')
