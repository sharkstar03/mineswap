
@if(isset($contentDetails['feature']))


    <!-- Feature Section Starts Here -->
    <section class="feature-section padding-top padding-bottom bg_img"
             style="background: url({{getFile($themeTrue.'images/banner/bg.png')}});">
        <div class="container">
            <div class="row gy-5 align-items-center">

                @if(isset($templates['feature'][0]) && $feature = $templates['feature'][0])
                <div class="col-xl-6">
                    <div class="section-header pe-lg-5 mb-0">
                        <span class="section-header_subtitle">  @lang($feature['description']->sub_title)</span>
                        <h2 class="section-header_title">  @lang($feature['description']->heading) <span
                                class="text--base">  @lang($feature['description']->highlight_heading)</span></h2>
                        <p>@lang($feature['description']->short_details)</p>
                    </div>
                </div>
                @endif

                @if(isset($contentDetails['feature']))

                <div class="col-xl-6">
                    <div class="row align-items-center gy-4 justify-content-center">
                        <div class="col-lg-6 col-md-6 col-sm-10">
                            @foreach($contentDetails['feature']->take(2) as $key => $obj)
                            <div class="feature-item {{($key == 0)? 'mb-4': '' }} ">
                                <div class="feature-item_icon">
                                    <img src="{{getFile(config('location.content.path').@$obj->content->contentMedia->description->image)}}" alt="@lang($obj['description']->title)">
                                </div>
                                <div class="feature-item_content">
                                    <h4 class="title">@lang($obj['description']->title)</h4>
                                    <p>@lang($obj['description']->information)</p>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-10">
                            @foreach($contentDetails['feature']->skip(2)->take(1) as $key => $obj)
                            <div class="feature-item">
                                <div class="feature-item_icon">
                                    <img src="{{getFile(config('location.content.path').@$obj->content->contentMedia->description->image)}}" alt="@lang($obj['description']->title)">
                                </div>
                                <div class="feature-item_content">
                                    <h4 class="title">@lang($obj['description']->title)</h4>
                                    <p>@lang($obj['description']->information)</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Feature Section Ends Here -->



@endif
