@props([
    'value' => 0,
    'unit' => 'تومان',
    'sign' => null,
])

<span {{ $attributes->class('nowrap') }}>
    <span class="figure">{{ $sign }}{{ \App\Support\Fmt::money($value) }}</span><span class="micro dim"> {{ $unit }}</span>
</span>
