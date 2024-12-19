@if(isset($templates['team'][0]) && $team = $templates['team'][0])
    <!-- Team Section Starts Here -->
    <section class="team-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang($team->description->sub_title)</span>
                        <h2 class="section-header_title">@lang($team->description->heading) <span
                                class="text--base">@lang($team->description->highlight_heading)</span></h2>
                    </div>
                </div>
            </div>
            @if(isset($contentDetails['team']))
                <div class="row gy-4 justify-content-center">
                    @foreach($contentDetails['team'] as $key=>$data)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10">
                            <div class="team-item">
                                <div class="team-item_thumb">
                                    <img
                                        src="{{getFile(config('location.content.path').@$data->content->contentMedia->description->image)}}"
                                        alt="team">
                                    <ul class="social-links">
                                        @if(@$data->content->contentMedia->description->fb_link)
                                            <li><a href="{{@$data->content->contentMedia->description->fb_link}}"><i
                                                        class="lab la-facebook-f"></i></a></li>
                                        @endif
                                        @if(@$data->content->contentMedia->description->twitter_link)
                                            <li><a href="{{@$data->content->contentMedia->description->twitter_link}}"><i
                                                        class="lab la-twitter"></i></a></li>
                                        @endif
                                        @if(@$data->content->contentMedia->description->instagram_link)
                                            <li><a href="{{@$data->content->contentMedia->description->instagram_link}}"><i
                                                        class="lab la-instagram"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="team-item_content">
                                    <h4 class="name">@lang(@$data->description->name)</h4>
                                    <span class="designation">@lang(@$data->description->designation)</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </section>
    <!-- Team Section Ends Here -->
@endif
