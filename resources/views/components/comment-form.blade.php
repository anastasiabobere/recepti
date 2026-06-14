<div class="add-comment-box">
  <h4>{{ __('app.leave_rating') }}</h4>

  @if($errors->any())
    <div class="flash flash-error" style="margin-bottom:1rem">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('comments.store', $recipe) }}" id="commentForm">
    @csrf

    {{-- KEY QUESTION: Did you cook it? (professor's requirement) --}}
    <div class="cook-question">
      <div>
        <div style="font-weight:500;margin-bottom:4px">{{ __('app.did_you_cook') }}</div>
        <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px">
          {{ __('app.cook_explanation') }}
        </div>
        <div class="cook-toggle">
          <button type="button" class="cook-btn" id="cookYes" onclick="setCookAnswer(true)">
            {{ __('app.yes_cooked') }}
          </button>
          <button type="button" class="cook-btn" id="cookNo" onclick="setCookAnswer(false)">
            {{ __('app.no_cooked') }}
          </button>
        </div>
      </div>
    </div>
    {{-- Hidden field submitted to server --}}
    <input type="hidden" name="has_cooked" id="hasCookedInput" value="">

    {{-- Star rating --}}
    <div class="form-group">
      <label class="form-label">{{ __('app.rating') }} *</label>
      <div class="star-picker" id="starPicker">
        @for($i = 1; $i <= 5; $i++)
          <span class="star-pick" data-v="{{ $i }}">★</span>
        @endfor
      </div>
      <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 0) }}">
    </div>

    {{-- Comment text --}}
    <div class="form-group">
      <label class="form-label" for="content">{{ __('app.comment') }} *</label>
      <textarea name="content" id="content" class="form-textarea"
                placeholder="{{ __('app.comment_placeholder') }}">{{ old('content') }}</textarea>
    </div>

    <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">
      {{ __('app.publish_rating') }}
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
    alert(@json(__('app.validate_cook_answer')));
    return;
  }
  if (!ratingVal || ratingVal === '0') {
    alert(@json(__('app.validate_rating')));
    return;
  }
  document.getElementById('commentForm').submit();
}
</script>
@endpush
