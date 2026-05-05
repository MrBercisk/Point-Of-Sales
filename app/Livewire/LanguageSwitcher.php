<?php

namespace App\Livewire;

use App\Models\Settings;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public array $languages = [
        'en'  => ['label' => 'English',        'flag' => '🇬🇧'],
        'Ind'  => ['label' => 'Indonesian',     'flag' => '🇮🇩'],
        'kr'  => ['label' => '한국어',          'flag' => '🇰🇷'],
        'ja'  => ['label' => '日本語',          'flag' => '🇯🇵'],
        'fr'  => ['label' => 'Français',       'flag' => '🇫🇷'],
        'ar'  => ['label' => 'العربية',        'flag' => '🇸🇦']
    ];

    public function mount(): void
    {
        $this->currentLocale = Settings::current()->default_language ?? 'en';
    }

    public function switchLanguage(string $locale): void
    {
        // Update currentLocale dulu agar UI langsung berubah
        $this->currentLocale = $locale;

        $settings = Settings::first();
        if ($settings) {
            $settings->update(['default_language' => $locale]);
            Settings::clearCache();
        }

        App::setLocale($locale);

        // render dulu, baru redirect setelah 300ms
        $this->dispatch('language-changed', locale: $locale);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}