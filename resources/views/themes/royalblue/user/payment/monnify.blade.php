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
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img
                                        src="{{getFile(config('location.gateway.path').optional($order->gateway)->image)}}"
                                        class="w-100" alt="..">
                                </div>

                                <div class="col-md-9">
                                    <div class="deposit-preview-body p-4">
                                        <h4  class="title mb-3">@lang('Please Pay') {{getAmount($order->final_amount)}} {{$order->gateway_currency}}</h4>
                                        <button class="btn btn--base btn--md active justify-content-center btn--block mt-5"
                                                onclick="payWithMonnify()">@lang('Pay via Monnify')
                                        </button>

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
        <script type="text/javascript" src="//sdk.monnify.com/plugin/monnify.js"></script>
        <script type="text/javascript">
            function payWithMonnify() {
                MonnifySDK.initialize({
                    amount: {{ $data->amount ?? '0' }},
                    currency: "{{ $data->currency ?? 'NGN' }}",
                    reference: "{{ $data->ref }}",
                    customerName: "{{$data->customer_name ?? 'John Doe'}}",
                    customerEmail: "{{$data->customer_email ?? 'example@example.com'}}",
                    customerMobileNumber: "{{ $data->customer_phone ?? '0123' }}",
                    apiKey: "{{ $data->api_key }}",
                    contractCode: "{{ $data->contract_code }}",
                    paymentDescription: "{{ $data->description }}",
                    isTestMode: true,
                    onComplete: function (response) {
                        if (response.paymentReference) {
                            window.location.href = '{{ route('ipn', ['monnify', $data->ref]) }}';
                        } else {
                            window.location.href = '{{ route('failed') }}';
                        }
                    },
                    onClose: function (data) {
                    }
                });
            }
        </script>
    @endpush
@endsection
