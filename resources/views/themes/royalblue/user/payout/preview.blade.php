@extends($theme.'layouts.user')
@section('title', trans($title))
@section('content')


    <!-- Dashboard Starts Here -->
    <div class="padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-6">
                    <div class="card custom--card">
                        <div class="card--header gradient-bg text-center p-3 py-sm-4 px-sm-4">
                            <h4 class="title text-white m-0">@lang("Transaction Preview")</h4>
                        </div>
                        <div class="card--body gradient-bg p-0">
                            <div class="deposit-preview-body p-4">
                                <div class="deposit-group">
                                    <h6 class="title">@lang("Transaction ID"):</h6>
                                    <div class="value">
                                        {{$withdraw->trx_id}}
                                    </div>
                                </div>
                                <div class="deposit-group">
                                    <h6 class="title">@lang("Amount"):</h6>
                                    <div class="value">
                                        {{$withdraw->amount}} {{$withdraw->code}}
                                    </div>
                                </div>

                                <div class="deposit-group">
                                    <h6 class="title">@lang("Charge"):</h6>
                                    <div class="value">
                                        {{$withdraw->charge}} {{$withdraw->code}}
                                    </div>
                                </div>

                                <div class="deposit-group">
                                    <h6 class="title">@lang("Payment Status"):</h6>
                                    <div class="value">
                                        @if($withdraw->status == 1)
                                            <span class="badge bg--warning">@lang('Pending')</span>
                                        @elseif($withdraw->status == 2)
                                            <span class="badge bg--success">@lang('Complete')</span>
                                        @elseif($withdraw->status == 3)
                                            <span class="badge bg--danger">@lang('Cancel')</span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard Ends Here -->



@endsection

