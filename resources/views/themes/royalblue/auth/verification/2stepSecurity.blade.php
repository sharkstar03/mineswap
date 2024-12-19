@extends($theme.'layouts.app')
@section('title',$page_title)

@section('content')
    <div class="account-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5 justify-content-between">
                <div class="col-lg-6 col-xl-6 ">
                    <div class="section-thumb rtl me-lg-5 d-sm-block d-none ">
                        <img src="{{getFile(config('location.logo.path').'form_thumbs.png') ? : 0}}" alt="account">
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5">
                    <div class="account-form-wrapper">
                        <h2 class="title mb-3">@lang($page_title)</h2>
                        <form class="contact-form row gy-4" action="{{route('user.twoFA-Verify')}}"  method="post">
                            @csrf
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')">
                                @error('code')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                @error('error')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-12">
                                <button class="btn btn--base px-5" type="submit">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Contact Banner Ends Here -->


@endsection
