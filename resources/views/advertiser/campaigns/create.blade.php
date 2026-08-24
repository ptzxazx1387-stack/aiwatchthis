@extends('layouts.app')

@section('title', 'کمپین جدید')

@section('content')
    <x-page-header eyebrow="New campaign" title="ساخت کمپین"
                   :back="route('advertiser.campaigns.index')" back-label="بازگشت به کمپین‌ها"
                   lead="سه گام کوتاه. هرچه در گام دوم کمتر محدود کنید، ظرفیت سریع‌تر پر می‌شود." />

    <form method="POST" action="{{ route('advertiser.campaigns.store') }}"
          class="card card--pad-24 medium" data-reveal data-draft="campaign-new">
        @include('advertiser.campaigns._form')
    </form>
@endsection
