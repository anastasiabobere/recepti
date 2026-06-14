@extends('layouts.app')
@section('title', __('app.add_recipe_page'))

@section('content')
<div class="container" style="padding-top:1.5rem">
  <div class="form-page">
    <a href="{{ route('recipes.index') }}" class="back-btn">{{ __('app.back') }}</a>
    <h2>{{ __('app.add_recipe_title') }}</h2>

<form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">      @csrf
      @include('recipes._form', ['recipe' => null])
      <div class="form-actions">
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary">{{ __('app.cancel') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('app.save_recipe') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection

@include('recipes._form_scripts')
