@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[color:var(--ax-blue)] focus:ring-[color:var(--ax-blue)] rounded-lg shadow-sm']) }}>
