@extends('layouts.app')
@section('title', $recipe->title)

@section('content')
<div class="container" style="padding-top:1.5rem">
  <a href="{{ route('recipes.index') }}" class="back-btn">&#8592; Atpakaļ uz receptēm</a>

  <div class="recipe-detail">
    {{-- Header --}}
    <div class="recipe-detail-img">
  @if($recipe->image_path)
    <img src="{{ asset('storage/' . $recipe->image_path) }}"
         alt="{{ $recipe->title }}"
         style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg)">
  @else
    <img src="{{ asset('images/placeholder.jpg') }}"
         alt="Nav attēla"
         style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg)">
  @endif
</div>

    <div class="recipe-detail-header">
      <div>
        <div class="recipe-cat-label">{{ $recipe->category->emoji }} {{ $recipe->category->name }}</div>
        <h1 class="recipe-detail-title">{{ $recipe->title }}</h1>
        <div class="recipe-detail-meta">
          <span>👤 {{ $recipe->user->name }}</span>
          @if($recipe->cook_time)
            <span>⏱ {{ $recipe->cook_time }}</span>
          @endif
          <span>📅 {{ $recipe->created_at->format('d.m.Y') }}</span>
        </div>
      </div>
      @can('update', $recipe)
        <div style="display:flex;gap:.5rem;margin-top:.75rem">
          <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-secondary btn-sm">Rediģēt</a>
          <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                onsubmit="return confirm('Vai tiešām dzēst šo recepti?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">Dzēst</button>
          </form>
        </div>
      @endcan
    </div>

    {{-- Tags --}}
    @if($recipe->tags->count())
      <div class="recipe-tags">
        @foreach($recipe->tags as $tag)
          <a href="{{ route('recipes.index', ['search' => $tag->name]) }}" class="tag-pill">{{ $tag->name }}</a>
        @endforeach
      </div>
    @endif

    {{-- Ingredients --}}
    <div class="detail-section">
      <h3>Sastāvdaļas</h3>
      <ul class="ingredients-list">
        @foreach($recipe->ingredients as $ingredient)
          <li><span class="ing-dot"></span>{{ $ingredient }}</li>
        @endforeach
      </ul>
    </div>

    {{-- Description --}}
    <div class="detail-section">
      <h3>Pagatavošana</h3>
      <div class="recipe-description">{{ $recipe->description }}</div>
    </div>

    <hr class="divider">

    {{-- ── Ratings & Comments ── --}}
    <div class="ratings-section">
      <h2>Vērtējumi un komentāri</h2>

      {{-- Rating summary --}}
      @php
        $allComments   = $cookedComments->concat($otherComments);
        $cookedAvg     = $cookedComments->avg('rating');
        $allAvg        = $allComments->avg('rating');
        $totalCount    = $allComments->count();
      @endphp

      @if($totalCount)
        <div class="rating-summary">
          <div class="rating-big">
            <div class="rating-num">{{ number_format($allAvg, 1) }}</div>
            <div class="rating-stars">{{ str_repeat('★', round($allAvg)) }}{{ str_repeat('☆', 5 - round($allAvg)) }}</div>
            <div class="rating-count">{{ $totalCount }} vērtējum{{ $totalCount === 1 ? 's' : 'i' }}</div>
            @if($cookedComments->count())
              <div class="cooked-note">🍳 {{ $cookedComments->count() }} gatavojis</div>
            @endif
          </div>
          <div class="rating-bars">
            @foreach([5,4,3,2,1] as $star)
              @php $cnt = $allComments->where('rating', $star)->count(); $pct = $totalCount ? round($cnt / $totalCount * 100) : 0; @endphp
              <div class="rating-bar-row">
                <span>{{ $star }}</span>
                <span class="star-icon">★</span>
                <div class="rating-bar-track"><div class="rating-bar-fill" style="width:{{ $pct }}%"></div></div>
                <span>{{ $cnt }}</span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Info about the split --}}
      <div class="info-box" style="margin-bottom:1.25rem">
        🍳 <strong>Gatavojuši</strong> — vērtējumi no tiem, kuri tiešām pagatavoja šo recepti (rādīti pirmie).<br>
        👁 <strong>Apskatījuši</strong> — vērtējumi no tiem, kuri recepti vērtēja bez gatavošanas.
      </div>

      {{-- Tab buttons --}}
      <div class="rating-tabs" role="tablist">
        <button class="rating-tab active" onclick="switchTab('cooked', this)">
          🍳 Gatavojuši ({{ $cookedComments->count() }})
        </button>
        <button class="rating-tab" onclick="switchTab('tasted', this)">
          👁 Apskatījuši ({{ $otherComments->count() }})
        </button>
        <button class="rating-tab" onclick="switchTab('all', this)">
          Visi ({{ $totalCount }})
        </button>
      </div>

      {{-- Cooked comments (shown first by default) --}}
      <div id="tab-cooked">
        @forelse($cookedComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">Vēl neviens nav atstājis vērtējumu pēc gatavošanas.</p>
        @endforelse
      </div>

      <div id="tab-tasted" style="display:none">
        @forelse($otherComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">Vēl nav vērtējumu no apskatītājiem.</p>
        @endforelse
      </div>

      <div id="tab-all" style="display:none">
        @forelse($allComments as $c)
          @include('components.comment-card', ['comment' => $c])
        @empty
          <p class="no-comments">Vēl nav neviena komentāra.</p>
        @endforelse
      </div>

      {{-- Add comment form --}}
      @auth
        @if(! $userHasCommented)
          @include('components.comment-form', ['recipe' => $recipe])
        @else
          <div class="info-box">Jūs jau esat atstājis vērtējumu šai receptei. Paldies!</div>
        @endif
      @else
        <div class="add-comment-box" style="text-align:center;padding:2rem">
          <p style="color:var(--text-muted);margin-bottom:1rem">Lai komentētu vai vērtētu, lūdzu pieslēdzies.</p>
          <a href="{{ route('login') }}" class="btn btn-primary">Pieslēgties</a>
        </div>
      @endauth
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab, btn) {
  ['cooked','tasted','all'].forEach(t => {
    document.getElementById('tab-' + t).style.display = t === tab ? '' : 'none';
  });
  document.querySelectorAll('.rating-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// Star picker
document.querySelectorAll('.star-pick').forEach(star => {
  star.addEventListener('click', () => {
    const val = +star.dataset.v;
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= val);
    });
  });
  star.addEventListener('mouseover', () => {
    const val = +star.dataset.v;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= val);
    });
  });
  star.addEventListener('mouseout', () => {
    const current = +document.getElementById('ratingInput').value;
    document.querySelectorAll('.star-pick').forEach(s => {
      s.classList.toggle('lit', +s.dataset.v <= current);
    });
  });
});

// Cook answer toggle
function setCookAnswer(val) {
  document.getElementById('hasCookedInput').value = val ? '1' : '0';
  document.getElementById('cookYes').classList.toggle('yes', val);
  document.getElementById('cookNo').classList.toggle('no', !val);
  document.getElementById('cookYes').classList.toggle('no', false);
  document.getElementById('cookNo').classList.toggle('yes', false);
  if (val) {
    document.getElementById('cookYes').className = 'cook-btn yes';
    document.getElementById('cookNo').className = 'cook-btn';
  } else {
    document.getElementById('cookYes').className = 'cook-btn';
    document.getElementById('cookNo').className = 'cook-btn no';
  }
}
</script>
@endpush
