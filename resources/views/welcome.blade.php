@extends('layouts.app')

@section('content')
<style type="text/css">
    .price{
        font-size: 14px;
    padding: 0 7px;
    display: inline-block;
    border: none;
    line-height: inherit;
    background: #fff;
    font-weight: 400;
    color: #797874;
    border-radius: 4px;
    }
</style>
    <!-- Hero section Done (just needs new images for the slider)-->
    <section class="hero-section">
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="{{ asset('images/bg1.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-7 text-white">
                            <span>Make A Change</span>
                            <h2>our audiance</h2>
                            <p>Are you trying to find a suitable clothes matches your taste, we can help you with than just click the vote button to see our designs gallary and vote for your favourite ones to put it under radar for fashion copmanies to make a production line for it. </p>
                            @if (Auth::guest())
                                <a href="{{ route('login')}}" class="site-btn sb-white">VOTE NOW</a>
                            @elseif($role == 'user')
                                <a href="{{ route('designs.index')}}" class="site-btn sb-white">VOTE NOW</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="hs-item set-bg" data-setbg="{{ asset('images/bg3.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-7 text-white">
                            <span>Choose A Design</span>
                            <h2>Our brands</h2>
                            <p>we can help your business grow faster by get the feeling of the customer needs and taste in the clothes industry, as you can what's a trend and buy it from our designers list. </p>
                            @guest
                                <a href="{{ route('company.registeration') }}" class="site-btn sb-white">REGISTER NOW</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
            <div class="hs-item set-bg" data-setbg="{{ asset('images/bg.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-7 text-white">
                            <span>Chase Your Dream</span>
                            <h2>our designers</h2>
                            <p>We can help your ideas to see the light by turning them into a real products as the companies can see what is the trending designs and buy it </p>
                            @guest
                                <a href="{{ route('designer.registeration') }}" class="site-btn sb-white">REGISTER NOW</a>
                            @endguest
                        </div>
                    </div>
                    {{-- <div class="offer-card text-white">
                        <span>from</span>
                        <h2>$29</h2>
                        <p>SHOP NOW</p>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="container">
            <div class="slide-num-holder" id="snh-1"></div>
        </div>
    </section>
    <!-- Hero section end -->



    <!-- Features section Done --> 
    <section class="features-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 p-0 feature">
                    <div class="feature-inner">
                        <div class="feature-icon">
                            <img src="{{ asset('images/icons/1.png') }}" alt="#">
                        </div>
                        <h2>Fast Secure Payments</h2>
                    </div>
                </div>
                <div class="col-md-4 p-0 feature">
                    <div class="feature-inner">
                        <div class="feature-icon">
                            <img src="{{ asset('images/icons/2.png') }}" alt="#">
                        </div>
                        <h2>Premium Designs</h2>
                    </div>
                </div>
                <div class="col-md-4 p-0 feature">
                    <div class="feature-inner">
                        <div class="feature-icon">
                            <img src="{{ asset('images/icons/3.png') }}" alt="#">
                        </div>
                        <h2>One Click delivery </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features section end -->


    <!-- letest design section (needs to make images dynamic)-->
    <section class="top-letest-product-section">
        <div class="container">
            <div class="section-title">
                <h2>LATEST DESIGNS</h2>
            </div>
            <div class="product-slider owl-carousel">
                @foreach ($latestDesigns as $design)
                    <div class="product-item">
                        <div class="pi-pic">
                            @if($design->state != "sketch")
                            <div class="tag-sale">Sold</div>
                            @endif
                            <a href="{{route('designs.show', ['design' => $design->id])}}"> 
                                <img width="200px" height="350px" style="border-radius: 5%" src="{{asset ('storage/'.$design->images()->first()->image) }}" alt="">
                            </a>
                            <div class="pi-links"> 
                                @if ($role == 'company' && ($design->state == "sketch"))
                                    <a href="javascript:void(0)" data-id="{{ $design->id }}" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
                                @endif
                                @if((Auth::user())&&(Auth::user()->role == "user") && ($design->state == "sketch") )
                                    <a href="{{route('designs.show', ['design' => $design->id])}}" class="wishlist-btn"><i class="flaticon-heart"></i></a>
                                @endif
                            </div>
                        </div>
                        <div class="pi-text">
                            
                            <a href="{{route('designs.show', ['design' => $design->id])}}"> <p style="display: inline;">
                                {{ $design->title }}</p></a>
                            @if ($role == 'company')
                                <span  class="price" style="float: right;">${{ $design->price }}</span> 
                            @endif
                            <div class="designer-name">
                                <i style="font-style: italic;">By</i> 
                                <a href="{{route('designer.show', ['designer' => $design->designer->id])}}" class="designer">{{$design->designer->name}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
               
            </div>
    </section>

    <section class="product-filter-section" style="    background-color: #f5f4f2;">
        <div class="container ">
            <div class="section-title " style="    padding-top: 20px;">
                <h2 class="headers">BROWSE TOP LIKED DESIGNS</h2>
            </div>
            <div class="row">
                @foreach ($topDesigns as $design)
                    <div class="col-lg-3 col-sm-6">
                        <div class="product-item">
                            <div class="pi-pic">
                                @if($design->state != "sketch")
                                <div class="tag-sale">Sold</div>
                                @endif
                                {{-- <a href="{{route('designs.show', ['design' => $design->id])}}">
                                    <img src="{{ asset('images/product/9.jpg') }}" alt="">
                                </a> --}}
                                
                                <a href="{{route('designs.show', ['design' => $design->id])}}">
                                        <img width="338" height="241" style="border-radius: 5%" src="{{asset ('storage/'.$design->images()->first()->image) }}" alt="">
                                </a>
                               
                                <div class="pi-links">
                                    @if ($role == 'company' && ($design->state == "sketch"))
                                        <a href="javascript:void(0)" data-id="{{ $design->id }}" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
                                    @endif                                    
                                    @if((Auth::user())&&(Auth::user()->role == "user") && ($design->state == "sketch") )
										<a href="{{route('designs.show', ['design' => $design->id])}}" class="wishlist-btn"><i class="flaticon-heart"></i></a>
									@endif                               
                                </div>
                            </div>
                            <div class="pi-text">
                                @if ($role == 'company')
                                    <span  class="price" style="float: right;">${{ $design->price }}</span>
                                @endif
                                <a href="{{route('designs.show', ['design' => $design->id])}}"> <p>{{ $design->title}}</p></a>
                                <div class="designer-name">
                                    <i style="font-style: italic;">By</i> 
                                    <a href="{{route('designer.show', ['designer' => $design->designer->id])}}" class="designer">{{$design->designer->name}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center pt-5">
                <a href="{{ route('designs.index')}}" class="site-btn sb-line sb-dark">SEE MORE</a>
            </div>
        </div>
    </section>
    <!-- Product filter section end -->

    <!-- latest Brands section (needs to make images dynamic)-->
    <section class="top-letest-product-section">
        <div class="container">
            <div class="section-title">
                <h2>OUR BRANDS</h2>
            </div>
            <div class="product-slider owl-carousel">
                @foreach ($companies as $company)
                    <div class="product-item">
                        <div class="pi-pic">
                            <a href="{{route('company.show', $company)}}" >
                                {{-- <img src="{{ asset('images/product/1.jpg') }}" alt=""> --}}
                                <img src="{{asset('storage/'.$company->image)}}" alt="" style="height:200px;width:200px;border-radius: 50%">
                            </a>
                            <div class="pi-links">
                                <a href="{{route('company.show', $company)}}" class="btn btn-info">KNOW MORE</a>
                            </div>
                        </div>
                        <div class="pi-text">
                            <p>{{ $company->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
    </section>
    <!-- letest design section end -->

    <!-- Banner section (votes link) -->
    <section class="banner-section">
        <div class="container">
            <div class="banner set-bg" data-setbg="{{ asset('images/banner-bg.jpg') }}">
                <div class="tag-new">NEW</div>
                <span>New DESIGNS</span>
                <h2>EVERY DAY</h2>
                @auth
                    <a href="{{route('designs.index')}}" class="site-btn">VOTE NOW</a>
                @else
                    <a href="{{ route('createAccount') }}" class="site-btn">Register NOW</a>
                @endauth
            </div>
        </div>
    </section>
    <!-- Banner section end  -->
@endsection
