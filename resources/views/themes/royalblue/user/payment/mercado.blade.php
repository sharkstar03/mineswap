@extends($theme.'layouts.user')

@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection

@section('content')

    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">

            <div class="card gradient-bg form-block p-0 br-4">
                <div class="card-body ">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <img
                                src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                                class="w-100" alt="..">
                        </div>

                        <div class="col-md-9">
                            <div class="deposit-preview-body p-4">
                                <h3 class="title mb-3">@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h3>

                                <form
                                    action="{{ route('ipn', [optional($order->gateway)->code ?? 'mercadopago', $order->transaction]) }}"
                                    method="POST">
                                    <script
                                        src="https://www.mercadopago.com.co/integrations/v1/web-payment-checkout.js"
                                        data-preference-id="{{ $data->preference }}">
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
