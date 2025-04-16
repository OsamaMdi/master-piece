@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-base font-bold text-black']) }}>
    {{ $value ?? $slot }}
</label>
