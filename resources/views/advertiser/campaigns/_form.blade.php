@csrf
@isset($campaign) @method('PUT') @endisset

@php
    $selectedProvince = old('province_id', $campaign->province_id ?? null);
    $cityOptions = $selectedProvince
        ? \App\Models\City::where('province_id', $selectedProvince)->orderBy('name')->get()
        : collect();
    $defaultPrice = \App\Models\Setting::get('default_price_per_view', 1000);
@endphp

<div class="stack-32">

    {{-- ————— چه چیزی تبلیغ می‌شود --}}
    <section class="stack-16">
        <div>
            <span class="eyebrow">Step 1</span>
            <h2 class="h3 mt-8">کمپین چیست</h2>
        </div>

        <x-field label="عنوان کمپین" name="title" required
                 hint="سفیران همین عنوان را می‌بینند — روشن و کوتاه بنویسید.">
            <input type="text" id="f-title" name="title" value="{{ old('title', $campaign->title ?? '') }}"
                   class="input @error('title') input--err @enderror" required maxlength="120">
        </x-field>

        <x-field label="توضیحات" name="description" optional
                 hint="محصول یا خدمت‌تان در دو سه جمله.">
            <textarea id="f-description" name="description" rows="3"
                      class="textarea @error('description') textarea--err @enderror">{{ old('description', $campaign->description ?? '') }}</textarea>
        </x-field>

        <x-field label="متن استوری" name="story_content" optional
                 hint="دقیقاً همان چیزی که سفیر باید در استوری بگذارد. اگر خالی بماند، سفیر خودش تصمیم می‌گیرد.">
            <textarea id="f-story_content" name="story_content" rows="4"
                      class="textarea @error('story_content') textarea--err @enderror">{{ old('story_content', $campaign->story_content ?? '') }}</textarea>
        </x-field>
    </section>

    <hr>

    {{-- ————— چه کسی آن را ببیند --}}
    <section class="stack-16">
        <div>
            <span class="eyebrow">Step 2</span>
            <h2 class="h3 mt-8">مخاطب هدف</h2>
            <p class="body mt-8">هر فیلتری که خالی بگذارید یعنی «محدودیتی ندارد» — و شانس پر شدن ظرفیت بیشتر می‌شود.</p>
        </div>

        <div class="grid grid-3" style="gap:16px">
            <x-field label="دسته‌بندی" name="category_id" optional>
                <select id="f-category_id" name="category_id" class="select">
                    <option value="">همه دسته‌ها</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                @selected(old('category_id', $campaign->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <x-field label="استان هدف" name="province_id" optional>
                <select id="f-province_id" name="province_id" class="select"
                        data-geo-province data-geo-url="{{ route('geo.cities') }}">
                    <option value="">همه استان‌ها</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" @selected($selectedProvince == $province->id)>{{ $province->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <x-field label="شهر هدف" name="city_id" optional>
                <select id="f-city_id" name="city_id" class="select" data-geo-city data-blank-label="همه شهرها">
                    <option value="">همه شهرها</option>
                    @foreach($cityOptions as $city)
                        <option value="{{ $city->id }}"
                                @selected(old('city_id', $campaign->city_id ?? '') == $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </x-field>
        </div>

        <x-field label="حداقل میانگین ویوی ۷ روزه‌ی سفیر" name="min_avg_views" optional
                 hint="فقط سفیرانی که به‌طور میانگین این تعداد ویو می‌گیرند واجد شرایط می‌شوند. عدد بالا یعنی سفیر کمتر.">
            <input type="number" id="f-min_avg_views" name="min_avg_views" min="0" inputmode="numeric"
                   value="{{ old('min_avg_views', $campaign->min_avg_views ?? 0) }}"
                   class="input figure @error('min_avg_views') input--err @enderror">
        </x-field>
    </section>

    <hr>

    {{-- ————— چقدر و تا کی --}}
    <section class="stack-16">
        <div>
            <span class="eyebrow">Step 3</span>
            <h2 class="h3 mt-8">بودجه و زمان</h2>
        </div>

        <div class="grid grid-2" style="gap:16px">
            <x-field label="قیمت هر ویو" name="price_per_view" required hint="به تومان.">
                <input type="number" id="f-price_per_view" name="price_per_view" min="0" required inputmode="numeric"
                       value="{{ old('price_per_view', $campaign->price_per_view ?? $defaultPrice) }}"
                       class="input figure @error('price_per_view') input--err @enderror"
                       data-cost-price>
            </x-field>

            <x-field label="ظرفیت کل (تعداد ویو)" name="capacity" required
                     hint="مجموع ویوهایی که می‌خواهید بخرید.">
                <input type="number" id="f-capacity" name="capacity" min="1" required inputmode="numeric"
                       value="{{ old('capacity', $campaign->capacity ?? '') }}"
                       class="input figure @error('capacity') input--err @enderror"
                       data-cost-capacity>
            </x-field>
        </div>

        {{-- برآورد هزینه، همان‌جا و بی‌سروصدا --}}
        <div class="panel row between wrap" data-cost-box>
            <span class="micro">حداکثر هزینه‌ی این کمپین</span>
            <span class="h3 figure" data-cost-total>—</span>
        </div>

        <div class="grid grid-2" style="gap:16px">
            <x-field label="تاریخ شروع" name="start_date" optional>
                <input type="date" id="f-start_date" name="start_date" class="input ltr"
                       value="{{ old('start_date', isset($campaign) && $campaign->start_date ? $campaign->start_date->format('Y-m-d') : '') }}">
            </x-field>

            <x-field label="تاریخ پایان" name="end_date" optional>
                <input type="date" id="f-end_date" name="end_date" class="input ltr"
                       value="{{ old('end_date', isset($campaign) && $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}">
            </x-field>
        </div>
        <p class="hint">تاریخ‌ها میلادی وارد می‌شوند و همه‌جای سامانه شمسی نمایش داده می‌شوند.</p>
    </section>

    <div class="formbar">
        <button type="submit" class="btn">
            {{ isset($campaign) ? 'ذخیره تغییرات' : 'ثبت کمپین' }}
        </button>
        <a href="{{ route('advertiser.campaigns.index') }}" class="btn btn--quiet">انصراف</a>
        @unless(isset($campaign))
            <p class="micro push">پس از ثبت، کمپین برای تأیید مدیر ارسال می‌شود.</p>
        @endunless
    </div>
</div>
