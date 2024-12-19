<section id="choose" class="choose section-bg padding-bottom padding-top">
    <div class="container ">
        @if(isset($templates['why-chose-us'][0]) && $whyChoseUs = $templates['why-chose-us'][0])
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang($whyChoseUs['description']->sub_title) </span>
                        <h2 class="section-header_title">@lang($whyChoseUs['description']->heading) <span
                                class="text--base">@lang($whyChoseUs['description']->highlight_heading)</span>
                        </h2>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($contentDetails['why-chose-us'] ))
            <div class="row">
                @foreach($contentDetails['why-chose-us'] as $key => $item)
                    <div class="col-md-6 @if($key != 0) mt-4 mt-md-0 @endif">
                        <div class="icon-box">
                            <i class="{{@$item->content->contentMedia->description->icon}}"></i><h4><a href="javascript:void(0)">@lang(@$item->description->title)</a></h4>
                            <p>@lang(@$item->description->information)</p></div>
                    </div>
                @endforeach

            </div>
        @endif
    </div>
</section>

