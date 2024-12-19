@extends($theme.'layouts.app')
@section('title',trans('Login'))


@section('content')
    <!-- Contact Banner Starts Here -->
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
                        <h2 class="title mb-3">@lang('Sign In Your Account')</h2>
                        <p class="mb-4 pb-3">@lang('Please Insert your login credentials to access your data.')</p>
                        <form class="contact-form row gy-4" action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" type="text" name="username" value="{{old('username')}}" placeholder="@lang('Email Or Username')">
                                @error('username')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                @error('email')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="password" id="password" type="password"  placeholder="@lang('Password')">
                                @error('password')
                                <span class="text-danger mt-1">{{ $message }}</span>
                                @enderror
                            </div>


                            @if(basicControl()->reCaptcha_status_login)
                                <div class="form-group col-md-12">
                                    {!! NoCaptcha::renderJs(session()->get('trans')) !!}
                                    {!! NoCaptcha::display([]) !!}
                                    @error('g-recaptcha-response')
                                    <span class="text-danger mt-1">@lang($message)</span>
                                    @enderror
                                </div>
                            @endif


                            <div class="d-flex justify-content-between">
                                <div class="form--group form--check ">
                                    <input type="checkbox" id="check01" name="remember" {{ old('remember') ? 'checked' : '' }} >
                                    <label for="check01">@lang('Remember me')</label>
                                </div>
                                <div class="form--group forgot-pass">
                                    <a  href="{{ route('password.request') }}">@lang('Forgot Password?')</a>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <button class="btn btn--base px-5" type="submit">@lang('Sign In')</button>
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
