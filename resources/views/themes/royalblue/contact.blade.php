@extends($theme.'layouts.app')
@section('title',trans($title))

@section('content')
    <!-- Contact Banner Starts Here -->
    <div class="contact-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5 justify-content-between">
                <div class="col-lg-5">
                    <div class="contact-page-info me-lg-4 me-xl-5">
                        @if($contact->address)
                        <div class="contact-info-item">
                            <div class="contact-info-item-icon">
                                <i class="fa fa-home big-icon"></i>
                            </div>
                            <div class="contact-info-item-content">
                                <h4 class="title">@lang('Address')</h4>
                                <p>@lang(@$contact->address)</p>
                            </div>
                        </div>
                        @endif

                        @if($contact->phone)
                        <div class="contact-info-item">
                            <div class="contact-info-item-icon">
                                <i class="fa fa-phone big-icon"></i>
                            </div>
                            <div class="contact-info-item-content">
                                <h4 class="title">@lang('Phone Number')</h4>
                                <p>@lang(@$contact->phone)</p>
                            </div>
                        </div>
                        @endif

                        @if($contact->email)
                        <div class="contact-info-item">
                            <div class="contact-info-item-icon">
                                <i class="fa fa-envelope big-icon"></i>
                            </div>
                            <div class="contact-info-item-content">
                                <h4 class="title">@lang("Email Address")</h4>
                                <p>@lang(@$contact->email)</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>


                <div class="col-lg-7">
                    <div class="form-wrapper">
                        <h2 class="title mb-2">@lang(@$contact->heading)</h2>
                        <p class="mb-4 pb-3">
                            @lang(@$contact->sub_heading)
                        </p>
                        <form class="contact-form row gy-4" action="{{route('contact.send')}}" method="post">
                            @csrf
                            <div class="form-group col-md-12">
                                <input class="form-control form--control" name="name" value="{{old('name')}}" type="text" placeholder="@lang('Name')">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control"  id="email" name="email" value="{{old('email')}}"
                                       type="email" placeholder="@lang('Email Address')">
                                @error('email')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <input class="form-control form--control"  name="subject"
                                       value="{{old('subject')}}" placeholder="@lang('Subject')">
                                @error('subject')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group col-xs-12">
                                <textarea class="form-control form--control" id="message" name="message" cols="30" rows="5" name="message" placeholder="@lang('Message')">{{old('message')}}</textarea>
                                @error('message')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <button class="btn btn--base" type="submit">{{trans('Send Message')}}</button>
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
