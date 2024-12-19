@extends('admin.layouts.app')
@section('title',trans(@$title))
@section('content')


    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-md-4">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Profile')</h4>
                        <div class="form-group">
                            <img class="preview-image w-100"
                                 src="{{getFile(config('location.user.path').$user->image) }}"
                                 alt="preview image">
                        </div>
                        <h3> @lang(ucfirst($user->username))</h3>
                        <p>@lang('Joined At') @lang($user->created_at->format('d M,Y h:i A')) </p>
                    </div>
                </div>

                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User information')</h4>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Email')
                                <span>{{ $user->email }}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Username')
                                <span>{{ $user->username }}</span></li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Status')
                                <span
                                    class="badge badge-{{($user->status==1) ? 'success' :'danger'}} success badge-pill">{{($user->status==1) ? trans('Active') : trans('Inactive')}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Balance')
                                <span>{{ getAmount($user->balance, config('basic.fraction_number')) }} @lang(config('basic.currency')) </span>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('User action')</h4>


                        <div class="btn-list ">

                            @if(adminAccessRoute(config('role.user_management.access.view')))
                                <a href="{{ route('admin.user.transaction',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                <span class="btn-label"><i
                                        class="fas fa-exchange-alt"></i></span> @lang('Transaction Log')
                                </a>

                                <a href="{{ route('admin.user.fundLog',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                <span class="btn-label"><i
                                        class="fas fa-money-bill-alt"></i></span> @lang('Payment Log')
                                </a>

                                <a href="{{ route('admin.user.plan-purchaseLog',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i class="fa fa-seedling"></i></span> @lang('Purchase Log')
                                </a>
                            @endif

                            @if(adminAccessRoute(config('role.user_management.access.edit')))
                                <a href="{{ route('admin.user.wallet',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-wallet"></i></span> @lang('Investment Wallet')
                                </a>
                            @endif

                            @if(adminAccessRoute(config('role.user_management.access.view')))
                                <a href="{{ route('admin.user.withdrawal',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                <span class="btn-label"><i
                                        class="fas fa-hand-holding"></i></span> @lang('Payout History')
                                </a>
                            @endif

                            @if(adminAccessRoute(config('role.user_management.access.edit')))
                                <a href="{{ route('admin.send-email',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-envelope-open"></i></span> @lang('Send Email')
                                </a>
                            @endif
                            @if(adminAccessRoute(config('role.user_management.access.view')))
                                <a href="{{ route('admin.user.commissionLog',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i class="fas fa-piggy-bank"></i></span> @lang('Commission Log')
                                </a>

                                <a href="{{ route('admin.user.referralMember',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i class="fas fa-users"></i></span> @lang('Referral Member')
                                </a>

                                <a href="{{ route('admin.user.loggedIn',$user->id) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i class="fas fa-history"></i></span> @lang('Login Logs')
                                </a>
                            @endif

                            @if(adminAccessRoute(config('role.user_management.access.edit')))
                                <a href="{{ route('admin.user.userKycHistory',$user) }}"
                                   class="btn btn-info btn-sm">
                                    <span class="btn-label"><i
                                            class="fas fa-file-invoice"></i></span> @lang('KYC Records')
                                </a>
                            @endif



                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-8">

                <div class="card card-primary  shadow">
                    <div class="card-body">
                        <h4 class="card-title text-capitalize">{{$user->username}} @lang('Wallet')</h4>

                        @if(0 < count($wallets) )
                        <form method="post" action="{{ route('admin.user.walletUpdate',$user->id) }}"
                              enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            <div class="row">
                                @foreach($wallets as $key => $wallet)
                                    <div class="col-md-12">
                                        <div class="form-group ">

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
                                                <input type="text" class="form-control "
                                                       id="{{$wallet->code}}" name="{{trim($wallet->code)}}"
                                                       placeholder="@lang("Enter your $wallet->code Address")"
                                                       value="{{old($wallet->code,$wallet->wallet_address) }}">

                                            </div>


                                            @if($errors->has($wallet->code))
                                                <div class="error text-danger">@lang($errors->first($wallet->code)) </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach


                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update Wallet')</span></button>
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






@endsection

