@extends($theme.'layouts.user')
@section('title',trans($title))

@section('content')
    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-12">
                    <div class="custom--card card--lg">

                        <div class="card--body gradient-bg">
                            <div class="profile-form-area">

                                @if(0 < count($wallets) )
                                    <form class="profile-edit-form row gy-3" action="" method="post">
                                        @method('put')
                                        @csrf

                                        @foreach($wallets as $key => $wallet)
                                            <div class="form--group col-md-12">
                                                <label class="form-label d-flex  justify-content-between"
                                                       for="{{$wallet->code}}">{{$wallet->code}} @lang('Wallet Address')
                                                    @if($wallet->balance != 0)
                                                    <small>@lang('Balance'): {{getAmount($wallet->balance,8)}} {{strtoupper($wallet->code)}}</small>
                                                    @endif
                                                </label>

                                                <div class="input-group ">
                                                    <div class="input-group-prepend ">
                                                    <span class="input-group-text h-100">
                                                        @if(file_exists('assets/crypto/'.strtoupper($wallet->code).'.png') )
                                                            <img src="{{asset('assets/crypto/'.strtoupper($wallet->code).'.png')}}" alt="{{$wallet->code}}" class="width-25px">
                                                        @else
                                                            <img src="{{asset('assets/crypto/.png')}}" alt="{{$wallet->code}}" class="width-25px">
                                                        @endif
                                                    </span>
                                                    </div>
                                                    <input type="text" class="form-control form--control style--two"
                                                           id="{{$wallet->code}}" name="{{trim($wallet->code)}}"
                                                           placeholder="@lang("Enter your $wallet->code Address")"
                                                           value="{{old($wallet->code,$wallet->wallet_address) }}">

                                                </div>


                                                @if($errors->has($wallet->code))
                                                    <div
                                                        class="error text-danger">@lang($errors->first($wallet->code)) </div>
                                                @endif
                                            </div>
                                        @endforeach

                                        <div class="form--group w-100 col-md-6 mb-0">
                                            <button type="submit"
                                                    class="btn btn--base w-100 mt-3">@lang('Update Wallet')</button>
                                        </div>
                                    </form>
                                @else
                                    <h3 class="sub-title">@lang('No Wallet Found!')</h3>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->


@endsection

@push('css-lib')
@endpush
@push('script')

@endpush
