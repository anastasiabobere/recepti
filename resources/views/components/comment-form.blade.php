<div class="add-comment-box">
  <h4>Atstāt vērtējumu</h4>

  @if($errors->any())
    <div class="flash flash-error" style="margin-bottom:1rem">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('comments.store', $recipe) }}" id="commentForm">
    @csrf

    {{-- KEY QUESTION: Did you cook it? (professor's requirement) --}}
    <div class="cook-question">
      <span class="cook-icon">🤔</span>
      <div>
        <div style="font-weight:500;margin-bottom:4px">Vai Tu pats(-i) gatavoji šo recepti?</div>
        <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px">
          Tie, kas gatavoja, tiks rādīti pirmie — jo viņu vērtējums ir balstīts pieredzē!
        </div>
        <div class="cook-toggle">
          <button type="button" class="cook-btn" id="cookYes" onclick="setCookAnswer(true)">
            ✅ Jā, gatavoju!
          </button>
          <button type="button" class="cook-btn" id="cookNo" onclick="setCookAnswer(false)">
            👀 Nē, vērtēju pēc receptes
          </button>
        </div>
      </div>
    </div>
    {{-- Hidden field submitted to server --}}
    <input type="hidden" name="has_cooked" id="hasCookedInput" value="">

    {{-- Star rating --}}
    <div class="form-group">
      <label class="form-label">Vērtējums *</label>
      <div class="star-picker" id="starPicker">
        @for($i = 1; $i <= 5; $i++)
          <span class="star-pick" data-v="{{ $i }}">★</span>
        @endfor
      </div>
      <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">
    </div>

    {{-- Comment text --}}
    <div class="form-group">
      <label class="form-label" for="content">Komentārs *</label>
      <textarea name="content" id="content" class="form-textarea"
                placeholder="Dalies ar savām domām par recepti...">{{ old('content') }}</textarea>
    </div>

    <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">
      Publicēt vērtējumu
    </button>
  </form>
</div>

@push('scripts')
<script>
// Validate before submit — ensure cook answer and rating are set
function validateAndSubmit() {
  const hasCookedVal = document.getElementById('hasCookedInput').value;
  const ratingVal    = document.getElementById('ratingInput').value;

  if (hasCookedVal === '') {
    alert('Lūdzu atbildi — vai tu gatavoji šo recepti?');
    return;
  }
  if (!ratingVal || ratingVal === '0') {
    alert('Lūdzu izvēlies vērtējumu (1–5 zvaigznes)!');
    return;
  }
  document.getElementById('commentForm').submit();
}
</script>
@endpush
