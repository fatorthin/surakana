<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsEditor extends Component
{
    public string $hero_title = '';
    public string $hero_subtitle = '';
    public string $about_text = '';
    public string $contact_whatsapp = '';
    public string $contact_instagram = '';
    public string $faq = '';

    public bool $saved = false;

    public function mount(): void
    {
        $settings = Setting::query()->pluck('value', 'key');

        $this->hero_title        = $settings->get('hero_title', '');
        $this->hero_subtitle     = $settings->get('hero_subtitle', '');
        $this->about_text        = $settings->get('about_text', '');
        $this->contact_whatsapp  = $settings->get('contact_whatsapp', '');
        $this->contact_instagram = $settings->get('contact_instagram', '');
        $this->faq               = $settings->get('faq', '');
    }

    public function save(): void
    {
        $this->validate([
            'hero_title'        => ['nullable', 'string', 'max:255'],
            'hero_subtitle'     => ['nullable', 'string'],
            'about_text'        => ['nullable', 'string'],
            'contact_whatsapp'  => ['nullable', 'string', 'max:50'],
            'contact_instagram' => ['nullable', 'string', 'max:255'],
            'faq'               => ['nullable', 'string'],
        ]);

        $fields = [
            'hero_title',
            'hero_subtitle',
            'about_text',
            'contact_whatsapp',
            'contact_instagram',
            'faq',
        ];

        foreach ($fields as $key) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $this->$key]);
        }

        $this->saved = true;
        $this->js('setTimeout(() => $wire.set("saved", false), 3000)');
    }

    public function render()
    {
        return view('livewire.admin.settings-editor');
    }
}
