@props([
    'label',
    'value',
    'icon' => null,
    'href' => null,
    'unit' => null,
    'foot' => null,
    'flag' => false,
])

@php $tag = $href ? 'a' : 'div'; @endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    data-reveal
    {{ $attributes->class(['stat', 'stat--flag' => $flag && (float) $value > 0]) }}>

    <span class="stat__label">
        @if($icon)<x-icon :name="$icon" :size="13" />@endif
        {{ $label }}
    </span>

    <span class="stat__value">
        {{ \App\Support\Fmt::num($value) }}@if($unit)<span class="stat__unit"> {{ $unit }}</span>@endif
    </span>

    @if($foot)
        <span class="stat__foot">{{ $foot }}</span>
    @endif
</{{ $tag }}>
