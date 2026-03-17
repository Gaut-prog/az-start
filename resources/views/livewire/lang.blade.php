<div class="flex items-center space-x-3">
    @if ($lang == 'fr')
        <x-button label="FR" class="btn-square btn-primary" />
        <x-button label="EN" class="btn-square" wire:click="change('en')" />
    @else
        <x-button label="FR" class="btn-square" wire:click="change('fr')" />
        <x-button label="EN" class="btn-square btn-primary" />
    @endif
    <x-theme-toggle class="btn" />
</div>
