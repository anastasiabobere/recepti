@push('scripts')
<script>
const ingredientPlaceholder = @json(__('app.ingredient_placeholder'));

// ── Ingredients ──────────────────────────────────────────────────────────────
document.getElementById('addIngredient').addEventListener('click', () => {
  const row = document.createElement('div');
  row.className = 'ingredient-row';
  row.innerHTML = `<input type="text" name="ingredients[]" class="form-input" placeholder="${ingredientPlaceholder}">
                   <button type="button" class="btn btn-secondary btn-sm ing-remove">✕</button>`;
  document.getElementById('ingredientsList').appendChild(row);
});

document.getElementById('ingredientsList').addEventListener('click', e => {
  if (e.target.classList.contains('ing-remove')) {
    const rows = document.querySelectorAll('.ingredient-row');
    if (rows.length > 1) e.target.closest('.ingredient-row').remove();
  }
});

// ── Tags ─────────────────────────────────────────────────────────────────────
const tagsArea    = document.getElementById('tagsArea');
const tagTyper    = document.getElementById('tagTyper');
const tagsHidden  = document.getElementById('tagsHidden');
let tags = tagsHidden.value ? tagsHidden.value.split(',').map(t => t.trim()).filter(Boolean) : [];

function renderTags() {
  // Remove existing pills
  tagsArea.querySelectorAll('.tag-input-pill').forEach(p => p.remove());
  tags.forEach(tag => {
    const pill = document.createElement('span');
    pill.className = 'tag-input-pill';
    pill.innerHTML = `${tag}<span class="tag-rm" data-tag="${tag}">✕</span>`;
    tagsArea.insertBefore(pill, tagTyper);
  });
  tagsHidden.value = tags.join(', ');
}

function addTag(val) {
  const clean = val.trim().replace(/,$/, '');
  if (clean && !tags.includes(clean)) {
    tags.push(clean);
    renderTags();
  }
}
//prieviewing images berfore upload
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('imageFileName').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imagePreview').style.display = '';
    };
    reader.readAsDataURL(file);
}

tagTyper.addEventListener('keydown', e => {
  if (e.key === 'Enter' || e.key === ',') {
    e.preventDefault();
    addTag(tagTyper.value);
    tagTyper.value = '';
  }
  if (e.key === 'Backspace' && tagTyper.value === '' && tags.length) {
    tags.pop();
    renderTags();
  }
});

tagsArea.addEventListener('click', e => {
  if (e.target.classList.contains('tag-rm')) {
    const tag = e.target.dataset.tag;
    tags = tags.filter(t => t !== tag);
    renderTags();
  }
  tagTyper.focus();
});

// Initial render from existing value (edit mode)
renderTags();
</script>
@endpush
