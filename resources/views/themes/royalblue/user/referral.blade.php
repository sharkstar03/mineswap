@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')

    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="method__card">
                <div class="d-flex justify-content-end">
                    <a href="{{route('user.referral.bonus')}}" class="cmn--btn cmn--btn-sm"><i class="la la-money-bill"></i> @lang('Referral Bonus')</a>
                </div>

                <div class="form-group form-block br-4">
                    <label>@lang('Referral Link')</label>
                    <div class="input-group mb-50">
                        <input type="text"
                               value="{{route('register.sponsor',[Auth::user()->username])}}"
                               class="form--control form-control style--two"
                               id="sponsorURL"
                               readonly>
                        <div class="input-group-append">
                                <span class="input-group-text form--control copytext" id="copyBoard" onclick="copyFunction()">
                                        <i class="fa fa-copy"></i>
                                </span>
                        </div>
                    </div>
                </div>


                @if(0 < count($referrals))
                    <div class="row">
                        <div class="col-md-12">


                            <div class="d-flex justify-content-start" id="ref-label">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach($referrals as $key => $referral)
                                        <a class=" nav-link @if($key == '1')   active  @endif " id="v-pills-{{$key}}-tab" href="javascript:void(0)" data-bs-toggle="pill" data-bs-target="#v-pills-{{$key}}"  role="tab" aria-controls="v-pills-{{$key}}" aria-selected="true">@lang('Level') {{$key}}</a>
                                    @endforeach
                                </div>
                                <div class="tab-content w-90" id="v-pills-tabContent">
                                    @foreach($referrals as $key => $referral)
                                        <div class="tab-pane fade @if($key == '1') show active  @endif " id="v-pills-{{$key}}" role="tabpanel" aria-labelledby="v-pills-{{$key}}-tab">
                                            @if( 0 < count($referral))
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">@lang('Username')</th>
                                                            <th scope="col">@lang('Email')</th>
                                                            <th scope="col">@lang('Phone Number')</th>
                                                            <th scope="col">@lang('Joined At')</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($referral as $user)
                                                            <tr>

                                                                <td data-label="@lang('Username')">
                                                                    <a href="{{route('admin.user-edit',$user->id)}}" target="_blank">
                                                                        @lang($user->username)
                                                                    </a>
                                                                </td>
                                                                <td data-label="@lang('Email')">{{$user->email}}</td>
                                                                <td data-label="@lang('Phone Number')">
                                                                    {{$user->mobile}}
                                                                </td>
                                                                <td data-label="@lang('Joined At')">
                                                                    {{dateTime($user->created_at)}}
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            </div>






        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->

@endsection
@push('script')

    <script>
        "use strict";

        function copyFunction() {
            var copyText = document.getElementById("sponsorURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success(`Copied: ${copyText.value}`);
        }
    </script>

@endpush
