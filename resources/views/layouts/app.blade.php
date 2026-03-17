<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        @if (Route::is('login'))
            {{ $slot }}
        @else
            {{-- The navbar with `sticky` and `full-width` --}}
            <x-nav sticky full-width>
        
                <x-slot:brand>
                    {{-- Drawer toggle for "main-drawer" --}}
                    <label for="main-drawer" class="lg:hidden mr-3">
                        <x-icon name="o-bars-3" class="cursor-pointer" />
                    </label>
        
                    {{-- Brand --}}
                    <div>{{ config('app.name') }}</div>
                </x-slot:brand>
        
                {{-- Right side actions --}}
                <x-slot:actions>
                    <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
                    <x-dropdown class="min-w-60">
                        <x-slot:trigger>
                            <x-button icon="o-user" class="btn-circle btn-outline btn-sm" />
                        </x-slot:trigger>
                        @if($user = auth()->user())
                            <x-menu>
                                <b>{{ $user->name }}</b>
                                <span class="text-xs text-gray-400">{{ $user->userN }}</span>
                            </x-menu>
                            <x-menu-separator />
                            <livewire:lang />
                            <x-menu-separator />
                            {{-- Here is the switch of the company --}}
                            @if ($user->company_account_connected && count($user->linked_companies) > 1)
                                <x-menu-sub :title="__('Changer d\'entreprise')" icon="s-arrow-path">
                                    @foreach ($user->linked_companies as $key => $linked_company)
                                        @if ($linked_company->Customers_Numbers != $user->company_account_connected)
                                            <x-menu-item :title="$linked_company->Names" icon="o-building-office-2" :link="route('switch_company', $linked_company->Customers_Numbers)" />
                                        @else
                                            <x-menu-item icon="s-light-bulb" class="text-green-600 hover:text-green-400 animate-pulse">
                                                {{ $linked_company->Names }}
                                                <p class="italic ml-3 text-xs">{{ __("Connecté") }}</p>
                                            </x-menu-item>
                                        @endif
                                    @endforeach
                                </x-menu-sub>
                            @endif
                            <x-menu-item :title="__('actions.log_out')" icon="o-arrow-left-start-on-rectangle" class="text-red-600 hover:text-red-500" :link="route('logout')" />
                        @else
                            <x-menu-item :title="__('actions.log_in')" :link="route('login')" />
                        @endif
                    </x-dropdown>
                </x-slot:actions>
            </x-nav>
            {{-- The main content with `full-width` --}}
            <x-main with-nav full-width :collapse-text="__('actions.collapse')">
                {{-- This is a sidebar that works also as a drawer on small screens --}}
                {{-- Notice the `main-drawer` reference here --}}
                <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">
                    <div class="flex flex-col justify-between h-full">
                        {{-- Activates the menu item when a route matches the `link` property --}}
                        <x-menu activate-by-route>
                            <x-menu-item :title="__('actions.home')" icon="o-home" link="###" />
                            <x-menu-item title="Messages" icon="o-envelope" link="###" />
                            <x-menu-sub :title="__('actions.settings')" icon="o-cog-6-tooth">
                                <x-menu-item title="Wifi" icon="o-wifi" link="####" />
                                <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                            </x-menu-sub>
                        </x-menu>
                        {{-- User --}}
                        @if($user = auth()->user())
                            <div>
                                <x-menu-separator />

                                <x-list-item :item="$user" value="name" sub-value="userN" no-separator no-hover class="ml-3">
                                    <x-slot:actions>
                                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" :tooltip-left="__('actions.log_out')" no-wire-navigate link="/logout" />
                                    </x-slot:actions>
                                </x-list-item>
                            </div>
                        @endif
                    </div>
                </x-slot:sidebar>

                {{-- The `$slot` goes here --}}
                <x-slot:content>
                    {{ $slot }}
                </x-slot:content>
                <x-slot:footer>
                    <x-footer />
                </x-slot:footer>
            </x-main>
        @endif
        <livewire:notification />
        @livewireScripts
    </body>
</html>
