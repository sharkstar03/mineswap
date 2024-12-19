@extends($theme.'layouts.user')
@section('title',trans('Transaction'))
@section('content')


    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">

            <div class="card gradient-bg form-block p-0 br-4">
                <div class="card-body">
                    <form action="{{route('user.transaction.search')}}" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="text" name="transaction_id"
                                           value="{{@request()->transaction_id}}"
                                           class="form-control form--control style--two"
                                           placeholder="@lang('Search for Transaction ID')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="text" name="remark" value="{{@request()->remark}}"
                                           class="form-control form--control style--two"
                                           placeholder="@lang('Remark')">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="date" class="form-control form--control style--two" name="datetrx" id="datepicker"/>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-0 h-fill">
                                    <button type="submit" class="cmn--btn ">
                                        <i class="fas fa-search"></i> @lang('Search')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <table class="table transection__table mt-5">
                <thead>
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('Transaction ID')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Remarks')</th>
                    <th>@lang('Time')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td data-label="@lang('SL No.')"><span class="transection-id">{{loopIndex($transactions) + $loop->index}}</span></td>
                    <td data-label="@lang('Transaction ID')">@lang($transaction->trx_id)</td>
                    <td data-label="@lang('Amount')">
                        <span class="cost text--{{($transaction->trx_type == "+") ? 'success': 'danger'}}">
                            {{$transaction->trx_type}}{{getAmount($transaction->amount, ($transaction->balance_type == config('basic.currency')) ? config('basic.fraction_number') : 8). ' ' . trans($transaction->balance_type)}}
                        </span>
                    </td>
                    <td data-label="@lang('Remarks')">@lang($transaction->remarks)</td>
                    <td data-label="@lang('Time')"><span class="time"> {{ dateTime($transaction->created_at, 'd M, Y H:i') }}</span></td>
                </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="100%">{{trans('No Data Found!')}}</td>
                    </tr>
                @endforelse

                </tbody>
            </table>

            {{ $transactions->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->

@endsection
