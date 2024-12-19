@extends($theme.'layouts.user')
@section('title',trans('Profile Settings'))

@section('content')
    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="dashboard-user">

                        <form method="post" action="{{ route('user.updateProfile') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="user-thumb">
                                <img src="{{getFile(config('location.user.path').$user->image)}}" alt="user"
                                     id="image_preview_container" class="preview-image">

                                <div class="form-group user-up-icon">
                                    <input id="user-up" type="file" name="image" class="form-control d-none">
                                    <label class="user-up" for="user-up"><i class="las la-pen"></i></label>
                                </div>
                            </div>

                            @error('image')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                            <div class="form-group">
                                <button type="submit" class="cmn--btn cmn--btn-sm"><i
                                        class="la la-cloud-upload-alt"></i> @lang('Upload')</button>
                            </div>
                        </form>
                        <div class="user-content">
                            <span>@lang('Welcome')</span>
                            <h5 class="name">{{ucfirst($user->fullname)}}</h5>

                            @php
                                $country = null;
                                $list =collect(config('country'))->where('code',$user->country_code)->collapse();
                                if(isset($list)){
                                    $country = $list['name'];
                                }
                            @endphp

                            <ul class="user-info m-0">
                                @if($country)
                                    <li>
                                        <i class="las la-globe"></i>
                                        @lang($country)
                                    </li>
                                @endif
                                @if($user->email)
                                    <li>
                                        <i class="las la-envelope-open"></i>
                                        {{$user->email}}
                                    </li>
                                @endif
                                @if($user->email)
                                    <li>
                                        <i class="las la-phone-volume"></i>
                                        {{$user->phone}}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="custom--card card--lg">
                        <div class="card--header gradient-bg-rev d-flex align-items-start justify-content-start">
                            <ul class="nav nav-tabs nav--tabs m-0 text-left">
                                <li>
                                    <a href="#home"
                                       class="{{ $errors->has('profile') ? 'active' : (($errors->has('password') || $errors->has('identity') || $errors->has('addressVerification')) ? '' : ' active') }}"
                                       data-bs-toggle="tab">@lang('Profile Information')</a>
                                </li>
                                <li>
                                    <a href="#menu1" class="{{ $errors->has('password') ? 'active' : '' }}"
                                       data-bs-toggle="tab">@lang('Password Setting')</a>
                                </li>
                                <li>
                                    <a href="#identity" class="{{ $errors->has('identity') ? 'active' : '' }}"
                                       data-bs-toggle="tab">@lang('Identity Verification')</a>
                                </li>

                                <li>
                                    <a href="#addressVerification"
                                       class="{{ $errors->has('addressVerification') ? 'active' : '' }}"
                                       data-bs-toggle="tab">@lang('Address Verification')</a>
                                </li>
                            </ul>

                        </div>
                        <div class="card--body gradient-bg">
                            <div class="profile-form-area">

                                <div class="tab-content">
                                    <div class="tab-pane  fade {{ $errors->has('profile') ? 'show active' : (($errors->has('password') || $errors->has('identity') || $errors->has('addressVerification')) ? '' :  'show active') }}"
                                        id="home">
                                        <form class="profile-edit-form row gy-3"
                                              action="{{ route('user.updateInformation')}}" method="post">
                                            @method('put')
                                            @csrf
                                            <div class="form--group col-md-6">
                                                <label class="form-label" for="first-name">@lang('First Name')</label>
                                                <input type="text" class="form-control form--control style--two"
                                                       id="first-name" name="firstname"
                                                       value="{{old('firstname')?: $user->firstname }}">
                                                @if($errors->has('firstname'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('firstname')) </div>
                                                @endif
                                            </div>
                                            <div class="form--group col-md-6">
                                                <label class="form-label" for="last-name">@lang('Last Name')</label>
                                                <input type="text" class="form-control form--control style--two"
                                                       id="last-name" name="lastname"
                                                       value="{{old('lastname')?: $user->lastname }}">
                                                @if($errors->has('lastname'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('lastname')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-6">
                                                <label class="form-label" for="last-name">@lang('Username')</label>
                                                <input type="text" class="form-control form--control style--two"
                                                       id="username" name="username"
                                                       value="{{old('username')?: $user->username }}">
                                                @if($errors->has('username'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('lastname')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-6">
                                                <label class="form-label" for="email">@lang('Email Address')</label>
                                                <input type="email" class="form-control form--control style--two"
                                                       id="email"
                                                       value="{{old('email')?: $user->email }}" readonly>
                                                @if($errors->has('email'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('email')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-6">
                                                <label class="form-label" for="phone">@lang('Phone Number')</label>
                                                <input type="text" class="form-control form--control style--two"
                                                       id="phone"
                                                       value="{{old('phone')?: $user->phone }}" readonly>
                                                @if($errors->has('phone'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('email')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-6">
                                                <label class="form-label"
                                                       for="language_id">@lang('Preferred language')</label>
                                                <select name="language_id" id="language_id"
                                                        class="form-control form--control style--two">
                                                    <option value="" disabled>@lang('Select Language')</option>
                                                    @foreach($languages as $la)
                                                        <option value="{{$la->id}}"
                                                            {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}>@lang($la->name)</option>
                                                    @endforeach
                                                </select>

                                                @if($errors->has('language_id'))
                                                    <div class="error text-danger">@lang($errors->first('language_id')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-12">
                                                <label class="form-label" for="address">@lang('Address')</label>
                                                <input type="text" class="form-control form--control style--two"
                                                       id="address" name="address"
                                                       value="{{old('address',$user->address)}}">
                                                @if($errors->has('address'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('address')) </div>
                                                @endif
                                            </div>
                                            <div class="form--group w-100 col-md-6 mb-0">
                                                <button type="submit"
                                                        class="btn btn--base w-100 mt-3">@lang('Update Information')</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade {{ $errors->has('password') ? 'show active' : '' }}"
                                         id="menu1">

                                        <form class="profile-edit-form row gy-3" method="post"
                                              action="{{ route('user.updatePassword') }}">
                                            @csrf
                                            <div class="form--group col-md-12">
                                                <label class="form-label"
                                                       for="current_password">@lang('Current Password')</label>
                                                <input id="current_password" type="password"
                                                       class="form-control form--control style--two"
                                                       name="current_password" autocomplete="off">
                                                @if($errors->has('current_password'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('current_password')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-12">
                                                <label class="form-label"
                                                       for="password">@lang('Current Password')</label>
                                                <input id="password" type="password"
                                                       class="form-control form--control style--two"
                                                       name="password" autocomplete="off">
                                                @if($errors->has('password'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('password')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group col-md-12">
                                                <label class="form-label"
                                                       for="password_confirmation">@lang('Confirm Password')</label>
                                                <input id="password_confirmation" type="password"
                                                       class="form-control form--control style--two"
                                                       name="password_confirmation" autocomplete="off">
                                                @if($errors->has('password_confirmation'))
                                                    <div
                                                        class="error text-danger">@lang($errors->first('password_confirmation')) </div>
                                                @endif
                                            </div>

                                            <div class="form--group w-100 col-md-6 mb-0">
                                                <button type="submit"
                                                        class="btn btn--base w-100 mt-3">@lang('Update Password')</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane show fade {{ $errors->has('identity') ? 'show active' : '' }}"
                                         id="identity">
                                        @if(in_array($user->identity_verify,[0,3])  )
                                            @if($user->identity_verify == 3)
                                                <div class="alert alert-danger" role="alert">
                                                    @lang('You previous request has been rejected')
                                                </div>
                                            @endif
                                            <form method="post" action="{{route('user.verificationSubmit')}}" class="profile-edit-form row gy-3"
                                                  enctype="multipart/form-data">
                                                @csrf


                                                <div class="form-group mt-4">
                                                    <label class="form-label" for="identity_type">@lang('Identity Type')</label>
                                                    <select name="identity_type" id="identity_type"
                                                            class="form-control form--control style--two">
                                                        <option value="" selected disabled>@lang('Select Type')</option>
                                                        @foreach($identityFormList as $sForm)
                                                            <option
                                                                value="{{$sForm->slug}}" {{ old('identity_type', @$identity_type) == $sForm->slug ? 'selected' : '' }}>@lang($sForm->name)</option>
                                                        @endforeach
                                                    </select>
                                                    @error('identity_type')
                                                    <div class="error text-danger">@lang($message) </div>
                                                    @enderror
                                                </div>
                                                @if(isset($identityForm))
                                                    @foreach($identityForm->services_form as $k => $v)
                                                        @if($v->type == "text")
                                                            <div class="form-group">
                                                                <label
                                                                    for="{{$k}}">{{trans($v->field_level)}} @if($v->validation == 'required')
                                                                        <span class="text-danger">*</span>  @endif </label>
                                                                <input type="text" name="{{$k}}"
                                                                       class="form-control form--control style--two"
                                                                       value="{{old($k)}}" id="{{$k}}"
                                                                       @if($v->validation == 'required') required @endif>

                                                                @if($errors->has($k))
                                                                    <div class="error text-danger">@lang($errors->first($k)) </div>
                                                                @endif


                                                            </div>
                                                        @elseif($v->type == "textarea")
                                                            <div class="form-group">
                                                                <label
                                                                    for="{{$k}}">{{trans($v->field_level)}} @if($v->validation == 'required')
                                                                        <span
                                                                            class="text-danger">*</span>  @endif </label>
                                                                <textarea name="{{$k}}" id="{{$k}}"
                                                                          class="form-control form--control style--two" rows="5"
                                                                          placeholder="{{trans('Type Here')}}"
                                                                          @if($v->validation == 'required')@endif>{{old($k)}}</textarea>
                                                                @error($k)
                                                                <div class="error text-danger">
                                                                    {{trans($message)}}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                        @elseif($v->type == "file")
                                                            <div class="form-group">
                                                                <label>{{trans($v->field_level)}} @if($v->validation == 'required')
                                                                        <span class="text-danger">*</span>  @endif </label>

                                                                <br>
                                                                <div class="fileinput fileinput-new "
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail "
                                                                         data-trigger="fileinput">
                                                                        <img class="w-150px "
                                                                             src="{{ getFile(config('location.default')) }}"
                                                                             alt="...">
                                                                    </div>
                                                                    <div
                                                                        class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                                    <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new "> @lang('Select') {{$v->field_level}}</span>
                                                                        <span
                                                                            class="fileinput-exists"> @lang('Change')</span>
                                                                        <input type="file" name="{{$k}}"
                                                                               value="{{ old($k) }}" accept="image/*"
                                                                               @if($v->validation == "required")@endif>
                                                                    </span>
                                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                                           data-dismiss="fileinput"> @lang('Remove')</a>
                                                                    </div>

                                                                </div>

                                                                @error($k)
                                                                <div class="error text-danger">
                                                                    {{trans($message)}}
                                                                </div>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                    @endforeach

                                                    <div class="form-group">
                                                        <button type="submit"
                                                                class=" btn btn--base w-100 mt-3">@lang('Submit')</button>
                                                    </div>
                                                @endif
                                            </form>
                                        @elseif($user->identity_verify == 1)
                                            <div class="alert alert-warning" role="alert">
                                                @lang('Your KYC submission has been pending')
                                            </div>
                                        @elseif($user->identity_verify == 2)
                                            <div class="alert alert-success" role="alert">
                                                @lang('Your KYC already verified')
                                            </div>
                                        @endif
                                    </div>

                                    <div class="tab-pane  fade {{ $errors->has('addressVerification') ? 'show active' : '' }}"
                                         id="addressVerification">
                                        @if(in_array($user->address_verify,[0,3])  )
                                            @if($user->address_verify == 3)
                                                <div class="alert alert-danger" role="alert">
                                                    @lang('You previous request has been rejected')
                                                </div>
                                            @endif
                                            <form method="post" action="{{route('user.addressVerification')}}" class="profile-edit-form row gy-3"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="form-label">{{trans('Address Proof')}} <span class="text-danger">*</span>  </label>

                                                    <br>
                                                    <div class="fileinput fileinput-new "
                                                         data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail "
                                                             data-trigger="fileinput">
                                                            <img class="w-150px "
                                                                 src="{{ getFile(config('location.default')) }}"
                                                                 alt="...">
                                                        </div>
                                                        <div
                                                            class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

                                                        <div class="img-input-div">
                                                                    <span class="btn btn-success btn-file">
                                                                        <span
                                                                            class="fileinput-new "> @lang('Select Image') </span>
                                                                        <span
                                                                            class="fileinput-exists"> @lang('Change')</span>
                                                                        <input type="file" name="addressProof"
                                                                               value="{{ old('addressProof')}}" accept="image/*">
                                                                    </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput"> @lang('Remove')</a>
                                                        </div>

                                                    </div>

                                                    @error('addressProof')
                                                    <div class="error text-danger">
                                                        {{trans($message)}}
                                                    </div>
                                                    @enderror
                                                </div>


                                                <div class="form-group">
                                                    <button type="submit"
                                                            class=" btn btn--base w-100 mt-3">@lang('Submit')</button>
                                                </div>

                                            </form>
                                        @elseif($user->address_verify == 1)
                                            <div class="alert alert-warning" role="alert">
                                                @lang('Your KYC submission has been pending')
                                            </div>
                                        @elseif($user->address_verify == 2)
                                            <div class="alert alert-success" role="alert">
                                                @lang('Your KYC already verified')
                                            </div>
                                        @endif
                                    </div>
                                </div>


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
    <link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap-fileinput.css')}}">
@endpush

@push('extra-js')
    <script src="{{asset($themeTrue.'js/bootstrap-fileinput.js')}}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).on('change', '#user-up', function () {
            var _this = $(this);
            var newimage = new FileReader();
            newimage.readAsDataURL(this.files[0]);
            newimage.onload = function (e) {
                $('#image_preview_container').attr('src', e.target.result);
            }
        });


        $(document).on('click', '.clickShowPassword', function () {
            var passInput = $(this).closest('.input-group').find('input');
            if (passInput.attr('type') === 'password') {
                $(this).children().addClass('fa-eye');
                $(this).children().removeClass('fa-eye-slash');
                passInput.attr('type', 'text');
            } else {
                $(this).children().addClass('fa-eye-slash');
                $(this).children().removeClass('fa-eye');
                passInput.attr('type', 'password');
            }
        })


        $(document).on('change', "#identity_type",function (){
            let value = $(this).find('option:selected').val();
                    window.location.href = "{{route('user.profile')}}/?identity_type=" + value
        });

    </script>
@endpush
