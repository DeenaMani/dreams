@php
$setting = cache('settings');
@endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description"content="{{@$description}}">
  <meta name="author" content="Hashref">
  <title>{{@$title ? @$title." | " : ""}}  {{$setting->company_name}}</title> 
  <link rel="icon" type="image/x-icon" href="{{assets()}}image/setting/{{$setting->fav_icon}}">
  <link rel="stylesheet" href="{{assets()}}css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="{{assets()}}css/owl.carousel.min.css">
  <link rel="stylesheet" href="{{assets()}}css/owl.theme.default.min.css">
  @stack('links')
  <link rel="stylesheet" href="{{assets()}}css/style.css?v=1.4">

  <style type="text/css">
    input.btn-primary {
      float: right;
    }
    lable {
      text-transform: capitalize;
    }
  </style>

</head>
<body>
    <header  id="header-sticky">
    <div class="header-top d-sm-block d-none">
      <div class="container">
        <div class="row">
        <div class="col-md-8 col-sm-10 col-12">
          <ul class=" list-unstyled d-flex mb-0 py-2 justify-content-start">
            <li class="pe-3">
              <a class="" href="mailto:"><i class="fa fa-envelope pe-2"></i> {{$setting->email}}</a>
            </li>
            <li class="border-start px-3">
              <a class="" href="tel:"><i class="fa fa-phone pe-2"></i> {{$setting->phone}}</a>
            </li>
          </ul>
        </div>
        <nav class="col-md-4 col-sm-2 d-md-block d-none">
          <ul class=" list-unstyled d-flex mb-0 py-2 justify-content-end  ">
            
            <li class=" ps-3">
              @if(session('name'))
             
                <a href="{{url('dashboard')}}"><i class="fa fa-user pe-2"></i>Dashboard</a> &nbsp;
              &nbsp;
                <a href="{{url('logout')}}"><i class="fa fa-sign-out pe-2"></i>Logout</a>
              @else
              <a href="{{url('login')}}"><i class="fa fa-user-circle pe-2"></i> Login / Register</a>
              @endif
            </li>
          </ul>
        </nav>
      </div>
      </div>
    </div>
    <div class="main-header">
      <div class="container">
        <nav class="navbar navbar-expand-lg">
          <div class="col-lg-3 col-md-6 col-6">
            <div class="logo">
              <a href="{{url('/')}}">
                <img src="{{assets()}}image/setting/{{$setting->logo}}" alt="{{$setting->company_name}}">
              </a>
            </div>
          </div>

          <div class="col-6 d-lg-none d-block">
            <ul class="navbar-nav header-end justify-content-end flex-direction-row">
              <li class="nav-item d-md-none">
                <a href="{{url('dashboard')}}"><img src="{{assets()}}images/user.png" alt="user"></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{url('cart')}}"><img src="{{assets()}}images/cart.png" alt="cart"><span class="cart-count">{{cartCount()}}</span></a>
              </li>
              <li class="nav-item">
                <a class="navbar-toggler nav-link" data-bs-toggle="collapse" data-bs-target="#header">
                  <img src="{{assets()}}images/menu.png" alt="menu">
                </a>
              </li>
            </ul>
          </div>

          <div class="collapse navbar-collapse col-lg-8 col-md-12" id="header">
            <ul class="navbar-nav header-center justify-content-center">
              <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('/')}}">Home</a>
              </li>
              <li class="nav-item {{ request()->is('courses') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('courses')}}">Courses</a>
              </li>
              <li class="nav-item {{ request()->is('competitive') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('competitive')}}">Competitive Exams</a>
              </li>
              <li class="nav-item {{ request()->is('schedules') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('schedules')}}">Schedules</a>
              </li>
              <li class="nav-item {{ request()->is('study-material') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('study-material')}}">Resources</a>
              </li>
              <li class="nav-item {{ request()->is('contact-us') ? 'active' : '' }}">
                <a class="nav-link" href="{{url('contact-us')}}">Contact</a>
              </li>
            </ul>
          </div>
                    
                    <div class="col-lg-1 d-lg-block d-none">
            <ul class="navbar-nav header-end justify-content-end flex-direction-row">
              <li class="nav-item">
                <a class="nav-link" href="{{url('cart')}}"><img src="{{assets()}}images/cart.png" alt="cart"><span class="cart-count">{{cartCount()}}</span></a>
              </li>
             <!--  <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)"><img src="{{assets()}}images/search.png" alt="search"></a>
              </li> -->
              <li class="nav-item">
                <a class="navbar-toggler nav-link" data-bs-toggle="collapse" data-bs-target="#header">
                  <img src="{{assets()}}images/menu.png" alt="menu">
                </a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>
  
   @yield('main-content')
  <footer>
    <div class="py-4">
      <div class="container">
        <div class=" row footer-right">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{url('/')}}"><img alt="logo" src="{{assets()}}images/logo.png"></a>
                  <div class="text-white address">
                      
                      {!! $setting->address!!}
            </div> 
            </div>


              <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div>
                <h5 class="title pt-3">Quick Links</h5>
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <a href="{{url('about-us')}}">About</a>
                  </li>
                  <li class="mb-2">
                    <a href="{{url('competitive')}}">Competitive Exams</a>
                  </li>
                  <li class="mb-2">
                    <a href="{{url('courses')}}">Courses</a>
                  </li>
                  <li class="mb-2">
                    <a href="{{url('book-session')}}">Book Free Session</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div>
                <h5 class="title pt-3">Legal Terms</h5>
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <a href="{{url('terms-conditions')}}">Terms of Use</a>
                  </li>
                  <li class="mb-2">
                    <a href="{{url('privacy-policy')}}">Privacy policy</a>
                  </li>
                  <li class="mb-2">
                    <a href="{{url('faq')}}">FAQ's</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="touch col-lg-3 col-md-6 col-sm-6 col-12">
              <h5 class="title pt-3">Contact us</h5>
              <ul class="list-unstyled text-white">
                      <li class="mb-2">
                        <div class="mb-1">
                          <i class="fa fa-phone"></i>
                          <!-- <span class="ps-3">Call / Whatsapp :</span> -->
                          <div class="ps-3 d-inline-block"><a href="tel:">{{$setting->phone}}</a></div>
                        </div>
                      </li>
                      <li class="mb-2">
                        <div class="mb-1">
                          <i class="fa-sharp fa-solid fa-envelope"></i>
                          <!-- <span class="ps-3">E Mail : <span ></span></span> -->
                          <div class="ps-3 d-inline-block"><a href="mailto:">{{$setting->email}}</a></div>
                        </div>
                      </li>
                      <li class="mb-2">
                        <div class="mb-1 d-flex align-items-center">
                          <i class="fa-sharp fa-solid fa-clock pe-1"></i>
                          <!-- <span class="ps-3">Working Hours : <span ></span></span> -->
                          <div class="ps-3 d-inline-block"><a href="mailto:"> MON - FRI, 10AM - 9PM</a></div>
                        </div>
                      </li>
                    </ul>
                    <form method="post" action="" id="subscribe">
                      <div class="input-group">
                    <input type="text" class="form-control" placeholder="Your Email">
                    <button class="input-group-text" type="submit">Free Session</button>
                  </div>
                    </form>
            </div>
      
        </div>
        </div>
    </div>
    <div class="border-top footer-bottom">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-4">
            <a href=""> <img src="{{assets()}}images/playstore.png" class="app" alt="playstore"> </a>
            <a href=""> <img src="{{assets()}}images/applestore.png" class="app ms-3" alt="appstore"> </a>
          </div>
          <div class="col-lg-4"><div class="p my-3 text-center">© Copyright {{date('Y')}}  <a href="#">{{$setting->company_name}}</a></div></div>
          <div class="col-lg-4">
            <ul class="list-unstyled d-flex social-media gap-2 justify-content-center mb-0">
              <li> <a href="{{$setting->facebook}}"><i class="fa-brands fa-facebook-f">  </i></a> </li>
              <li> <a href="{{$setting->twitter}}"><i class="fa-brands fa-twitter">     </i></a> </li>
              <li> <a href="{{$setting->linked_in}}"><i class="fa-brands fa-linkedin-in"> </i></a> </li>
              <li> <a href="{{$setting->insta}}"><i class="fa-brands fa-instagram">   </i></a> </li>
              <li> <a href="{{$setting->youtube}}"><i class="fa-brands fa-youtube"> </i></a> </li>
            </ul>
          </div>
                  </h6>
        </div>
      </div>
    </div>
  </footer>

  <script src="{{assets()}}js/jquery.min.js"></script>
<script src="{{assets()}}js/bootstrap.bundle.min.js"></script>
<script src="{{assets()}}js/owl.carousel.min.js"></script>
<script src="{{assets()}}js/notify.min.js"></script>
@stack('scripts')
<script src="{{assets()}}js/script.js"></script>

<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("header-sticky");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

</script>



</body>

</html>