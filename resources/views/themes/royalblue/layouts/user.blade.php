<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&family=Open+Sans:wght@300;400&display=swap">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/animate.css')}}">
    @stack('css-lib')

    <link rel="stylesheet" href="{{asset($themeTrue.'css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/all.min.css')}}">

    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/slick.css')}}">
    <link rel="stylesheet" href="{{asset($themeTrue.'css/lib/odometer.css')}}">

    <link rel="stylesheet" href="{{asset($themeTrue.'css/main.css')}}">
    @stack('style')

</head>
<body>

<div class="overlay"></div>
<a href="javascript:void(0)" class="scrollToTop"><i class="las la-chevron-up"></i></a>

<div class="header">
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area" id="pushNotificationArea">
                <div class="logo">
                    <a href="{{url('/')}}"><img src="{{getFile(config('location.logoIcon.path').'logo.png')}}"
                                                alt="{{config('basic.site_title')}}"></a>
                </div>
                <ul class="menu">
                    <li>
                        <a href="{{route('home')}}">@lang('Home')</a>
                    </li>


                    <li class="d-lg-none d-block "><a href="{{route('user.home')}}">@lang('Dashboard')</a></li>
                    <li>
                        <a href="javascript:void(0)">@lang('Plan')</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('plan')}}">@lang('Plan List')</a></li>
                            <li><a href="{{route('user.plan-log')}}">@lang('Purchase Log')</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)">@lang('Fund')</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('user.addFund')}}">@lang('Add Fund')</a></li>
                            <li><a href="{{route('user.fund-history')}}">@lang('Payment Log')</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0)">@lang('Payout')</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('user.payout.money')}}">@lang('Payout Now')</a></li>
                            <li><a href="{{route('user.payout.history')}}">@lang('Payout History')</a></li>
                        </ul>
                    </li>

                    <li class="d-lg-none d-block "><a href="{{route('user.transaction')}}">@lang('Transaction')</a></li>
                    <li class="d-lg-none d-block "><a href="{{route('user.wallet')}}">@lang('Wallets')</a></li>
                    <li class="d-lg-none d-block "><a href="{{route('user.profile')}}">@lang('Profile Settings')</a>
                    </li>
                    <li class="d-lg-none d-block "><a href="{{route('user.referral')}}">@lang('My Referral')</a></li>
                    <li class="d-lg-none d-block "><a href="{{route('user.ticket.list')}}">@lang('Support Ticket')</a>
                    </li>
                    <li class="d-lg-none d-block "><a
                            href="{{route('user.twostep.security')}}">@lang('2FA Security')</a></li>
                    <li class="d-lg-none d-block "><a href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">@lang('Logout')</a></li>


                    @auth
                        <li class="p-0 d-none d-lg-block mx-lg-3 my-2">
                            <a href="javascript:void(0)" class="dashboard-link p-0"><img
                                    src="{{getFile(config('location.user.path').auth()->user()->image)}}"
                                    alt="dashboard-link"></a>
                            <ul class="sub-menu">
                                <li><a href="{{route('user.home')}}">@lang('Dashboard')</a></li>
                                <li><a href="{{route('user.transaction')}}">@lang('Transaction')</a></li>
                                <li><a href="{{route('user.wallet')}}">@lang('Wallets')</a></li>
                                <li><a href="{{route('user.profile')}}">@lang('Profile Settings')</a></li>
                                <li><a href="{{route('user.referral')}}">@lang('My Referral')</a></li>
                                <li><a href="{{route('user.ticket.list')}}">@lang('Support Ticket')</a></li>
                                <li><a href="{{route('user.twostep.security')}}">@lang('2FA Security')</a></li>
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">@lang('Logout')</a></li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        <li class="p-0 d-none d-lg-block mx-lg-3 my-2">
                            @include($theme.'partials.pushNotify')
                        </li>
                    @endauth

                </ul>

                <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    @auth
                        <div class="p-0  d-lg-block mx-lg-3 my-2">
                            <a href="javascript:void(0)" class="dashboard-link mobile p-0"><img
                                    src="{{getFile(config('location.user.path').auth()->user()->image)}}"
                                    alt="dashboard-link"></a>

                        </div>
                        <div class="p-0  d-lg-block mx-3 my-2">
                            @include($theme.'partials.pushNotify')
                        </div>
                    @endauth

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

<!-- jQuery library -->
<script src="{{asset($themeTrue.'js/lib/jquery-3.6.0.min.js')}}"></script>
<!-- bootstrap 5 js -->
<script src="{{asset($themeTrue.'js/lib/bootstrap.min.js')}}"></script>
@stack('extra-js')
<!-- Pluglin Link -->
<script src="{{asset($themeTrue.'js/lib/slick.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/lib/odometer.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/lib/viewport.jquery.js')}}"></script>
<script src="{{asset($themeTrue.'js/notiflix-aio-2.7.0.min.js')}}"></script>

<script src="{{asset($themeTrue.'js/pusher.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/vue.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/axios.min.js')}}"></script>


<!-- main js -->
<script src="{{asset($themeTrue.'js/main.js')}}"></script>

@auth
    <script>
        'use strict';
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            mounted() {
                this.getNotifications();
                this.pushNewItem();

                $('.notification-toggler').on('click', function () {
                    $('.dropdown-wrapper').hide(300);
                    if ($('.dropdown-wrapper').hasClass('active')) {
                        $('.dropdown-wrapper').removeClass('active')
                    } else {
                        $('.dropdown-wrapper').addClass('active')
                        $('.dropdown-wrapper').show(300);
                    }
                    $('.overlay').addClass('active');
                });

                // Responsive Menu
                var headerTrigger = $('.header-trigger');
                headerTrigger.on('click', function () {
                    $('.menu, .header-trigger').toggleClass('active')
                    $('.overlay').toggleClass('active')
                });

                //Menu Dropdown
                $("ul>li>.sub-menu").parent("li").addClass("has-sub-menu");


                $('.menu li a').on('click', function () {
                    var element = $(this).parent('li');
                    if (element.hasClass('open')) {
                        element.removeClass('open');
                        element.find('li').removeClass('open');
                        element.find('ul').slideUp(300, "swing");
                    } else {
                        element.addClass('open');
                        element.children('ul').slideDown(300, "swing");
                        element.siblings('li').children('ul').slideUp(300, "swing");
                        element.siblings('li').removeClass('open');
                        element.siblings('li').find('li').removeClass('open');
                        element.siblings('li').find('ul').slideUp(300, "swing");
                    }
                });
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link != '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>

@endauth
@stack('script')

@include($theme.'partials.notification')


@include('plugins')

</body>
</html>
