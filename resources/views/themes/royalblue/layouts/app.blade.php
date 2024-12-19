<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&family=Open+Sans:wght@300;400&display=swap">
    <!-- bootstrap 5  -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/animate.css')}}">
    @stack('css-lib')
    <link rel="stylesheet" href="{{asset($themeTrue.'css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/line-awesome.min.css')}}">

    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/slick.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/odometer.css')}}">

    <link rel="stylesheet" href="{{asset($themeTrue.'css/main.css')}}">
    @stack('style')
</head>
<body>

<div class="overlay"></div>
<a href="javascript:void(0)" class="scrollToTop"><i class="las la-chevron-up"></i></a>
@include($theme.'partials.loader')
<div class="header">
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area">
                <div class="logo">
                    <a href="{{url('/')}}"><img src="{{getFile(config('location.logoIcon.path').'logo.png')}}"
                                                alt="{{config('basic.site_title')}}"></a>
                </div>

                <ul class="menu">
                    <li><a href="{{route('home')}}">@lang('Home')</a></li>
                    <li><a href="{{route('plan')}}">@lang('Plan')</a></li>
                    <li><a href="{{route('about')}}">@lang('About Us')</a></li>
                    <li><a href="{{route('blog')}}">@lang('Blog')</a></li>
                    <li><a href="{{route('faq')}}">@lang('FAQ')</a></li>
                    <li><a href="{{route('contact')}}">@lang('Contact')</a></li>


                    @guest
                        <li class="d-sm-none d-block "><a href="{{route('login')}}">@lang('Sign In')</a></li>
                        <li class="d-sm-none d-block "><a href="{{route('register')}}">@lang('Sign Up')</a></li>
                        <li class="p-0 mx-lg-3 my-2 my-lg-0 d-none d-lg-block">
                            <a href="{{route('login')}}"
                               class="cmn--btn btn--md btn--round @if(request()->routeIs('login')) active @endif shadow-base">@lang('Sign In')</a>
                        </li>
                        <li class="p-0 mx-lg-3 my-2 my-lg-0 d-none d-lg-block">
                            <a href="{{route('register')}}"
                               class="cmn--btn btn--md btn--round  @if(request()->routeIs('register')) active @endif shadow-base">@lang('Sign Up')</a>
                        </li>
                    @endguest


                    @auth
                        <li class="d-lg-none d-block "><a href="{{route('user.home')}}">@lang('Dashboard')</a></li>
                        <li class="d-lg-none d-block "><a href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">@lang('Logout')</a></li>

                        <li class="p-0 d-none d-lg-block mx-lg-3 my-2">
                            <a href="javascript:void(0)" class="dashboard-link p-0"><img
                                    src="{{getFile(config('location.user.path').auth()->user()->image)}}"
                                    alt="dashboard-link"></a>

                            <ul class="sub-menu">
                                <li><a href="{{route('user.home')}}">@lang('Dashboard')</a></li>
                                <li><a href="{{route('user.twostep.security')}}">@lang('2FA Security')</a></li>
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">@lang('Logout')</a></li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endauth

                    <li class="p-0 d-none d-lg-block mx-lg-3 my-2">
                    </li>

                </ul>

                <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    @auth
                        <div class="p-0  d-lg-block mx-lg-3 my-2">
                            <a href="javascript:void(0)" class="dashboard-link p-0"><img
                                    src="{{getFile(config('location.user.path').auth()->user()->image)}}"
                                    alt="dashboard-link"></a>

                        </div>
                        <div class="p-0  d-lg-block mx-3 my-2">
                        </div>
                    @endauth

                    @guest
                        <div class="me-3 me-lg-0 d-none d-sm-block">
                            <a href="{{route('login')}}"
                               class="cmn--btn btn--sm btn--round active shadow-base">@lang('Sign In')</a>
                            <a href="{{route('register')}}"
                               class="cmn--btn btn--sm btn--round  shadow-base">@lang('Sign Up')</a>
                        </div>
                    @endguest
                    <div class="header-trigger d-block d--none">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include($theme.'partials.banner')
@yield('content')


@include($theme.'partials.footer')


@stack('extra-content')
@stack('loadModal')

<script src="{{asset($themeTrue.'js/lib/jquery-3.6.0.min.js')}}"></script>


<script src="{{asset($themeTrue.'js/lib/bootstrap.min.js')}}"></script>
@stack('extra-js')
<script src="{{asset($themeTrue.'js/lib/slick.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/lib/odometer.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/lib/viewport.jquery.js')}}"></script>
<script src="{{asset($themeTrue.'js/notiflix-aio-2.7.0.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/pusher.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/vue.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/axios.min.js')}}"></script>


<script src="{{asset($themeTrue.'js/main.js')}}"></script>
@auth

@endauth
@stack('script')

@include($theme.'partials.notification')
@include('plugins')

</body>
</html>
