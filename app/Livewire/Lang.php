<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class Lang extends Component
{
    public string $lang;

    public function mount()
    {
        $this->lang = App::currentLocale();
    }

    public function change(string $lang)
    {
        App::setLocale($lang);
        Cookie::queue('locale', $lang, 60*24*30*3);
        $this->dispatch('locale-changed', ['lang' => $lang]);
    }

    public function render()
    {
        return view('livewire.lang');
    }
}