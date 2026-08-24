@extends('layouts.app')

@section('title', 'ویرایش کمپین')

@section('content')
    <x-page-header eyebrow="Edit campaign" :title="$campaign->title"
                   :back="route('advertiser.campaigns.show', $campaign)" back-label="بازگشت به کمپین" />

    <form method="POST" action="{{ route('advertiser.campaigns.update', $campaign) }}"
          class="card card--pad-24 medium" data-reveal>
        @include('advertiser.campaigns._form')
    </form>
@endsection
