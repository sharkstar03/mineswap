@extends('admin.layouts.app')
@section('title')
    @lang($user->username)
@endsection
@section('content')


    <div class="m-0 m-md-4 my-4 m-md-0">
        <div class="row">

            <div class="col-md-4">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Profile')</h4>
                        <div class="form-group text-center">
                            <img class="rounded mx-auto d-block w-50"
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

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Last Login')
                                <span>{{ diffForHumans($user->last_login) }}</span></li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('IP')
                                <span>{{ $user->last_login_ip }}</span></li>
                        </ul>
                    </div>
                </div>


                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('More Information')</h4>


                        <div class="btn-list ">
                            @if(adminAccessRoute(config('role.user_management.access.edit')))
                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal"
                                        data-backdrop='static' data-keyboard='false'
                                        data-target="#balance">
                                    <span class="btn-label"><i class="fas fa-hand-holding-usd"></i></span>
                                    @lang('Add/Subtract Fund')
                                </button>
                            @endif

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


                                <button class="btn btn-primary btn-sm loginAccount" type="button" data-toggle="modal"
                                        data-target="#signIn"
                                        data-route="{{route('admin.user-loginAccount',$user->id)}}">
                                    <span class="btn-label"><i class="fas fa-sign-in-alt"></i></span>
                                    @lang('Login as User')
                                </button>


                        </div>


                    </div>
                </div>

            </div>

            <div class="col-md-8">

                <div class="row">
                    @foreach($wallets as $wallet)
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                            <div class="card border-right shadow">
                                <div class="card-body">
                                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                                        <div>
                                            <div class="d-inline-flex align-items-center">
                                                <h2 class="text-dark mb-1 wallet-range">{{getAmount($wallet->balance,8)}}</h2>
                                            </div>
                                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">{{$wallet->code}} @lang('Balance')
                                            </h6>
                                        </div>
                                        <div class="ml-auto mt-md-3 mt-lg-0">
                                        <span class="opacity-7 text-muted">
                                             @if(file_exists('assets/crypto/'.strtoupper($wallet->code).'.png') )
                                                <img src="{{asset('assets/crypto/'.strtoupper($wallet->code).'.png')}}"
                                                     alt="{{$wallet->code}}" class="width-25px">
                                            @else
                                                <img src="{{asset('assets/crypto/.png')}}" alt="{{$wallet->code}}"
                                                     class="width-25px">
                                            @endif
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>

                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucfirst($user->username) }} @lang('Information')</h4>
                        <form method="post" action="{{ route('admin.user-update', $user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('First Name')</label>
                                        <input class="form-control" type="text" name="firstname"
                                               value="{{ $user->firstname }}"
                                               required>
                                        @error('firstname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Last Name')</label>
                                        <input class="form-control" type="text" name="lastname"
                                               value="{{ $user->lastname }}"
                                               required>
                                        @error('lastname')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Username')</label>
                                        <input class="form-control" type="text" name="username"
                                               value="{{ $user->username }}" required>
                                        @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Email')</label>
                                        <input class="form-control" type="email" name="email" value="{{ $user->email }}"
                                               required>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>@lang('Phone Number')</label>
                                        <input class="form-control" type="text" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group ">
                                        <label>@lang('Preferred language')</label>

                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value="" disabled>@lang('Select Language')</option>
                                            @foreach($languages as $la)
                                                <option value="{{$la->id}}"
                                                    {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}>@lang($la->name)</option>
                                            @endforeach
                                        </select>

                                        @error('language_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('Address')</label>
                                        <textarea class="form-control" name="address" rows="2">{{ $user->address }}</textarea>
                                        @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>@lang('Status')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='status'>
                                                <input type="checkbox" name="status" class="custom-switch-checkbox"
                                                       id="status" {{ $user->status == 0 ? 'checked' : '' }} >
                                                <label class="custom-switch-checkbox-label" for="status">
                                                    <span class="status custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <label>@lang('Email Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='email_verification'>
                                                <input type="checkbox" name="email_verification"
                                                       class="custom-switch-checkbox"
                                                       id="email_verification" {{ $user->email_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="email_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>@lang('SMS Verification')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='1' name='sms_verification'>
                                                <input type="checkbox" name="sms_verification"
                                                       class="custom-switch-checkbox"
                                                       id="sms_verification" {{ $user->sms_verification == 0 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="sms_verification">
                                                    <span class="verify custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label>@lang('2FA Secturity')</label>
                                            <div class="custom-switch-btn w-md-80">
                                                <input type='hidden' value='0' name='two_fa_verify'>
                                                <input type="checkbox" name="two_fa_verify"
                                                       class="custom-switch-checkbox"
                                                       id="two_fa_verify" {{ $user->two_fa_verify == 1 ? 'checked' : '' }}>
                                                <label class="custom-switch-checkbox-label" for="two_fa_verify">
                                                    <span class="custom-switch-checkbox-inner"></span>
                                                    <span class="custom-switch-checkbox-switch"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3  text-center text-md-left">
                                <button type="submit"
                                        class=" btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update User')</span></button>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h4 class="card-title">@lang('Password Change')</h4>

                        <form method="post" action="{{ route('admin.userPasswordUpdate',$user->id) }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label>@lang('New Password')</label>
                                        <input id="new_password" type="password" class="form-control" name="password"
                                               autocomplete="current-password">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group ">
                                        <label>@lang('Confirm Password')</label>
                                        <input id="confirm_password" type="password" name="password_confirmation"
                                               autocomplete="current-password" class="form-control">
                                        @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn-wrapper mt-md-3 text-center text-md-left">
                                <button type="submit"
                                        class="btn waves-effect waves-light btn-rounded btn-primary btn-block">
                                    <span>@lang('Update Password')</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <!-- The Modal -->
    <div class="modal fade" id="balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.user-balance-update',$user->id) }}"
                      enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Add / Subtract Balance')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group ">
                            <label>@lang('Amount')</label>
                            <input class="form-control" type="text" name="balance" id="balance">
                        </div>

                        <div class="form-group">
                            <div class="custom-switch-btn w-md-100">
                                <input type='hidden' value='1' name='add_status'>
                                <input type="checkbox" name="add_status" class="custom-switch-checkbox" id="add_status"
                                       value="0">
                                <label class="custom-switch-checkbox-label" for="add_status">
                                    <span class="modal_status custom-switch-checkbox-inner"></span>
                                    <span class="custom-switch-checkbox-switch"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary balanceSave"><span>@lang('Submit')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <!-- The Modal -->
    <div class="modal fade" id="signIn">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="" class="loginAccountAction" enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title">@lang('Sing In Confirmation')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>@lang('Are you sure to sign in this account?')</p>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
                        </button>
                        <button type="submit" class=" btn btn-primary "><span>@lang('Yes')</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        "use strict";

        $(document).on('click', '.loginAccount', function () {
            var route = $(this).data('route');
            $('.loginAccountAction').attr('action', route)
        });

        $(document).ready(function () {
            $(document).on('click', '.balanceSave', function () {
                var bala = $('#balance').text();
            });


            $('select').select2({
                selectOnClose: true
            });
        });


    </script>
@endpush


