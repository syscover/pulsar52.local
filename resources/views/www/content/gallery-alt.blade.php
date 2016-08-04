@extends('www.layouts.default')

@section('title', 'GALLERY')

@section('head')

    <link rel="stylesheet" href="{{ asset('vendor/swiper/css/swiper.min.css') }}">
    <script src="{{ asset('vendor/swiper/js/swiper.jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('vendor/magnific-popup/css/magnific-popup.css') }}">
    <script src="{{ asset('vendor/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>

    <link href='https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two' rel='stylesheet' type='text/css'>
    <script src="{{ asset('vendor/isotope/js/isotope.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('a.sys_gallery__element__wrapper').magnificPopup({
                type: 'image',
                gallery:{enabled:true},
                // other options
                removalDelay: 300,
                mainClass: 'mfp-with-zoom', // this class is for CSS animation below
                zoom: {
                    enabled: true, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                    // The "opener" function should return the element from which popup will be zoomed in
                    // and to which popup will be scaled down
                    // By defailt it looks for an image tag:
                    opener: function (openerElement) {
                        // openerElement is the element on which popup was initialized, in this case its <a> tag
                        // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });

        });

        $(window).load(function(){
            var grid = $('.sys_gallery').isotope({
                // set itemSelector so .grid-sizer is not used in layout
                itemSelector: '.sys_gallery__element',
                percentPosition: true,
                masonry: {
                    // use element for option
                    columnWidth: '.sys_gallery__element'
                }
            });

            $('.sys_gallery__filters__list__item').on('click', function(){
                if (!$(this).hasClass('sys_gallery__filters__list__item--active')) {
                    $('.sys_gallery__filters__list__item').removeClass('sys_gallery__filters__list__item--active');
                    $(this).addClass('sys_gallery__filters__list__item--active');
                }
                var filter_class = $(this).data('filter');
                grid.isotope({ filter: filter_class });
            });
        })
    </script>

@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="sys_page-header sys_inner-container clearfix">
                <div class="sys_bg-img-wrapper">
                    <div class="sys_bg-img-wrapper__bg-overlay sys_bg-img-wrapper__bg-overlay--white"></div>
                    <img src="https://lh3.ggpht.com/20F-fZ3iDGRMzXxRxsQIouFsHKhuP45QMCT2vJXOMTRe-WZ0cvR1SQZruCUZW_36BXk=h900" class="sys_bg-img-wrapper__bg-img">
                </div>
                <h3 class="sys_page-header__breadcrumbs">
                    <a href="{{ route('home') }}">Home</a>
                    <span class="sys_page-header__breadcrumbs__separator">/</span>
                    <span>Gallery</span>
                </h3>
                <h1 class="sys_page-header__title">Gallery</h1>
                <h2 class="sys_page-header__subtitle">
                    <span class="sys_page-header__subtitle__icon">
                        <i class="urban urban-media"></i>
                    </span>
                    <span class="sys_page-header__subtitle__main">
                        Lorem ipsum dolor sit amet,
                        <br class="hidden-xs">
                        consectetur adipiscing elit.
                    </span>
                    <span class="sys_page-header__subtitle__sub white-text">
                        Lorem ipsum dolor sit amet,
                        <br class="hidden-xs">
                        consectetur adipiscing elit.
                    </span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="sys_content-wrapper sys_inner-container clearfix">
                <div class="row">
                    <div class="sys_gallery sys_gallery--masonry clearfix">
                        <div class="col-md-12 sys_gallery__filters">
                            <ul class="sys_gallery__filters__list">
                                <li class="sys_gallery__filters__list__item sys_gallery__filters__list__item--active" data-filter="*">All</li>
                                <li class="sys_gallery__filters__list__item" data-filter=".abc">Filter 'abc'</li>
                                <li class="sys_gallery__filters__list__item" data-filter=".qwe">Filter 'qwe'</li>
                                <li class="sys_gallery__filters__list__item" data-filter=".asd">Filter 'asd'</li>
                            </ul>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element abc qwe">
                            <a class="sys_gallery__element__wrapper"  href="http://placehold.it/350x150">
                                <img src="http://placehold.it/350x150" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder abc qwe</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element asd">
                            <a class="sys_gallery__element__wrapper" href="http://placehold.it/500x750">
                                <img src="http://placehold.it/500x750" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder asd</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element asd qwe">
                            <a class="sys_gallery__element__wrapper" href="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg">
                                <img src="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder asd qwe</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element abc asd">
                            <a class="sys_gallery__element__wrapper" href="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg">
                                <img src="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder abc asd</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element qwe">
                            <a class="sys_gallery__element__wrapper" href="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg">
                                <img src="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder qwe</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 sys_gallery__element abc">
                            <a class="sys_gallery__element__wrapper" href="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg">
                                <img src="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg" class="sys_gallery__element__img">
                                <span class="sys_gallery__element__caption">Placeholder abc</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop