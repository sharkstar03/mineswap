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
                                                class="btn btn--base btn--md active justify-content-center btn--block"
                                                id="btn-confirm">@lang('Pay with VoguePay')
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

        <script src="//voguepay.com/js/voguepay.js"></script>
        <script>
            let closedFunction = function () {

            }
            let  successFunction = function (transaction_id) {
                let txref = "{{ $data->merchant_ref }}";
                window.location.href = '{{ url('payment/voguepay') }}/' + txref + '/' + transaction_id;
            }
            let  failedFunction = function (transaction_id) {
                window.location.href = '{{ route('failed') }}';
            }

            function pay(item, price) {
                //Initiate voguepay inline payment
                Voguepay.init({
                    v_merchant_id: "{{ $data->v_merchant_id}}",
                    total: price,
                    notify_url: "{{ $data->notify_url }}",
                    cur: "{{$data->cur}}",
                    merchant_ref: "{{ $data->merchant_ref }}",
                    memo: "{{$data->memo}}",
                    recurrent: true,
                    frequency: 10,
                    developer_code: '5cff7398d89d1',
                    store_id: "{{ $data->store_id }}",
                    custom: "{{ $data->custom }}",

                    closed: closedFunction,
                    success: successFunction,
                    failed: failedFunction
                });
            }


            $(document).on('click', '#btn-confirm', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });

        </script>
    @endpush

@endsection

