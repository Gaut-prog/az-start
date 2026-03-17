<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Notification extends Component
{
    use Toast;

    public function mount()
    {
        if($notif = session()->get('notification')){
            $this->dispatch('notification', $notif);
        }
    }

    #[On('notification')]
    public function handle($params)
    {
        $type = $params['type'] ?? '';

        if(in_array($type, ['success', 'error', 'warning', 'info'])){
            $this->$type($params['title'] ?? '', $params['description'] ?? null, timeout: 6000);
        }
    }
    
}