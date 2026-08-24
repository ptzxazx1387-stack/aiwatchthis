@props([
    'tone' => 'mist',
    'bare' => false,
])

<span {{ $attributes->class(['badge', 'badge--'.$tone, 'badge--bare' => $bare]) }}>{{ $slot }}</span>
