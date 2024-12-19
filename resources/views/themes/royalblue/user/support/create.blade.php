@extends($theme.'layouts.user')
@section('title',trans($page_title))

@section('content')


    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="method__card">
                        <form action="{{route('user.ticket.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                                <div class="form-group ">
                                    <label class="form-label">@lang('Subject')</label>
                                    <input class="form-control form--control style--two" type="text" name="subject"
                                           value="{{old('subject')}}" placeholder="@lang('Enter Subject')">
                                    @error('subject')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>


                                <div class="form-group my-3">
                                    <label class="form-label">@lang('Message')</label>
                                    <textarea class="form-control form--control style--two" name="message" rows="5"
                                              id="textarea1"
                                              placeholder="@lang('Enter Message')">{{old('message')}}</textarea>
                                    @error('message')
                                    <div class="error text-danger">@lang($message) </div>
                                    @enderror
                                </div>


                            <div class="col-md-12">
                                <div class="form-group ">
                                    <input type="file" name="attachments[]"
                                           class="form-control form--control style--two"
                                           multiple
                                           placeholder="@lang('Upload File')">

                                    @error('attachments')
                                    <span class="text-danger">{{trans($message)}}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <button type="submit"
                                            class="cmn--btn cmn--btn-md w-100">
                                        <span>@lang('Submit')</span></button>

                                </div>
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

@endsection
