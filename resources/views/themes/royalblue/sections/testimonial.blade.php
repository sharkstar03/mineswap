@if(isset($templates['testimonial'][0]) && $testimonial = $templates['testimonial'][0])

    <!-- Testimonial Section Starts Here -->
    <section class="testimonial-section padding-bottom padding-top bg_img">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang($testimonial->description->sub_title)</span>
                        <h2 class="section-header_title">@lang(@$testimonial->description->heading) <span class="text--base">@lang(@$testimonial->description->highlight_heading)</span></h2>
                    </div>
                </div>
            </div>
            @if(isset($contentDetails['testimonial']))
            <div class="testimonial-slider">
                @foreach($contentDetails['testimonial'] as $key=>$data)

                <div class="single-slide">
                    <div class="testimonial-item">
                        <div class="testimonial-item_content">
                            <span class="icon"><i class="las la-quote-left"></i></span>
                            <p>@lang(@$data->description->description)</p>
                        </div>
                        <div class="testimonial-thumb">
                            <img src="{{getFile(config('location.content.path').@$data->content->contentMedia->description->image)}}" alt=" @lang(@$data->description->name)">
                        </div>
                        <h4 class="name"> @lang(@$data->description->name)</h4>
                        <span class="designation"> @lang(@$data->description->designation)</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="shape shape1">
            <img src="{{$themeTrue.'images/about/shape.svg'}}" alt="about">
        </div>
    </section>
    <!-- Testimonial Section Ends Here -->


@endif
