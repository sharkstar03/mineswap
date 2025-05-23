@extends($theme.'layouts.app')
@section('title',trans('About Us'))

@section('content')

    @include($theme.'sections.about-us')
    @include($theme.'sections.feature')
    @include($theme.'sections.team')
    @include($theme.'sections.statistics')

    @include($theme.'sections.testimonial')


    @include($theme.'sections.news-letter')

@endsection
