<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MtrBahasa;
use Illuminate\Support\Facades\Lang;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFromDatabase();
    }

    protected function loadTranslationsFromDatabase()
    {
        $language = app()->getLocale();
        $translations = MtrBahasa::where('bahasa', $language)->get();
        $translationsArray = [];

        foreach ($translations as $translation) {
            $translationsArray[$translation->key] = $translation->value;
        }
        // dd($translationsArray);

        // Tambahkan terjemahan ke Laravel's Lang
        Lang::addLines($translationsArray, 'id');
        // foreach ($translations as $translation) {
        //     // dd($translation->key, $translation->value);
        //     Lang::addLines([
        //         $translation->key => $translation->value
        //     ], $language, false);
        // }
    }
}
