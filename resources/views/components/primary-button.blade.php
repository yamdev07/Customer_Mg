<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-anyxtech inline-flex items-center justify-center px-5 py-2.5 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--ax-blue)] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
