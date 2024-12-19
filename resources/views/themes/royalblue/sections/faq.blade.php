@if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
<section class="faq-section padding-bottom padding-top overflow-hidden">
    <div class="container">
        @if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang(@$faq['description']->sub_title)</span>
                        <h2 class="section-header_title"><span
                                class="text--base">@lang(@$faq['description']->highlight_heading) </span> @lang(@$faq['description']->heading)
                        </h2>
                    </div>
                </div>
            </div>
        @endif
        <div class="row gy-5 align-items-center flex-lg-row flex-column-reverse">
            <div class="col-lg-6">
                <div class="faq-wrapper">
                    @if(isset($contentDetails['faq']))
                        @foreach($contentDetails['faq'] as $k => $data)
                            <div class="faq-item @if($k == 0)active open @endif">
                                <div class="faq-title"><h5 class="title">@lang(@$data->description->title)</h5>
                                </div>
                                <div class="faq-content">
                                    <p>@lang(@$data->description->description)</p>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
            @if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
            <div class="col-lg-6">
                <div class="faq-thumb m-0 ms-lg-5">
                    <img src="{{getFile(config('location.template.path').@$faq->templateMedia()->image)}}" alt="faq">
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="shape shape1">
        <img src="{{$themeTrue.'images/about/shape2.png'}}" alt="about">
    </div>
</section>
<!-- Faq Section Ends Here -->
@endif

