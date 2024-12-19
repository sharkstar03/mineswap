@extends($theme.'layouts.app')
@section('title','403 Forbidden')


@section('content')
    <section id="error" class="feature-section padding-top padding-bottom bg_img">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center">
                <div class="error-wrapper">
                    <div class="error-content">
                        <h2 class="text--base">@lang('opps!')</h2>
                        <h3 class="h3 my-4 font-weight-bold">@lang("You don’t have permission to access ‘/’ on this server.")</h3>
                        <a class="cmn--btn" href="{{url('/')}}">@lang('Back To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
