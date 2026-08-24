@extends('layouts.app')

@section('title', 'پروفایل سفیر')

@section('content')
    <x-page-header eyebrow="Profile" title="پروفایل سفیر"
                   lead="تخصیص خودکار کمپین‌ها دقیقاً از روی همین اطلاعات انجام می‌شود — هرچه دقیق‌تر، پیشنهادهای بهتر." />

    @if($profile && $profile->status === \App\Enums\ProfileStatus::Pending)
        <div class="note note--amber mb-24" data-reveal>
            <x-icon name="clock" :size="16" />
            <p class="grow">پروفایل شما در انتظار تأیید مدیر است. تا آن زمان کمپینی تخصیص داده نمی‌شود.</p>
        </div>
    @endif

    <div class="grid grid-side grid-24">
        <form method="POST" action="{{ route('ambassador.profile.update') }}"
              class="card card--pad-24 stack-20" data-reveal data-draft="ambassador-profile">
            @csrf
            @method('PUT')

            <div class="grid grid-2" style="gap:16px">
                <x-field label="یوزرنیم اینستاگرام" name="ig_username" required
                         hint="بدون @ — فقط خود نام کاربری.">
                    <input type="text" id="f-ig_username" name="ig_username" dir="ltr"
                           value="{{ old('ig_username', $profile->ig_username ?? '') }}"
                           class="input ltr @error('ig_username') input--err @enderror"
                           required placeholder="your_username">
                </x-field>

                <x-field label="لینک پروفایل" name="ig_profile_url" optional>
                    <input type="url" id="f-ig_profile_url" name="ig_profile_url" dir="ltr"
                           value="{{ old('ig_profile_url', $profile->ig_profile_url ?? '') }}"
                           class="input ltr @error('ig_profile_url') input--err @enderror"
                           placeholder="https://instagram.com/…">
                </x-field>
            </div>

            <x-field label="دسته‌بندی پیج" name="category_id" required
                     hint="کمپین‌های هم‌دسته زودتر به شما پیشنهاد می‌شوند.">
                <select id="f-category_id" name="category_id" required
                        class="select @error('category_id') select--err @enderror">
                    <option value="">انتخاب کنید</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                @selected(old('category_id', $profile->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <div class="grid grid-2" style="gap:16px">
                <x-field label="استان" name="province_id" required>
                    <select id="f-province_id" name="province_id" required
                            class="select @error('province_id') select--err @enderror"
                            data-geo-province data-geo-url="{{ route('geo.cities') }}">
                        <option value="">انتخاب کنید</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}"
                                    @selected(old('province_id', $profile->province_id ?? '') == $province->id)>{{ $province->name }}</option>
                        @endforeach
                    </select>
                </x-field>

                <x-field label="شهر" name="city_id" required>
                    <select id="f-city_id" name="city_id" required
                            class="select @error('city_id') select--err @enderror"
                            data-geo-city data-blank-label="انتخاب کنید">
                        <option value="">انتخاب کنید</option>
                        {{-- بدون جاوااسکریپت هم فهرست شهرهای استان انتخاب‌شده باید بیاید --}}
                        @php
                            $selectedProvince = old('province_id', $profile->province_id ?? null);
                            $cityOptions = $selectedProvince
                                ? \App\Models\City::where('province_id', $selectedProvince)->orderBy('name')->get()
                                : collect();
                        @endphp
                        @foreach($cityOptions as $city)
                            <option value="{{ $city->id }}"
                                    @selected(old('city_id', $profile->city_id ?? '') == $city->id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </x-field>
            </div>

            <x-field label="تعداد دنبال‌کننده" name="followers_count" optional>
                <input type="number" id="f-followers_count" name="followers_count" min="0" inputmode="numeric"
                       value="{{ old('followers_count', $profile->followers_count ?? 0) }}"
                       class="input figure @error('followers_count') input--err @enderror">
            </x-field>

            <x-field label="بیوگرافی" name="bio" optional
                     hint="یک یا دو جمله درباره‌ی پیج و مخاطبانتان.">
                <textarea id="f-bio" name="bio" rows="3" class="textarea">{{ old('bio', $profile->bio ?? '') }}</textarea>
            </x-field>

            <div class="formbar">
                <button type="submit" class="btn">ذخیره پروفایل</button>
                <p class="micro push">تغییر اطلاعات، پروفایل را دوباره به صف بررسی می‌برد.</p>
            </div>
        </form>

        <aside class="stack-16">
            @if($profile)
                <div class="card" data-reveal>
                    <div class="row">
                        <x-avatar :name="auth()->user()->name" size="lg" />
                        <div class="stack-4 grow">
                            <span class="strong">{{ auth()->user()->name }}</span>
                            <span class="micro ltr">{{ '@'.$profile->ig_username }}</span>
                        </div>
                    </div>

                    <div class="row-8 mt-16">
                        <x-badge :tone="$profile->status->tone()">{{ $profile->status->label() }}</x-badge>
                        @if($profile->group)
                            <x-badge tone="mist">سطح {{ $profile->group->name }}</x-badge>
                        @endif
                    </div>

                    <hr class="mt-16">

                    <div class="stack-12 mt-16">
                        <div class="row between">
                            <span class="micro">دنبال‌کننده</span>
                            <span class="strong figure">{{ \App\Support\Fmt::num($profile->followers_count) }}</span>
                        </div>
                        <div class="row between">
                            <span class="micro">میانگین ویو ۷ روزه</span>
                            <span class="strong figure">{{ \App\Support\Fmt::num($profile->avg_views_7d) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card" data-reveal>
                <span class="eyebrow">Why it matters</span>
                <p class="body mt-12">
                    میانگین ویوی هفت روز اخیر شما تعیین می‌کند به چه کمپین‌هایی دسترسی دارید و
                    در چه سطحی قرار می‌گیرید. هرچه ویوهای بیشتری ثبت و تأیید شود، کمپین‌های
                    بهتری به شما می‌رسد.
                </p>
            </div>
        </aside>
    </div>
@endsection
