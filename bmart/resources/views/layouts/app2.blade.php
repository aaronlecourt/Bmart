<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{asset('css/styles.css')}}">
        <link rel="stylesheet" href="{{asset('css/homestyle.css')}}">
        <link rel="stylesheet" href="{{asset('css/form.css')}}">
        <link rel="stylesheet" href="{{asset('css/mart.css')}}">
</head>
<body style="font-family:'Nunito', sans-serif;">
    @section('title', 'Welcome to BerryMart')
    @section('header')
    {{-- header-top --}}
    <div class="nav header-top" id="header-top">
        <ul>
            <li><a class="nav-link" href="#">About Us 2.0</a></li>
            <li><a class="nav-link" href="#">Wishlist</a></li>
            <li><a class="nav-link" href="#">Order Tracking</a></li>
        </ul>
        <ul>
            @if(!Auth::check())
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
            @endif
        </ul>
    </div>
    {{-- header-mid --}}
    <div class="nav header-middle">
        <div class="header-item header__left">
            <div class="logo">
                <a href="/home">
                <img src="{{asset('images/logo.png')}}" alt="Logo" style="max-height: 50px;"></a>
            </div>
        </div>
    </div>
    </div>
    @show
    <div class="container-fluid d-flex row p-0 m-0">
            <div class="col p-0 m-0" style="border-right: 2px solid rgb(215, 215, 215);">
                <div class="bg-light p-4 h-100">
                    <a href="/" class="d-flex">
                        <img src="https://github.com/mdo.png" alt="" width="30" height="30" class="rounded-circle me-2">
                        <h3>Hello {{Auth::user()->name}}!</h3>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                          <a href="/vendor/home" class="nav-link active" aria-current="page">Products</a>
                        </li>
                        <li>
                          <a href="#" class="nav-link link-dark">Profile</a>
                        </li>
                        <li>
                          <a href="#" class="nav-link link-dark">Orders</a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-10 p-0 m-0">
                @yield('content') 
            </div>
    </div>
    </div>
    <footer>
        <div class="nav">
            <div class="foot-top">
                <div class="foot-top-content">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-rocket.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h3>Free Shipping</h3>
                        <p>For all orders over P1000</p>
                    </div>
                </div>
                <div class="foot-top-content">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-reload.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h3>1 & 1 Returns</h3>
                        <p>Cancellation after 1 Day</p>
                    </div>
                </div>
                <div class="foot-top-content">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-protect.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h3>100% Secure Payment</h3>
                        <p>Guarantee secure payments</p>
                    </div>
                </div>
                <div class="foot-top-content">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-support.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h3>24/7 Dedicated Support</h3>
                        <p>Anywhere & Anytime</p>
                    </div>
                </div>
                <div class="foot-top-content">
                    <div class="foot-img">
                        <img src="{{asset('/images/icon-tag.png')}}" alt="">
                    </div>
                    <div class="foot-txt">
                        <h3>Daily Offers</h3>
                        <p>Discount up to 70% OFF</p>
                    </div>
                </div>
            </div>
    
            <div class="container-fluid footer-widgets">
                <div class="row border-top py-5">
                    <div class="col-xl-3 widg">
                        <strong>Berrymart - Your Online Foods & Grocery</strong>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus 
                            odit maiores nobis ipsum ex atque minus.</p>
                        <div class="contact-widg">
                            <div>
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                  </svg></span>
                                <span>Hotline 24/7</span>
                                <h4>&nbsp&nbsp&nbsp&nbsp&nbsp8 800 123 45-67</h4>                            
                            </div>
                            <div>
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/>
                                  </svg></span>
                                <span>123 Baguio City, Benguet, 2600</span>
                            </div>
                            <div>
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                  </svg></span>
                                <span>support@berrymart.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 widg">
                        <strong>Useful Links</strong>
                        <ul>
                            <li><a href="/home">Terms of Use</a></li>
                            <li><a href="/home">Terms and Conditions</a></li>
                            <li><a href="/home">Refund Policy</a></li>
                            <li><a href="/home">FAQs</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-2 widg">
                        <strong>Help Center</strong>
                        <ul>
                            <li><a href="/home">About Us</a></li>
                            <li><a href="/home">Affiliate</a></li>
                            <li><a href="/home">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-2 widg">
                        <strong>Business</strong>
                        <ul>
                            <li><a href="/home">My Account</a></li>
                            <li><a href="/home">Vendor Registration</a></li>
                            <li><a href="/home">Shop</a></li>
                            <li><a href="/home">Cart</a></li>
                        </ul>
                    </div>
                    <div class="col-xl-3 widg">
                        <strong>Newsletter</strong>
                        <p>Register now to get updated on promotions and coupons. Don't worry! We don't spam.</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email here" aria-label="email" aria-describedby="sub">
                            <button class="btn btn-outline-0 subscribeBtn" type="submit">Subscribe</button>
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
</html>
