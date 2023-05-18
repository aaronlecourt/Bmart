<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="https://kit.fontawesome.com/e471b1e913.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    {{-- popper.min.js --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{asset('css/styles.css')}}">
        <link rel="stylesheet" href="{{asset('css/vendor.css')}}">
        <link rel="stylesheet" href="{{asset('css/homestyle.css')}}">
        <link rel="stylesheet" href="{{asset('css/form.css')}}">
        <link rel="stylesheet" href="{{asset('css/mart.css')}}">

    <style>
        .navlast .nav-items{
            top: 29%;
        }
        .navlast .cancel-icon.show{
            float: left;
        }
    </style>
</head>
<body style="font-family:'Nunito', sans-serif;">
    @section('title', 'Welcome to BerryMart')
    @section('header')
     <nav>
        <div class="navtop d-flex">
            <ul>
                <li><a href="">About Us</a></li>
                <li><a href="">Order Tracking</a></li>
                <li><a href="">Contact Us</a></li>
            </ul>
            <ul>
                @if(!Auth::check())
                    @if (Route::has('login'))
                    <li><a href="/login">Login</a></li>
                    @endif

                    @if (Route::has('register'))
                    <li><a href="/register">Register</a></li>
                    @endif
                @endif
                
                @guest
                
                @else
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                @endguest
            </ul>
        </div>
     </nav>
     <nav>
        <div class="navmid">
            <div class="flex-left">
                <div class="logo">
                    <img src="{{asset('images/logo.png')}}">
                </div>
            
                <div class="searchbar">
                    <form action="{{ route('vendor.home') }}" method="GET">
                        <input type="search" name="search" class="search-data" placeholder="Product Search...">
                        <button type="submit" class="fas fa-search"></button>
                    </form>
                </div>
                {{-- <div class="search-icon">
                    <span class="fas fa-search"></span>
                </div> --}}
            </div>

        </div>
     </nav>
     <nav>
        <div class="navlast align-items-center d-flex">
            <div class="cancel-icon">
                <span class="fas fa-times"></span>
            </div>
            <div class="menu-icon">
                <span class="fas fa-bars"></span>
            </div>
            
            <div class="nav-items">
                {{-- <li><a href="" disabled><i class="fa-solid fa-user"></i>&nbsp </a></li> --}}
                <li><a href="/vendor/profile">My Profile</a></li>
                <li><a href="/vendor/products">Products</a></li>
                <li><a href="/vendor/orders">Orders</a></li>
                <li><a href="/vendor/categories">Categories</a></li>
                <li><a href="/">Sales Report</a></li>
            </div>
        </div>
     </nav>
    @show
        <main class="container-fluid m-0 p-0">
            @yield('content')
        </main>
    </div>
    <footer>
        <div class="nav-foot">
            <div class="container-fluid row foot-top">
                <div class="col-lg-2 col-md-4 col-sm-12 foot-top-content d-flex">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-rocket.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h5>Free Shipping</h5>
                        <p>For all orders over P1000</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 foot-top-content d-flex">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-reload.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h5>1 & 1 Returns</h5>
                        <p>Cancellation after 1 Day</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12 foot-top-content d-flex">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-protect.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h5>100% Secure Payment</h5>
                        <p>Guarantee secure payments</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 foot-top-content d-flex">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-support.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h5>24/7 Dedicated Support</h5>
                        <p>Anywhere & Anytime</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12 foot-top-content d-flex">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-tag.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h5>Daily Offers</h5>
                        <p>Discount up to 70% OFF</p>
                    </div>
                </div>
            </div>
    
            <div class="container-fluid footer-widgets">
                <div class="row border-top">
                    <div class="col-xl-3 col-md-12 widg">
                        <strong>Berrymart - Your Online Foods & Grocery</strong>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus 
                            odit maiores nobis ipsum ex atque minus.</p>
                        <div class="contact-widg">
                            <div>
                                <span class="fa-solid fa-phone"></span>
                                <span>Hotline 24/7</span>
                                <h4>&nbsp&nbsp&nbsp&nbsp8 800 123 45-67</h4>                            
                            </div>
                            <div>
                                <span class="fa-solid fa-house"></span>
                                <span>123 Baguio City, Benguet, 2600</span>
                            </div>
                            <div>
                                <span class="fa-solid fa-envelope"></span>
                                <span>support@berrymart.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 widg">
                        <strong>Useful Links</strong>
                        <ul>
                            <li><a href="/home">Terms of Use</a></li>
                            <li><a href="/home">Terms and Conditions</a></li>
                            <li><a href="/home">Refund Policy</a></li>
                            <li><a href="/home">FAQs</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-2 col-md-4 widg">
                        <strong>Help Center</strong>
                        <ul>
                            <li><a href="/home">About Us</a></li>
                            <li><a href="/home">Affiliate</a></li>
                            <li><a href="/home">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-2 col-md-4 widg">
                        <strong>Business</strong>
                        <ul>
                            <li><a href="/home">My Account</a></li>
                            <li><a href="/home">Vendor Registration</a></li>
                            <li><a href="/home">Shop</a></li>
                            <li><a href="/home">Cart</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-3 col-md-12 widg">
                        <strong>Newsletter</strong>
                        <p>Register now to get updated on promotions and coupons. Don't worry! We don't spam.</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email here" aria-label="email" aria-describedby="sub">
                            <button class="btn btn-warning subscribeBtn" type="submit">Subscribe</button>
                        </div>
                    </div>
                    <div class="crft">
                        <p>© 2023 BerryMart. All Rights Reserved. Lecourt, Aaron Zayke & Galutera, Caesar Klidge IAB WEBSYS2</p>
                    </div>
                </div>
                
            </div>
        </div>
    </footer>
</body>
<script>
    const menuBtn = document.querySelector(".menu-icon span");
    // const searchBtn = document.querySelector(".search-icon");
    const cancelBtn = document.querySelector(".cancel-icon");
    const items = document.querySelector(".nav-items");
    const form = document.querySelector("form");
    menuBtn.onclick = ()=>{
      items.classList.add("active");
      menuBtn.classList.add("hide");
      cancelBtn.classList.add("show");
    }
    cancelBtn.onclick = ()=>{
      items.classList.remove("active");
      menuBtn.classList.remove("hide");
      cancelBtn.classList.remove("show");
      form.classList.remove("active"); 
    }
    // searchBtn.onclick = ()=>{
    //   form.classList.add("active");
    //   searchBtn.classList.add("hide");
    //   cancelBtn.classList.add("show");
    // }
  </script>
</html>
