@extends($theme.'layouts.app')
@section('title','404')


@section('content')
    <!-- ERROR -->
    <section id="error" class="feature-section padding-top padding-bottom bg_img">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="error-wrapper">
                    <div class="error-content">
                        <h2 class="text--base">@lang('opps!')</h2>
                        <h3 class="h3 my-4 font-weight-bold">@lang("Sorry page was not found!")</h3>
                        <p class=" mb-3">
                            @lang("We're sorry, the page you requested could not be found. Please go back to the homepage or contact us at") {{config('basic.sender_email')}}
                        </p>
                        <a class="cmn--btn" href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /ERROR -->
@endsection
