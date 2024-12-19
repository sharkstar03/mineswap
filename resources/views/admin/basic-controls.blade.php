@extends('admin.layouts.app')
@section('title')
    @lang('Basic Controls')
@endsection
@section('content')


    <div class="alert alert-warning my-5 m-0 m-md-4" role="alert">
        <i class="fas fa-info-circle mr-2"></i> @lang("If you get 500(server error) for some reason, please turn on <b>Error Log</b> and try again. Then you can see what was missing in your system.")
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">

            <form method="post" action="" novalidate="novalidate" class="needs-validation base-form">
                @csrf
                <div class="row">
                    @include('errors.error')
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">@lang('Site Title')</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title') ?? $control->site_title ?? 'Site Title' }}"
                               class="form-control ">

                        @error('site_title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">@lang('Base Color') </label>
                        <input type="color" name="base_color"
                               value="{{ old('base_color') ?? $control->base_color ?? '#6777ef' }}"
                               required="required" class="form-control ">
                        @error('base_color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">@lang('TimeZone')</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="time_zone">
                            <option hidden>{{ old('time_zone', $control->time_zone)?? 'Select Time Zone' }}</option>
                            @foreach ($control->time_zone_all as $time_zone_local)
                                <option value="{{ $time_zone_local }}">@lang($time_zone_local)</option>
                            @endforeach
                        </select>

                        @error('time_zone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-sm-3 col-12">
                        <label class="font-weight-bold">@lang('Base Currency')</label>
                        <input type="text" name="currency" value="{{ old('currency') ?? $control->currency ?? 'USD' }}"
                               required="required" class="form-control ">

                        @error('currency')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-3 col-12">
                        <label class="font-weight-bold">@lang('Currency Symbol')</label>
                        <input type="text" name="currency_symbol"
                               value="{{ old('currency_symbol') ?? $control->currency_symbol ?? '$' }}"
                               required="required" class="form-control ">

                        @error('currency_symbol')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-sm-3 col-12">
                        <label class="font-weight-bold">@lang('Fraction number')</label>
                        <input type="text" name="fraction_number"
                               value="{{ old('fraction_number') ?? $control->fraction_number ?? '2' }}"
                               required="required" class="form-control ">
                        @error('fraction_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-sm-3 col-12">
                        <label class="font-weight-bold">@lang('Paginate Per Page')</label>
                        <input type="text" name="paginate" value="{{ old('paginate') ?? $control->paginate ?? '20' }}"
                               required="required" class="form-control ">
                        @error('paginate')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>




                    <div class="form-group col-sm-3 col-12">
                        <label class="font-weight-bold">@lang('Joining Bonus Amount')</label>
                        <div class="input-group mb-3">
                            <input type="text" name="bonus_amount" value="{{ old('bonus_amount') ?? $control->bonus_amount ?? '0' }}"
                                   required="required" class="form-control ">

                            <div class="input-group-append">
                                <span class="input-group-text" >{{trans($control->currency_symbol)}}</span>
                            </div>
                        </div>
                        @error('bonus_amount')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                </div>


                <div class="row">


                    <div class="form-group col-sm-3 ">
                        <label class="font-weight-bold">@lang('Joining bonus')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='joining_bonus'>
                            <input type="checkbox" name="joining_bonus" class="custom-switch-checkbox"
                                   id="joining_bonus"
                                   value="0" <?php if ($control->joining_bonus == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="joining_bonus">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-sm-3 ">
                        <label class="font-weight-bold">@lang('Plan Sale Commission')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='plan_sale_commission'>
                            <input type="checkbox" name="plan_sale_commission" class="custom-switch-checkbox"
                                   id="plan_sale_commission"
                                   value="0" <?php if ($control->plan_sale_commission == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="plan_sale_commission">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="font-weight-bold">@lang('Deposit Commission')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='deposit_commission'>
                            <input type="checkbox" name="deposit_commission" class="custom-switch-checkbox"
                                   id="deposit_commission"
                                   value="0" <?php if ($control->deposit_commission == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="deposit_commission">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="font-weight-bold">@lang('Strong Password')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='strong_password'>
                            <input type="checkbox" name="strong_password" class="custom-switch-checkbox"
                                   id="strong_password"
                                   value="0" {{($control->strong_password == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="strong_password">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="font-weight-bold">@lang('Registration')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='registration'>
                            <input type="checkbox" name="registration" class="custom-switch-checkbox"
                                   id="registration"
                                   value="0" {{($control->registration == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="registration">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-sm-6 col-md-4 col-lg-3">
                        <label class="font-weight-bold">@lang('Address Verification')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='address_verification'>
                            <input type="checkbox" name="address_verification" class="custom-switch-checkbox"
                                   id="address_verification"
                                   value="0" {{($control->address_verification == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="address_verification">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4 col-lg-3">
                        <label class="font-weight-bold">@lang('Identity Verification')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='identity_verification'>
                            <input type="checkbox" name="identity_verification" class="custom-switch-checkbox"
                                   id="identity_verification"
                                   value="0" {{($control->identity_verification == 0) ? 'checked' : ''}} >
                            <label class="custom-switch-checkbox-label" for="identity_verification">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="font-weight-bold">@lang('Error Log')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='error_log'>
                            <input type="checkbox" name="error_log" class="custom-switch-checkbox"
                                   id="error_log"
                                   value="0" <?php if ($control->error_log == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="error_log">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>





                </div>

                <div class="row">


                    <div class="form-group col-sm-3">
                        <label class="d-block">@lang('Cron Set Up Pop Up')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='is_active_cron_notification'>
                            <input type="checkbox" name="is_active_cron_notification" class="custom-switch-checkbox"
                                   id="is_active_cron_notification"
                                   value="0" <?php if ($control->is_active_cron_notification == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="is_active_cron_notification">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 ">
                        <label class="font-weight-bold">@lang('Maintenance Mode')</label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='maintenance'>
                            <input type="checkbox" name="maintenance" class="custom-switch-checkbox"
                                   id="maintenance"
                                   value="0" <?php if ($control->maintenance == 0):echo 'checked'; endif ?> >
                            <label class="custom-switch-checkbox-label" for="maintenance">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="font-weight-bold">@lang('Maintenance Message')</label>
                        <input type="text" name="maintenance_message" value="{{old('maintenance_message',$control->maintenance_message)}}" required="required" class="form-control ">
                    </div>


                </div>


                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        "use strict";
        $(document).ready(function () {
            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endpush
