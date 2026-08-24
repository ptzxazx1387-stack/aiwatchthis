@props([
    'value' => 0,
    'max' => 100,
    'tone' => 'ink',
    'label' => null,
])

@php $pct = \App\Support\Fmt::percent((float) $value, (float) $max); @endphp

<div {{ $attributes->class('progress') }}
     role="progressbar"
     aria-valuenow="{{ round($pct) }}" aria-valuemin="0" aria-valuemax="100"
     @if($label) aria-label="{{ $label }}" @endif>
    <div class="progress__fill @if($tone !== 'ink') progress__fill--{{ $tone }} @endif"
         style="inline-size: {{ round($pct, 1) }}%"></div>
</div>
