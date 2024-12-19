@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection

@section('content')
    @push('style')
        <link href="{{ asset('assets/admin/css/card-js.min.css') }}" rel="stylesheet" type="text/css"/>
        <style>
            .card-js .icon {
                top: 5px;
            }
        </style>
    @endpush


    <div class="padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-8">
                    <div class="card custom--card gradient-bg p-4">
                        <div class="card-body ">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <h4 class="title text-center"> @lang('Your Card Information')</h4>
                                </div>

                                <div class="col-md-3">
                                        <img
                                            src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                                            class="w-75 " alt="..">

                                </div>

                                <div class="col-md-9">
                                    <div class="deposit-preview-body p-4">
                                        <form class="form-horizontal" id="example-form"
                                              action="{{ route('ipn', [optional($order->gateway)->code ?? '', $order->transaction]) }}"
                                              method="post">
                                            <div class="card-js form-group --payment-card">
                                                <input class="card-number form-control"
                                                       name="card_number"
                                                       placeholder="@lang('Enter your card number')"
                                                       autocomplete="off"
                                                       required>
                                                <input class="name form-control"
                                                       id="the-card-name-id"
                                                       name="card_name"
                                                       placeholder="@lang('Enter the name on your card')"
                                                       autocomplete="off"
                                                       required>
                                                <input class="expiry form-control"
                                                       autocomplete="off"
                                                       required>
                                                <input class="expiry-month" name="expiry_month">
                                                <input class="expiry-year" name="expiry_year">
                                                <input class="cvc form-control"
                                                       name="card_cvc"
                                                       autocomplete="off"
                                                       required>
                                            </div>
                                            <button type="submit"
                                                    class="btn btn--base btn--md active justify-content-center btn--block mt-3">@lang('Submit')</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('assets/admin/js/card-js.min.js') }}"></script>
    @endpush

@endsection
