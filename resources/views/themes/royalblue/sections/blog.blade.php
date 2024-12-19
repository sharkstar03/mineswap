<!-- Blog Section Starts Here -->
<section class="blog-section padding-bottom padding-top">
    <div class="container">
        @if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang(@$blog['description']->sub_title)</span>
                        <h2 class="section-header_title"><span
                                class="text--base">@lang(@$blog['description']->highlight_heading)</span> @lang(@$blog['description']->heading)
                        </h2>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($contentDetails['blog']))
            <div class="row g-4 justify-content-center">
                @foreach($contentDetails['blog']->take(3)->sortDesc() as $data)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="post__item">
                        <div class="post__item-thumb">
                            <img src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" alt="{{@$data->description->title}}">
                        </div>
                        <div class="post__item-content">
                            <h4 class="title">
                                <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}">
                                    {{Str::limit(@$data->description->title,40)}}
                                 </a>
                            </h4>
                            <p> {{Str::limit(strip_tags(@$data->description->description),120)}}</p>
                            <span class="date mt-3"><i class="las la-clock"></i> {{dateTime(@$data->created_at,'d M, Y')}} </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
<!-- Blog Section Ends Here -->

