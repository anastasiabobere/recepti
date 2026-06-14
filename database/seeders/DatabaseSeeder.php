<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrators',
            'email'    => 'admin@garsa.lv',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $anna = User::create([
            'name'     => 'Anna K.',
            'email'    => 'anna@garsa.lv',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $peteris = User::create([
            'name'     => 'Pēteris Z.',
            'email'    => 'peteris@garsa.lv',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $lauma = User::create([
            'name'     => 'Lauma B.',
            'email'    => 'lauma@garsa.lv',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // ── Categories ────────────────────────────────────────────────────
        $uncat   = Category::uncategorized();
        $deserti = Category::create(['name' => 'Deserti',         'slug' => 'deserti']);
        $zupas   = Category::create(['name' => 'Zupas',           'slug' => 'zupas']);
        $brok    = Category::create(['name' => 'Brokastis',       'slug' => 'brokastis']);
        $vegan   = Category::create(['name' => 'Vegāniskie',      'slug' => 'veganiskie']);
        $galv    = Category::create(['name' => 'Galvenie ēdieni', 'slug' => 'galvenie-edieni']);

        // ── Tags ──────────────────────────────────────────────────────────
        $tags = collect([
            'āboli','kanēlis','klasika','latvju virtuve','bietes',
            'vegāns','ātri','veselīgi','šokolāde','svētku ēdiens',
            'siļķe','rosols'
        ])->mapWithKeys(fn($name) => [
            $name => Tag::create(['name' => $name])
        ]);

        // ── Recipes ───────────────────────────────────────────────────────
        $recipe1 = Recipe::create([
            'title'       => 'Mājas ābolu pīrāgs',
            'description' => "Mīksts un sulīgs ābolu pīrāgs ar kanēļa aromātu.\n\nSagriez ābolus šķēlītēs, pārkaisi ar kanēli un cukuru. Sagatavo mīklu no miltiem, sviestas, olām un cukura. Liec formā kārtām — mīkla, āboli, mīkla. Cep 180°C 50 minūtes.",
            'ingredients' => ['500g ābolu','200g miltu','150g cukura','100g sviestas','2 olas','1 tēk. kanēļa','1 tēk. cepamā pulvera'],
            'cook_time'   => '1 st 20 min',
            'image_path'  => 'recipes/abolu-pirags.jpg',
            'user_id'     => $anna->id,
            'category_id' => $deserti->id,
        ]);
        $recipe1->tags()->attach([$tags['āboli']->id, $tags['kanēlis']->id, $tags['klasika']->id]);

        $recipe2 = Recipe::create([
            'title'       => 'Klasiskā biešu zupa',
            'description' => "Tradicionālā latviešu biešu zupa ar krējumu un dillēm.\n\nNomizo bietes un burkānus, sagriez kubiņos. Uz vidējas uguns apcep sīpolus. Pievieno dārzeņus un ūdeni, vāri 30 min. Sezonē pēc garšas.",
            'ingredients' => ['4 bietes','2 burkāni','1 sīpols','3 kartupeļi','Skābs krējums','Dilles','Sāls, pipari'],
            'cook_time'   => '45 min',
            'image_path'  => 'recipes/biesu-zupa.jpg',
            'user_id'     => $peteris->id,
            'category_id' => $zupas->id,
        ]);
        $recipe2->tags()->attach([$tags['latvju virtuve']->id, $tags['bietes']->id]);

        $recipe3 = Recipe::create([
            'title'       => 'Auzu pārslu biezputra ar ogām',
            'description' => "Ātras un veselīgas brokastis jebkurai dienai.\n\nUzvāri ūdeni vai pienu, pievieno auzu pārslas, vāri 5 min. Pasniedz ar svaigām ogām un medu.",
            'ingredients' => ['80g auzu pārslu','250ml piena','Sezonālas ogas','1 ēd. k. medus','Šķipsniņa sāls'],
            'cook_time'   => '10 min',
            'image_path'  => 'recipes/biezputra.jpg',
            'user_id'     => $anna->id,
            'category_id' => $brok->id,
        ]);
        $recipe3->tags()->attach([$tags['veselīgi']->id, $tags['ātri']->id]);

        $recipe4 = Recipe::create([
            'title'       => 'Cepti dārzeņi ar aunazirņiem',
            'description' => "Krāsains un barojošs vegāniskais ēdiens.\n\nSagriez dārzeņus, apkaisi ar olīveļļu un garšvielām. Cep 200°C 25 min. Uzkaisi vārītus aunazirņus.",
            'ingredients' => ['2 cukini','1 sarkanā paprika','1 sarkansīpols','400g aunazirņu (konservi)','3 ēd. k. olīveļļas','Kurkuma, paprika, ķimenes'],
            'cook_time'   => '35 min',
            'image_path'  => 'recipes/darzeni.jpg',
            'user_id'     => $lauma->id,
            'category_id' => $vegan->id,
        ]);
        $recipe4->tags()->attach([$tags['vegāns']->id, $tags['veselīgi']->id]);

        $recipe5 = Recipe::create([
            'title'       => 'Siļķe kažokā',
            'description' => "Klasiskā Jaungada salātu recepte.\n\nSavāri dārzeņus. Sagriez siļķi. Krāso kārtas bļodā: kartupeļi, siļķe, burkāns, biete, majonēze. Atdzesē 2 stundas.",
            'ingredients' => ['2 siļķes fileja','3 vārīti kartupeļi','2 vārīti burkāni','2 vārītas bietes','Majonēze','Sīpols'],
            'cook_time'   => '30 min + 2 st atdzišanai',
            'image_path'  => 'recipes/silke-kazoka.jpg',
            'user_id'     => $peteris->id,
            'category_id' => $galv->id,
        ]);
        $recipe5->tags()->attach([$tags['latvju virtuve']->id, $tags['siļķe']->id, $tags['svētku ēdiens']->id, $tags['rosols']->id]);

        $recipe6 = Recipe::create([
            'title'       => 'Šokolādes brauniji',
            'description' => "Mitri un bagātīgi šokolādes brauniji.\n\nIzkausē šokolādi ar sviestu. Sajauc cukuru, olas, vanilju. Pievieno miltus. Cep 175°C 25 min. Svarīgi: neapcept par daudz!",
            'ingredients' => ['200g tumšās šokolādes','150g sviestas','200g cukura','3 olas','100g miltu','30g kakao','1 tēk. vaniljes'],
            'cook_time'   => '40 min',
            'image_path'  => 'recipes/brauniji.jpg',
            'user_id'     => $lauma->id,
            'category_id' => $deserti->id,
        ]);
        $recipe6->tags()->attach([$tags['šokolāde']->id, $tags['klasika']->id]);

        // ── Comments ──────────────────────────────────────────────────────
        Comment::create([
            'recipe_id' => $recipe1->id, 'user_id' => $peteris->id,
            'rating' => 5, 'has_cooked' => true,
            'content' => 'Gatavoju pirmo reizi un iznāca lieliski! Skābos ābolus iesaku — labāk līdzsvaro saldumu.',
        ]);
        Comment::create([
            'recipe_id' => $recipe1->id, 'user_id' => $lauma->id,
            'rating' => 4, 'has_cooked' => false,
            'content' => 'Fotogrāfija izskatās ļoti kārdinoši. Noteikti izmēģināšu nākamajā nedēļā!',
        ]);
        Comment::create([
            'recipe_id' => $recipe2->id, 'user_id' => $anna->id,
            'rating' => 5, 'has_cooked' => true,
            'content' => 'Manas vecmāmiņas recepte bija gandrīz tāda pati. Atgādina bērnību!',
        ]);
        Comment::create([
            'recipe_id' => $recipe5->id, 'user_id' => $anna->id,
            'rating' => 5, 'has_cooked' => true,
            'content' => 'Tā arī mans tētis gatavoja visu mūžu. Neko nemainītu.',
        ]);
        Comment::create([
            'recipe_id' => $recipe5->id, 'user_id' => $lauma->id,
            'rating' => 3, 'has_cooked' => false,
            'content' => 'Izskatās sarežģīti, bet mēģināšu nākamajā brīvdienā!',
        ]);
        Comment::create([
            'recipe_id' => $recipe4->id, 'user_id' => $peteris->id,
            'rating' => 4, 'has_cooked' => true,
            'content' => 'Ļoti garšīgi! Pievienoju citronu sulas galam — uzmundrina garšu.',
        ]);
    }
}