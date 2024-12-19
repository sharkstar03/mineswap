@if(isset($templates['hero'][0]) && $hero = $templates['hero'][0])
    <!-- Banner Section Starts Here -->
    <div class="banner-section bg_img" style="background: url({{getFile($themeTrue.'images/banner/bg.png')}});">
        <div class="container">
            <div class="banner-wrapper d-flex flex-wrap justify-content-between align-items-center">
                <div class="banner-content">
                    <span class="sub-title"><img src="{{getFile($themeTrue.'images/banner/icon.png')}}" alt="banner">
                        @lang(@$hero['description']->sub_title)</span>
                    <h1 class="title"> @lang(@$hero['description']->heading) <span class="text--base">@lang(@$hero['description']->highlight_heading) </span> @lang(@$hero['description']->sub_heading)</h1>
                    <p>
                        @lang(@$hero['description']->short_description)
                    </p>
                    <div class="button-wrapper">
                        <a href="{{@$hero->templateMedia()->button_link}}" class="cmn--btn"> @lang(@$hero['description']->button_name)</a>
                    </div>
                </div>
                <div class="banner-thumb">
                    <img src="{{getFile(config('location.template.path').@$hero->templateMedia()->image)}}" alt="banner">
                </div>
            </div>
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/banner/shape.png')}}" alt="banner">
        </div>
    </div>
    <!-- Banner Section Ends Here -->

@endif
