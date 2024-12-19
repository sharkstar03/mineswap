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
                        <div class="card-body text-center">

                            <h4 class="title"> @lang('PLEASE SEND EXACTLY') <span
                                    class="text-success"> {{ getAmount($data->amount) }}</span> {{@$data->currency}}  {{@$data->gateway_currency}}
                            </h4>
                            <h5 class="my-3">@lang('TO') <span class="text-success"> {{ $data->sendto }}</span></h5>
                            <img src="{{$data->img}}" alt="..">
                            <h4 class="text--base font-weight-bold">@lang('SCAN TO SEND')</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>



@endsection



