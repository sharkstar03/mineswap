@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($order->gateway)->name ?? '' }}
@endsection
@section('content')

    <div class="padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-8">
                    <div class="card custom--card gradient-bg p-4">
                        <div class="card-body ">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img
                                        src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                                        class="w-100" alt="..">
                                </div>


                                <div class="col-md-9">
                                    <div class="deposit-preview-body p-4">
                                        <h4 class="title mb-3">@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h4>
                                        <button type="button"
                                                class="btn btn-primary base-btn"
                                                id="btn-confirm">@lang('Pay Now')</button>
                                        <form
                                            action="{{ route('ipn', [optional($order->gateway)->code, $order->transaction]) }}"
                                            method="POST">
                                            @csrf
                                            <script
                                                src="//js.paystack.co/v1/inline.js"
                                                data-key="{{ $data->key }}"
                                                data-email="{{ $data->email }}"
                                                data-amount="{{$data->amount}}"
                                                data-currency="{{$data->currency}}"
                                                data-ref="{{ $data->ref }}"
                                                data-custom-button="btn-confirm">
                                            </script>
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



@endsection

