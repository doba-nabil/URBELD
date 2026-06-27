@extends('layouts.website')
@section('body_class', 'sup-page')

@section('title', $page->title . ' - ' . \App\Models\Setting::getValue('site_name'))

@section('content')
    <!-- Header Start (Static simple header for inner pages) -->
    <div class="services-header-section without-search">
        <div class="container p-md-5 p-4 mb-md-5">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="services-header-title text-center text-white wow fadeInUp" data-wow-delay="0.1s">
                        {{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Page Content -->
    <div class="page-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-12" style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

      <x-website.services-section />

    <x-website.success-partners :title="\App\Models\Setting::getValue(
        'home_partners_title',
        app()->getLocale(),
        'نفخر بالشراكة مع عملاء من الطراز الأول',
    )" />
@endsection
