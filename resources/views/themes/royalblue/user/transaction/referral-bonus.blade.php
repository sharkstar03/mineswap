@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')


    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">

            <div class="card gradient-bg form-block p-0 br-4">
                <div class="card-body">
                    <form action="{{route('user.referral.bonus.search')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <input type="text"
                                           name="search_user"
                                           value="{{@request()->search_user}}"
                                           class="form-control form--control style--two"
                                           placeholder="@lang('Search User')">
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <input type="date" class="form-control form--control style--two" name="datetrx" id="datepicker"/>
                                </div>
                            </div>

                            <div class="col-md-4">
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
                    <th>@lang('Bonus From')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Remarks')</th>
                    <th>@lang('Time')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td data-label="@lang('SL No.')">
                            {{loopIndex($transactions) + $loop->index}}</td>
                        <td data-label="@lang('Bonus From')">@lang(optional($transaction->bonusBy)->fullname)</td>
                        <td data-label="@lang('Amount')">
                            <span class=" text--success">{{getAmount($transaction->amount, config('basic.fraction_number')). ' ' . trans(config('basic.currency'))}}</span>
                        </td>

                        <td data-label="@lang('Remarks')"> @lang($transaction->remarks)</td>
                        <td data-label="@lang('Time')">
                            {{ dateTime($transaction->created_at, 'd M Y h:i A') }}
                        </td>
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
