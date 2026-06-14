<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslationController extends Controller
{
    public function translate(Request $request, Recipe $recipe): JsonResponse
    {
        $target = $request->query('target', app()->getLocale());

        if (! in_array($target, ['en', 'lv'], true)) {
            return response()->json(['error' => 'Invalid target language.'], 400);
        }

        $source = $recipe->detectLanguage();

        if ($source === $target) {
            return response()->json(['error' => 'Recipe is already in the target language.'], 400);
        }

        $translatedTitle       = $this->translateText($recipe->title, $source, $target);
        $translatedDescription = $this->translateText($recipe->description, $source, $target);

        $separator = ' | ';
        $combinedIngredients = implode($separator, $recipe->ingredients);
        $translatedCombinedIngredients = $this->translateText($combinedIngredients, $source, $target);

        $translatedIngredients = array_map('trim', explode($separator, $translatedCombinedIngredients));

        if (count($translatedIngredients) !== count($recipe->ingredients)) {
            $translatedIngredients = $recipe->ingredients;
        }

        return response()->json([
            'title'       => $translatedTitle,
            'description' => $translatedDescription,
            'ingredients' => $translatedIngredients,
        ]);
    }

    private function translateText(string $text, string $from, string $to): string
    {
        if (trim($text) === '') {
            return $text;
        }

        if (mb_strlen($text) > 480) {
            $text = mb_substr($text, 0, 480);
        }

        $response = Http::timeout(15)->withoutVerifying()->get('https://api.mymemory.translated.net/get', [
            'q'        => $text,
            'langpair' => "{$from}|{$to}",
        ]);

        if ($response->successful()) {
            return $response->json('responseData.translatedText', $text);
        }

        return $text;
    }
}
