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
                                        <form action="{{$data->url}}" method="{{$data->method}}">
                                            <script src="{{$data->checkout_js}}"
                                                    @foreach($data->val as $key=>$value)
                                                    data-{{$key}}="{{$value}}"
                                                @endforeach >
                                            </script>
                                            <input type="hidden" custom="{{$data->custom}}" name="hidden">
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


    </section>


    @push('script')
        <script>
            $(document).ready(function () {
                $('input[type="submit"]').addClass(" btn-custom2 btn btn--base btn--md active justify-content-center btn--block");
            })
        </script>
    @endpush
@endsection




