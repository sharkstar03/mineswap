@extends($theme.'layouts.app')
@section('title','500')


@section('content')
    <section id="error" class="feature-section padding-top padding-bottom bg_img">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="error-wrapper">
                    <div class="error-content">
                        <h2 class="text--base">@lang('opps!')</h2>
                        <h3 class="h3 my-4 font-weight-bold">@lang("Internal Server Error")</h3>
                        <p class="mb-3">
                            @lang("The server encountered an internal error misconfiguration and was unable to complate your request. Please contact the server administrator.")
                        </p>
                        <a class="cmn--btn" href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
