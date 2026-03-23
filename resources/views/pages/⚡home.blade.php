<?php

use App\Action\Helpers;
use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public int $stepper = 1;
    public string $email;
    public string $password;
    public array $azCode = [];
    public string $use_option;
    public bool $remember = true;


    public function mount()
    {
        $this->use_option = Cookie::get('use_option', 'email');
    }

    public function UpdatingUseOption(string $value)
    {
        Cookie::queue(cookie()->forever('use_option', $value));
    }

    public function connexion()
    {
        $this->validate([
                'email' => 'required_without:azCode|email|nullable',
                'azCode' => 'required_without:email|array|nullable',
                'password' => 'required|string',
            ]);

        if($this->use_option == 'azid')
        {
            $codecheck = (int) implode("", $this->azCode);
            $config_value = DB::table('az_config')->where('config_name', 'base_identifiant')->first('config_value')->config_value ?? 0;

            $user = User::with('customer')->where('customer_number', $codecheck - $config_value)
                ->where('passW', md5($this->password))
                ->where('status', 1)
                ->first();
        }
        else
        {
            $user = User::with('customer')->where('userN', $this->email)
                ->where('passW', md5($this->password))
                ->where('status', 1)
                ->first();
        }
        if(!$user)
            $this->addError('username', __("auth.failed"));
        else{
            //connexion s'il s'agit d'une entreprise
            if($user->customer->is_company){
                $user->company_account_connected = [
                        'id' => $user->customer->Customers_Numbers,
                        'role' => $user->customer->type
                    ];
                return $this->connect($user);
            }
            else{  //connexion s'il s'agit d'un particulier
                //S'il est associé à plusieurs entreprises, demande de faire un choix
                if(count($user->linked_companies) > 1){
                    session()->put('user', $user);
                    session()->put('companies', $user->linked_companies);
                }else{
                    foreach($user->linked_companies as $company) //S'il est associé à une seule entreprise, le connecté automatiquement
                    { 
                        $user->company_account_connected = [
                            'id' => $company->Customers_Numbers,
                            'role' => $company->role
                        ];
                        return $this->connect($user);
                    }
                }
                //s'il s'agit d'un personnel n'étant associé à aucune entreprise lui affiché un message d'erreur
                $this->addError('username', __("auth.unauthorized"));
            }
        }
    }

    public function chooseAccount(int $company)
    {
        $user = session()->pull('user');
        $linked_companies = session()->pull('companies', []);
        if($user && key_exists($company, $linked_companies)){
            $user->company_account_connected = [
                    'id' => $linked_companies[$company]->Customers_Numbers,
                    'role' => $linked_companies[$company]->role
                ];
            return $this->connect($user);
        }else{
            $this->reset();
        }
    }

    private function connect(User $user)
    {
        if($user->customer['code_language']){
            $locale = Helpers::getLocale($user->customer['code_language']);
            Cookie::queue('locale', $locale, 60*24*30*3);
        }
        
        Auth::login($user, $this->remember);
        $this->dispatch('notification', [
            'type' => 'success',
            'title' => __('auth.login_title'),
            'description' => __('auth.login_success', ['name' => $user->customer->Names . ' ' . $user->customer->LastName])
        ]);
        
        return $this->redirectIntended(route('dashboard'), navigate: true);
    }
};
?>

