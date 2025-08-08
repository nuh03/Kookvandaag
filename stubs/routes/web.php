<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/healthz', fn() => response('ok', 200));

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Sitemap: ' . config('app.url') . '/sitemap.xml',
    ];
    return new Response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
});

Route::get('/ads.txt', function () {
    // In productie kun je dit uit DB of storage laden
    $content = file_exists(public_path('ads.txt'))
        ? file_get_contents(public_path('ads.txt'))
        : 'google.com, pub-xxxxxxxxxxxxxxxx, DIRECT, f08c47fec0942fa0';
    return new Response($content, 200, ['Content-Type' => 'text/plain']);
});

Route::get('/amp/recepten/{slug}', function (string $slug) {
    // Placeholder data; vervang door DB lookup
    $recipe = [
        'slug' => $slug,
        'title' => 'Recept: ' . ucwords(str_replace('-', ' ', $slug)),
        'image' => url('/images/placeholder-800x450.jpg'),
        'ingredients' => ['200 g pasta', '2 tomaten', '1 ui', '1 el olijfolie', 'zout', 'peper'],
        'steps' => ['Kook de pasta', 'Fruit de ui en tomaat', 'Meng en breng op smaak'],
        'meta' => 'Bereiding 30 min · 2 porties',
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Recipe',
        'name' => $recipe['title'],
        'image' => [$recipe['image']],
        'recipeIngredient' => $recipe['ingredients'],
        'recipeInstructions' => array_map(fn($t) => ['@type' => 'HowToStep', 'text' => $t], $recipe['steps']),
    ];

    return response()->view('amp.recipe', [
        'recipe' => $recipe,
        'jsonld' => $jsonld,
        'canonical' => url('/recepten/' . $slug),
        'ad_slot' => '1234567890',
    ])->header('Content-Type', 'text/html');
});