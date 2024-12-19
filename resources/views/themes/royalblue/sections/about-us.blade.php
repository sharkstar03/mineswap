@if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])

    <!-- About Section STarts Here -->
    <section class="about-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6">
                    <div class="section-thumb about-thumb rtl me-lg-5">
                        <img src="{{getFile(config('location.template.path').@$aboutUs->templateMedia()->image)}}" alt="about">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-header">
                            <span class="section-header_subtitle">
                                @lang(@$aboutUs['description']->sub_title)

                            </span>
                            <h2 class="section-header_title">@lang(@$aboutUs['description']->heading) <span class="text--base">@lang(@$aboutUs['description']->highlight_heading)</span></h2>
                            <p>
                                @lang(@$aboutUs['description']->short_description)
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <a href="{{@$aboutUs->templateMedia()->button_link}}" class="cmn--btn">@lang(@$aboutUs['description']->button_name)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape.svg')}}" alt="about">
        </div>
        <div class="shape shape2">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </section>
    <!-- About Section Ends Here -->


@endif


