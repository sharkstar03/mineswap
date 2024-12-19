@extends($theme.'layouts.app')
@section('title',trans('Reset Password'))


@section('content')
    <!-- Contact Banner Starts Here -->
    <div class="account-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5 justify-content-between">
                <div class="col-lg-6 col-xl-6">
                    <div class="section-thumb rtl me-lg-5 d-sm-block d-none ">
                        <img src="{{getFile(config('location.logo.path').'form_thumbs.png') ? : 0}}" alt="account">
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5">
                    <div class="account-form-wrapper">
                        <h2 class="title mb-3">@lang('Reset Your Account')</h2>
                        <p class="mb-4 pb-3">@lang('Please Insert your email address to retrieve your account.')</p>

                        @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <div>
                                {{ trans(session('status')) }}
                            </div>
                        </div>
                        @endif

                        <form class="contact-form row gy-4" action="{{route('password.email')}}" method="post">
                            @csrf
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" type="email" name="email" value="{{old('email')}}" placeholder="@lang('Enter your Email Address')">
                                @error('email')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-12">
                                <button class="btn btn--base px-5" type="submit">@lang('Send Password Reset Link')</button>
                            </div>
                        </form>
                        <span class="subtitle mt-4">@lang("Don't have an account yet?")</span>
                        <a href="{{ route('register') }}" class="create-account text--base ms-2">@lang("Create Account")</a>
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

