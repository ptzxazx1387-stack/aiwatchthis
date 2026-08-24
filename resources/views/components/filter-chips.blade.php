@props([
    'options' => [],
    'param' => 'status',
    'current' => null,
    'route' => null,
    'label' => 'فیلتر',
])

{{--
    یک ردیف چیپ فیلتر. هر چیپ یک لینک ساده است، پس بدون جاوااسکریپت هم
    کار می‌کند و با کیبورد هم قابل پیمایش است.
--}}
<nav {{ $attributes->class('chips') }} aria-label="{{ $label }}">
    @foreach($options as $value => $text)
        @php $isOn = (string) ($current ?? '') === (string) $value; @endphp
        <a href="{{ $value === '' ? route($route) : route($route, [$param => $value]) }}"
           class="chip @if($isOn) is-on @endif"
           @if($isOn) aria-current="page" @endif>{{ $text }}</a>
    @endforeach
</nav>
