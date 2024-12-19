@if(!request()->routeIs('home'))
    <!-- Inner Banner Starts Here -->
    <div class="inner-banner " style="background: url({{getFile(config('location.logo.path').'banner.jpg')}}) center;">
        <div class="container">
            <div class="inner-banner-wrapper">
                <h2 class="title">@yield('title')</h2>
                <ul class="breadcrumbs d-flex flex-wrap justify-content-center">
                    <li><a href="{{route('home')}}">{{trans('Home')}}</a></li>
                    <li>@yield('title')</li>
                </ul>
            </div>
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/banner/shape-bg.png')}}" alt="banner">
        </div>
    </div>
    <!-- Inner Banner Ends Here -->
@endif
