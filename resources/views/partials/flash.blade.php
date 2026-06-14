{{-- Notifications globales (toasts) — affichées sur toutes les pages.
     S'appuie sur les messages flash de session posés par les contrôleurs :
     ->with('success'|'error'|'warning'|'info'|'status', '…') ainsi que sur
     les erreurs de validation ($errors). --}}
@php
    $axToasts = collect([
        ['key' => 'success', 'type' => 'success', 'icon' => 'fa-circle-check'],
        ['key' => 'error',   'type' => 'danger',  'icon' => 'fa-circle-exclamation'],
        ['key' => 'warning', 'type' => 'warning', 'icon' => 'fa-triangle-exclamation'],
        ['key' => 'info',    'type' => 'info',    'icon' => 'fa-circle-info'],
        ['key' => 'status',  'type' => 'info',    'icon' => 'fa-circle-info'],
    ])->filter(fn ($t) => session()->has($t['key']) && filled(session($t['key'])));
@endphp

@if($axToasts->isNotEmpty() || $errors->any())
    <div class="ax-toasts">
        {{-- Messages flash (auto-disparition après 5s) --}}
        @foreach($axToasts as $t)
            <div class="ax-toast ax-toast--{{ $t['type'] }}"
                 x-data="{ show: true }" x-show="show" x-cloak
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition.opacity.duration.300ms>
                <i class="fas {{ $t['icon'] }}"></i>
                <div class="ax-toast__body">{{ session($t['key']) }}</div>
                <button type="button" class="ax-toast__close" @click="show = false" aria-label="Fermer">&times;</button>
            </div>
        @endforeach

        {{-- Erreurs de validation (pas d'auto-disparition : l'utilisateur doit les lire) --}}
        @if($errors->any())
            <div class="ax-toast ax-toast--danger"
                 x-data="{ show: true }" x-show="show" x-cloak
                 x-transition.opacity.duration.300ms>
                <i class="fas fa-circle-exclamation"></i>
                <div class="ax-toast__body">
                    <strong>Veuillez corriger&nbsp;:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="ax-toast__close" @click="show = false" aria-label="Fermer">&times;</button>
            </div>
        @endif
    </div>
@endif
