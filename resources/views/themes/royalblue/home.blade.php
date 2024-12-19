@extends($theme.'layouts.app')
@section('title',trans('Home'))

@section('content')
    @include($theme.'partials.heroBanner')
    @include($theme.'sections.about-us')
    @include($theme.'sections.feature')
    @include($theme.'sections.calculation')

    @include($theme.'sections.why-chose-us')
    @include($theme.'sections.plan')
    @include($theme.'sections.testimonial')
    @include($theme.'sections.faq')

    @include($theme.'sections.statistics')
    @include($theme.'sections.blog')
    @include($theme.'sections.news-letter')
@endsection
