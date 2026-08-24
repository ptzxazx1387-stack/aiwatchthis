@props([
    'name' => '',
    'size' => 'md',
    'tone' => 'ink',
])

<span {{ $attributes->class([
    'avatar',
    'avatar--lg' => $size === 'lg',
    'avatar--sm' => $size === 'sm',
    'avatar--mist' => $tone === 'mist',
]) }} aria-hidden="true">{{ \App\Support\Fmt::initial($name) }}</span>
