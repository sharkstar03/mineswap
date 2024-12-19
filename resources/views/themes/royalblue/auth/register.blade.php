@extends($theme.'layouts.app')
@section('title',trans('Sign Up'))


@section('content')
    <!-- Contact Banner Starts Here -->
    <div class="account-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5 justify-content-between flex-column-reverse flex-lg-row">
                <div class="col-lg-6 col-xl-5">
                    <div class="account-form-wrapper">
                        <h2 class="title mb-3">@lang('Create Your Account')</h2>
                        <form class="contact-form row gy-4" action="{{ route('register') }}" method="post">
                            @csrf

                            @if(session()->get('sponsor') != null)
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="sponsor" id="sponsor"   placeholder="{{trans('Sponsor By') }}" type="text"  value="{{session()->get('sponsor')}}" readonly>
                            </div>
                            @endif

                            <div class="form-group col-md-6">
                                <input class="form-control form--control" type="text" name="firstname" value="{{old('firstname')}}" placeholder="@lang('First Name')" required>
                                @error('firstname')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <input class="form-control form--control" type="text" name="lastname" value="{{old('lastname')}}" placeholder="@lang('Last Name')" required>
                                @error('lastname')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control" type="text" name="username" value="{{old('username')}}" placeholder="@lang('Username')">
                                @error('username')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control"  name="email" value="{{old('email')}}" placeholder="@lang('Email Address')" required>
                                @error('email')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-12">
                                @php
                                    $country_code = (string) getIpInfo()['code'] ?: null;
                                @endphp

                                <div class="input-group ">
                                    <div class="input-group-prepend w-50">
                                        <select name="phone_code" class="form-control form--control  dialCode-change">
                                            @foreach(config('country') as $value)

                                                <option value="{{$value['phone_code']}}"
                                                        data-name="{{$value['name']}}"
                                                        data-code="{{$value['code']}}"
                                                    {{$country_code == $value['code'] ? 'selected' : ''}}
                                                > {{$value['phone_code']}} <strong>({{$value['name']}}
                                                        )</strong>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" name="phone" class="form-control dialcode-set"
                                           value="{{old('phone')}}"
                                           placeholder="@lang('Your Phone Number')">
                                </div>


                                @error('phone')
                                <span class="text-danger mt-1">{{ $message }}</span>
                                @enderror

                                <input type="hidden" name="country_code" value="{{old('country_code')}}"
                                       class="text-dark">

                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="password" id="password" type="password" placeholder="@lang('Password')" required>
                                @error('password')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="password_confirmation" id="password_confirmation" type="password" placeholder="@lang('Confirm Password')" required>
                            </div>

                            @if(basicControl()->reCaptcha_status_registration)
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
                                    <input type="checkbox" id="check01" required>
                                    <label for="check01">@lang("Accepting all the terms & conditions")</label>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <button class="btn btn--base px-5" type="submit">@lang('Sign Up')</button>
                            </div>
                        </form>
                        <span class="subtitle mt-4">@lang("Already have an account?")</span>
                        <a href="{{route('login')}}" class="create-account text--base ms-2">@lang("Login Now")</a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6">
                    <div class="section-thumb ms-lg-5 d-sm-block d-none ">
                        <img src="{{getFile(config('location.logo.path').'form_thumbs.png') ? : 0}}" alt="account">
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

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            setDialCode();
            $(document).on('change', '.dialCode-change', function () {
                setDialCode();
            });
            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);

                $('input[name=country_code]').val($('.dialCode-change option:selected').data('code'));
            }

        });

    </script>
@endpush
