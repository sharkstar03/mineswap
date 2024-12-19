@extends($theme.'layouts.app')
@section('title',trans('Blog Details'))

@section('content')

    <!-- Blog Section Starts Here -->
    <section class="blog-section padding-top padding-bottom">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8">
                    <div class="post__details">
                        <div class="post__thumb"><img src="{{$singleItem['image']}}" alt="blog"></div>
                        <ul class="post-meta d-flex flex-wrap mt-4 mb-3">
                            <li>
                                <a href="javascript:void(0)" class="post-author"><i class="las la-user"></i>{{trans('Posted By Admin')}}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="post-meta-date"><i class="las la-clock"></i>{{$singleItem['date']}}</a>
                            </li>
                        </ul>
                        <div class="post__body">
                            <h3 class="title mb-4">{{$singleItem['title']}}</h3>

                            <div class="entry__content">
                                @lang($singleItem['description'])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="sidebar blog__sidebar">
                        @if(isset($popularContentDetails['blog']))
                            <div class="sidebar__item">
                                <h5 class="title">{{trans('Recent Post')}}</h5>
                                <div class="recent__posts">
                                    @foreach($popularContentDetails['blog']->sortDesc() as $data)
                                    <div class="post__item d-flex p-3">
                                        <div class="post__item-thumb">
                                            <img src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}" alt="{{@$data->description->title}}" class="radius-5">
                                        </div>
                                        <div class="post__item-content p-0 ps-3">
                                            <h6 class="title mb-2 mt-0"><a href="{{route('blogDetails',[slug($data->description->title), $data->content_id])}}">{{\Str::limit($data->description->title,40)}} </a></h6>
                                            <div
                                                class="post__meta__wrapper d-flex flex-wrap align-items-center justify-content-between">
                                                <span class="date"><i class="las la-clock"></i> {{dateTime($data->created_at)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section Ends Here -->
@endsection
