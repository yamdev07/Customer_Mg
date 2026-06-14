<x-guest-layout>
    <h1 class="text-xl font-bold text-center text-[color:var(--ax-navy)] mb-1">Connexion</h1>
    <p class="text-center text-sm text-gray-500 mb-6">Accédez à votre espace de gestion</p>

    {{-- Statut de session (ex. lien de réinitialisation envoyé) --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Adresse e-mail --}}
        <div>
            <x-input-label for="email" value="Adresse e-mail" class="font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username"
                          placeholder="vous@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Mot de passe --}}
        <div>
            <x-input-label for="password" value="Mot de passe" class="font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Se souvenir / mot de passe oublié --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-[color:var(--ax-blue)] shadow-sm focus:ring-[color:var(--ax-blue)]"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-[color:var(--ax-blue)] hover:underline">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">
            Se connecter
        </x-primary-button>
    </form>
</x-guest-layout>
