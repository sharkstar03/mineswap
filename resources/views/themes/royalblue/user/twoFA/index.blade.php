@extends($theme.'layouts.user')
@section('title',trans('2 Step Security'))
@section('content')

    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center g-4">
                @if(auth()->user()->two_fa)
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="card method__card text-center br-4">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Two Factor Authenticator')</h5>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <div class="input-group input-group-lg">
                                        <input type="text" class=" form-control form--control style--two" id="referralURL" value="{{$previousCode}}" readonly>
                                        <div class="input-group-append ">
                                            <span class="input-group-text form--control copytext" id="copyBoard"
                                                  onclick="copyFunction()" ><i class="fa fa-copy"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mx-auto my-4 text-center">
                                    <img class="mx-auto" src="{{$previousQR}}">
                                </div>

                                <div class="form-group mx-auto text-center">
                                    <a href="javascript:void(0)" class="cmn--btn cmn--btn-md"
                                       data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable 2FA')</a>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="card method__card text-center br-4">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Two Factor Authenticator')</h5>
                            </div>
                            <div class="card-body">

                                <div class="form-group ">

                                    <div class="input-group input-group-lg">
                                        <input type="text" class=" form-control form--control style--two" id="referralURL" value="{{$secret}}" readonly>
                                        <div class="input-group-append ">
                                            <span class="input-group-text form--control copytext" id="copyBoard"
                                            onclick="copyFunction()" ><i class="fa fa-copy"></i></span>
                                        </div>
                                    </div>


                                </div>
                                <div class="form-group mx-auto my-4 text-center">
                                    <img class="mx-auto" src="{{$qrCodeUrl}}">
                                </div>

                                <div class="form-group mx-auto  text-center">
                                    <a href="javascript:void(0)" class="cmn--btn cmn--btn-md"
                                       data-bs-toggle="modal" data-bs-target="#enableModal">@lang('Enable 2FA')</a>
                                </div>
                            </div>

                        </div>
                    </div>

                @endif


                <div class="col-lg-6 col-md-6 mb-3">
                    <div class="card method__card text-center">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Google Authenticator')</h5>
                        </div>
                        <div class="card-body">

                            <h6 class="text-uppercase my-3">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h6>

                            <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                            <a class="cmn--btn mt-3"
                               href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                               target="_blank">@lang('DOWNLOAD APP')</a>

                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>





    <!--Enable Modal -->
    <div id="enableModal" class="modal fade custom--modal " role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content gradient-bg">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Verify Your OTP')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" >&times;</button>

                </div>
                <form action="{{route('user.twoStepEnable')}}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control form--control style--two" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger  btn--sm" data-bs-dismiss="modal" >@lang('Close')</button>
                        <button type="submit" class="btn btn-success btn--sm">@lang('Verify')</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!--Disable Modal -->
    <div id="disableModal" class="modal fade custom--modal " role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content form-block gradient-bg">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Verify Your OTP to Disable')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" >&times;</button>
                </div>
                <form action="{{route('user.twoStepDisable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control form--control style--two" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger  btn--sm" data-bs-dismiss="modal" >@lang('Close')</button>
                        <button type="submit" class="btn btn-success btn--sm">@lang('Verify')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection



@push('script')
    <script>
        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success(`Copied: ${copyText.value}`);
        }

        @foreach($errors->all() as $key => $error)
        Notiflix.Notify.Failure("{{$error}}");
        @endforeach
    </script>
@endpush

