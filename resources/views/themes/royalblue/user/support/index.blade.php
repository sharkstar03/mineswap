@extends($theme.'layouts.user')
@section('title',trans($page_title))

@section('content')


    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="method__card">
                        <div class="d-flex justify-content-between">
                            <h4 >@lang($page_title)</h4>
                            <a href="{{route('user.ticket.create')}}" class="cmn--btn cmn--btn-sm"> <i
                                    class="fa fa-plus-circle"></i> @lang('Create Ticket')</a>
                        </div>


                        <table class="table transection__table mt-5">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Subject')</th>
                                <th scope="col">@lang('Status')</th>
                                <th scope="col">@lang('Last Reply')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $key => $ticket)
                                <tr>
                                    <td data-label="@lang('Subject')">
                                                    <span
                                                        class="font-weight-bold"> [{{ trans('Ticket#').$ticket->ticket }}
                                                        ] {{ $ticket->subject }} </span>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($ticket->status == 0)
                                            <span class="badge rounded-pill bg-success">@lang('Open')</span>
                                        @elseif($ticket->status == 1)
                                            <span class="badge rounded-pill bg-primary">@lang('Answered')</span>
                                        @elseif($ticket->status == 2)
                                            <span class="badge rounded-pill bg-warning">@lang('Replied')</span>
                                        @elseif($ticket->status == 3)
                                            <span class="badge rounded-pill bg-dark">@lang('Closed')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Last Reply')">
                                        {{diffForHumans($ticket->last_reply) }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('user.ticket.view', $ticket->ticket) }}"
                                           class="btn btn-sm btn-primary py-1 px-2">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="100%">{{trans('No Data Found!')}}</td>
                                </tr>

                            @endforelse
                            </tbody>
                        </table>
                        {{ $tickets->appends($_GET)->links($theme.'partials.pagination') }}
                    </div>
                </div>
            </div>



        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>

@endsection
