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
                        <h2 class="title mb-3">@lang('Password')</h2>

                        @if (session('status'))
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <div>
                                    {{ trans(session('status')) }}
                                </div>
                            </div>
                        @endif
                        @if (session('token'))
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <div>
                                    {{ trans($message) }}
                                </div>
                            </div>
                        @endif

                        <form class="contact-form row gy-4" action="{{route('password.update')}}" method="post">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="password" id="password" type="password"  placeholder="@lang('New Password')">
                                @error('password')
                                <span class="text-danger mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="password_confirmation" id="password_confirmation" type="password"  placeholder="@lang('Confirm Password')">

                            </div>


                            <div class="form-group col-12">
                                <button class="btn btn--base px-5" type="submit">@lang('Change Password')</button>
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