<div class="min-h-screen text-teritary flex items-center justify-center p-4 overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-emerald-950">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-3xl">
        <!-- Card Container with animation -->
        <div class="relative">
            <!-- Glow effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-blue-600/20 rounded-2xl blur-xl opacity-50"></div>

            <div x-data="{ stepper: @entangle('stepper'), 
                        showPassword: false,
                        use_option: @entangle('use_option'),
                        handlePairChange: (index, value) => {
                            const digits = value.replace(/\D/g, '').slice(0, 2);
                            if (digits.length === 2 && index < 3) {
                                const nextInput = document.getElementById(`pair-${index + 1}`);
                                nextInput?.focus();
                            }
                        },
                        handlePairKeyDown: (index, e) => {
                            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                                const prevInput = document.getElementById(`pair-${index - 1}`);
                                prevInput?.focus();
                            }
                        }
                    }" 
                class='relative bg-gray-100/95 backdrop-blur-xl shadow-2xl rounded-2xl overflow-hidden border border-white/10'>
                <div class="bg-gradient-to-br from-emerald-700 via-emerald-800 to-blue-900 p-6 text-center relative overflow-hidden">
                    <!-- Decorative elements -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-0 left-1/4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-0 right-1/4 w-24 h-24 bg-emerald-300/20 rounded-full blur-xl"></div>
                    </div>

                    <div class="relative">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm mb-2 border border-white/20">
                            <!-- Project logo here -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                            </svg>
                        </div>
                            <!-- Project Name -->
                        <h1 class="text-3xl font-bold tracking-tight">{{ config('app.name') }}</h1>
                            <!-- Project description -->
                        <p class="text-emerald-100/80 mt-2">{{ __("Project description") }}</p>
                    </div>

                    <!-- Step indicator -->
                    <div class="flex items-center justify-center gap-3 mt-2">
                        <button type="button" @click="stepper = 1" class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition-all
                            duration-300 z-40" :class="stepper === 1 ? 'bg-white/20 text-white' : 'bg-white/5 text-white/50'"
                            :aria-current="stepper === 1 ? 'step' : 'undefined'"
                            >
                            <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">1</span>
                            {{ __('actions.home') }}
                        </button>
                        <div class="w-8 h-px bg-white/30"></div>
                        <button type="button" @click="stepper = 2" class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm transition-all duration-300 z-40" 
                            :class="stepper === 2 ? 'bg-white/20 text-white' : 'bg-white/5 text-white/50'">
                            <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">2</span>
                            {{ __('actions.log_in') }}
                        </button>
                    </div>
                </div>

                <div class="p-6 text-foreground">
                    <!-- Step 1: Home -->
                    <div class="transition-all duration-500" :class="stepper === 1 ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-full absolute inset-0 pointer-events-none'">
                        <div x-cloak x-show="stepper === 1" class="space-y-4">
                            <div class="text-center">
                                <h2 class="text-md md:text-xl font-semibold text-black">
                                    {{ __("Découvrez :name", ['name' => config('app.name')]) }}
                                </h2>
                                <p class="text-gray-600 mt-1 text-sm">
                                    {{ __("Accédez aux principales fonctionnalités pour la gestion de vos activités.") }}
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6 mb-12">
                                <!-- Project features -->
                                @for ($i = 0; $i< 6; $i++)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/50 hover:bg-muted transition-colors group">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500/20 to-blue-500/20 flex items-center justify-center group-hover:from-emerald-500/30 group-hover:to-blue-500/30 transition-colors">
                                            <feature.icon class="w-4 h-4 text-emerald-600" />
                                        </div>
                                        <span class="text-sm text-black/80">feature text</span>
                                    </div>
                                @endfor
                            </div>

                            <button @click="stepper = 2" class="w-full h-12 bg-gradient-to-r from-emerald-600 to-blue-600
                                hover:from-emerald-500 hover:to-blue-500 text-white font-semibold rounded-xl shadow-lg
                                shadow-emerald-500/20 transition-all duration-300 hover:shadow-emerald-500/30 flex items-center justify-center
                                hover:scale-[1.02]"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                {{ __('Se connecter') }}
                            </button>
                        </div>
                    </div>
                    <!-- Step 2: Login Form -->
                    <div class="transition-all duration-500" :class="stepper === 2 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full absolute inset-0 pointer-events-none'">
                        <div x-cloak x-show="stepper === 2">
                            <div class="space-y-4">
                                <!-- Back button -->
                                <button @click="stepper = 1" class="flex items-center gap-2 text-muted-foreground hover:text-black transition-colors text-sm group">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                                    </svg>
                                    {{ __('actions.back') }}
                                </button>

                                <h2 class="text-center text-xl font-semibold text-black">{{ __("Connexion à :name", ['name' => config('app.name')]) }}</h2>
                                @if(session()->has('companies'))
                                    <p class="text-gray-600 text-center mt-1 text-sm">
                                        {{ __("Veuillez choisir un compte") }}
                                    </p>

                                    <div class="grid md:grid-cols-2 gap-3">
                                        @foreach (session()->get('companies') as $company)
                                            <div class="bg-white/50 px-3 md:px-6 py-3 hover:border-emerald-600 hover:border hover:shadow-md rounded-lg">
                                                <div class="flex flex-col justify-between items-center">
                                                    <figure>
                                                        @if ($company->Picture)
                                                            <img src="<?= $company->Picture ?>" alt="<?= $company->Names ?>" class="rounded-full size-32 object-cover object-center" />
                                                        @else
                                                            <img src="{{ asset('images/card_logo.png') }}" alt="default image" class="rounded-full size-32 object-cover object-center" />
                                                        @endif
                                                    </figure>
                                                    <h5 class="text-lg font-semibold text-black mb-2.5 text-center"><?= $company->Names ?></h5>
                                                    <button wire:click="chooseAccount({{ $company->Customers_Numbers }})" class="w-full h-12 bg-gradient-to-r from-emerald-600 to-blue-600
                                                        hover:from-emerald-500 hover:to-blue-500 text-white font-semibold rounded-xl shadow-lg
                                                        shadow-emerald-500/20 transition-all duration-300 hover:shadow-emerald-500/30 flex items-center justify-center
                                                        hover:scale-[1.02]" wire:loading.attr="disabled" wire:target="chooseAccount({{ $company->Customers_Numbers }})"
                                                        >
                                                        <span wire:loading wire:target="chooseAccount({{ $company->Customers_Numbers }})" class="loading loading-spinner mr-1"></span>
                                                        <svg wire:loading.remove wire:target="chooseAccount({{ $company->Customers_Numbers }})" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                                        </svg>
                                                        {{ __('Se connecter') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-600 text-center mt-1 text-sm">
                                        {{ __("Entrez vos identifiants pour continuer") }}
                                    </p>

                                    <form wire:submit.prevent='connexion' class="space-y-5">
                                        <div class="w-full bg-white/50 p-1 rounded-xl mb-4 grid grid-cols-2 gap-3 md:gap-6">
                                            <div @click="use_option = 'email'" class="cursor-pointer rounded-lg flex items-center justify-center py-2" :class="use_option == 'email' && 'bg-emerald-600 shadow-sm text-white'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                Email
                                            </div>
                                            <div @click="use_option = 'azid'" class="cursor-pointer rounded-lg flex items-center justify-center py-2" :class="use_option == 'azid' && 'bg-emerald-600 shadow-sm text-white'">
                                                <p class="text-lg mr-2">#</p>
                                                AZ-ID
                                            </div>
                                        </div>
                                        @error('username')<p class="text-red-500 text-sm mb-3 px-2 md:px-4 py-2 bg-red-200 rounded-md">{{ $message }}</p>@enderror
                                        <div x-show="use_option == 'email'" x-cloak class="space-y-2">
                                            <label for="email" class="text-sm text-black font-medium">
                                                {{ __("Adresse Email") }}
                                            </label>
                                            <div class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                <input id="email" type="email" placeholder="{{ __("vous@exemple.com") }}" 
                                                class="pl-10 h-12 text-black rounded-xl w-full focus:border-emerald-500/50  @error('email')border border-red-500 @enderror" wire:model="email"
                                                />
                                            </div>
                                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                        </div>

                                        <div x-show="use_option == 'azid'" x-cloak class="space-y-3">
                                            <label class="text-sm text-black font-medium">{{ __("Code d'identification AZ") }}</label>
                                            <p class="text-xs text-gray-500">{{ __("Entrez votre code à 8 chiffres.") }} Format: XX-XX-XX</p>
                                            <div class="flex items-center justify-center gap-2">
                                                @for ($i=0; $i<4; $i++)
                                                    <div class="flex items-center gap-2">
                                                        <input id="pair-{{ $i }}" type="text" input-mode="numeric" maxLength="2" 
                                                        class="w-14 h-14 text-center text-xl text-black font-bold rounded-xl focus:border-emerald-500/50  @error('azCode')border border-red-500 @enderror"
                                                        placeholder="00" wire:model="azCode.{{ $i }}" @input="(e) => handlePairChange({{ $i }}, e.target.value)" @keydown="(e) => handlePairKeyDown({{ $i }}, e)"
                                                        />
                                                        @if($i < 3)<span class="text-gray-500 font-bold text-xl">-</span>@endif
                                                    </div>
                                                @endfor
                                            </div>
                                            @error('azCode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                        </div>

                                        <!-- Password Field -->
                                        <div class="space-y-2">
                                            <label for="password" class="text-sm text-black font-medium">
                                                {{ __("Mot de passe") }}
                                            </label>
                                            <div class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-600">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>

                                                <input id="password" wire:model="password" :type="showPassword ? 'text' : 'password' "
                                                    placeholder="{{ __("Entrez votre mot de passe") }}" class="w-full pl-10 pr-10 h-12 text-black rounded-xl focus:border-emerald-500/50 @error('password')border border-red-500 @enderror"
                                                autoComplete="current-password"
                                                />
                                                <button type="button" @click="showPassword = !showPassword"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600
                                                    hover:text-foreground transition-colors">
                                                    <svg x-cloak x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    </svg>

                                                    <svg x-cloak x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" id="remember" wire:model="remember"
                                                class="w-4 h-4 rounded bg-gray-600/50 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0" />
                                            <label for="remember" class="text-sm text-gray-600 cursor-pointer">
                                                {{ __("Se souvenir de moi") }}
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="w-full relative group/btn" wire:loading.attr="disabled">
                                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-blue-600 rounded-xl blur opacity-75 group-hover/btn:opacity-100 transition duration-300"></div>
                                            <div class="relative h-12 bg-gradient-to-r from-emerald-600 to-blue-600 rounded-xl flex items-center justify-center gap-2 font-semibold text-white transition-transform duration-200 group-hover/btn:scale-[1.02]">
                                                <span wire:loading wire:target="connexion" class="loading loading-spinner mr-1"></span>
                                                {{ __("Se connecter") }}
                                            </div>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class='flex flex-col items-center pt-4'>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-emerald-600 inline-block mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>

                                    <span class="text-sm text-muted-foreground font-medium">
                                        {{ __("Connexion sécurisée.") }}
                                    </span>
                                </div>
                                <p class="text-sm text-muted-foreground">
                                    {{ __("Vos données sont sécurisées et protégées.") }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <x-footer class="mt-4" />
            </div>
        </div>
    </div>
</div>