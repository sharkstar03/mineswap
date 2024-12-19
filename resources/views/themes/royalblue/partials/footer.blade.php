<!-- Footer Section Starts Here -->
<footer class="footer-section section-bg">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-5 justify-content-between">
                <div class="col-lg-3 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <div class="logo"><a href="{{url('/')}}"><img src="{{getFile(config('location.logoIcon.path').'logo.png')}}" alt="{{config('basic.site_title')}}"></a></div>
                        @if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0])
                        <p>@lang(strip_tags(@$contact->description->footer_short_details))</p>
                        @endif
                        @if(isset($contentDetails['social']))
                        <ul class="social-links d-flex">
                            @foreach($contentDetails['social'] as $data)
                            <li><a href="{{@$data->content->contentMedia->description->link}}">      <i class="{{@$data->content->contentMedia->description->icon}}"></i></a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h4 class="title">{{trans('Useful Links')}}</h4>
                        <ul class="footer-links">
                            <li>
                                <a href="{{route('home')}}">@lang('Home')</a>
                            </li>
                            <li>
                                <a href="{{route('about')}}">@lang('About Us')</a>
                            </li>
                            <li>
                                <a href="{{route('blog')}}">@lang('Blog')</a>
                            </li>
                            <li>
                                <a href="{{route('faq')}}">@lang('FAQ')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h4 class="title">@lang('Other Links')</h4>
                        <ul class="footer-links">
                            @isset($contentDetails['support'])
                                @foreach($contentDetails['support'] as $data)
                                    <li>
                                        <a href="{{route('getLink', [slug($data->description->title), $data->content_id])}}">@lang($data->description->title)</a>
                                    </li>
                                @endforeach
                            @endisset
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-md-5">
                    <div class="footer-widget">
                        <h4 class="title">{{trans('Language')}}</h4>
                        <ul class="footer-links">
                            @foreach($languages as $lang)
                            <li>
                                <a href="{{route('language',$lang->short_name)}}">@lang($lang->name)</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg">
        <img src="{{getFile($themeTrue.'images/footer/bg2.png')}}" alt="footer">
    </div>
    <div class="shape shape1">
        <img src="{{getFile($themeTrue.'images/footer/bg.png')}}" alt="footer">
    </div>
    <div class="shape shape2">
        <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="copyright"> @lang('Copyright') &copy; {{date('Y')}} @lang($basic->site_title) @lang('All Rights Reserved')</p>
        </div>
    </div>
</footer>
<!-- Footer Section Ends Here -->

@include($theme.'partials.color')
