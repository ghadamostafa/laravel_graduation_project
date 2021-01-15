<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>MY DESIGN</title>
        
        <!-- Scripts -->

          @stack('my_style')
          
        <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- login    --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    {{-- end of login --}}
     
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
   
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,700,700i" rel="stylesheet">

    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    @stack('my_style')        
    <style type="text/css">
        #Notifications::after {
            content: none;
        }
        .hideNotification{
            display: none;
        }
        .notify
        {
            background-color:lightgray;
        }
    </style>
     @yield('styles')
    <script type="text/javascript">
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>

    </script>
    @if(!auth()->guest())

    <script>
        window.Laravel.userId = <?php echo auth()->user()->id; ?>
    </script>

    @endif
   
     


</head>
<body>
    <div id="app">
        @auth
            @if ((Auth::user()->email_verified_at === null) ||  (Auth::user()->profile->is_verified != 'accepted'))
            <div class="alert alert-success " role="alert">
                <ul class="ml-5">
                    @if (Auth::user()->email_verified_at === null)
                       <li> <strong>Your email is not verified yet please check your email</strong> </li> 
                    @endif
                    @if( (Auth::user()->role != 'user') &&(Auth::user()->profile->is_verified != 'accepted') )
                     <li><strong>Your profile is not accepted yet !</strong> </li> 
                    @endif
                </ul>
            </div>
            @endif
        @endauth
        <!-- Header section -->
    <header class="header-section sticky-top" style="background-color: white;">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 text-center text-lg-left">
                        <!-- logo -->
                        <a href="/" class="site-logo">
                            <img src="{{ asset('images/myDesign.png') }}" alt="">
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-5">
                        <form class="header-search-form" method="GET" action="{{ route('search') }}">
                            <input type="text" placeholder="Search by Tag or Category ...." name="word">
                            <button><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="user-panel">
                            @auth
                                @if(Auth::user()->role != "company")
                                <div class="up-item notifications" >
                                        <a id="Notifications" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="MarkAsRead()"> <i class="fa fa-globe" aria-hidden="true"></i>
                                        </a>
                                        <input type="hidden" name="count" id="count" value="{{Auth::user()->unreadNotifications->count() }}" >

                                        <span id="Notification-count" class="{{ (Auth::user()->unreadNotifications->count()  <= 0) ? ' hideNotification': '' }} ">{{ Auth::user()->unreadNotifications->count() }}</span>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Notifications" id="notificationList">                                           
                                           
                                            @if( Auth::user()->role == "designer") 
                                                @forelse (Auth::user()->unreadNotifications as $notification)
                                                    @if($notification->type === "App\\Notifications\\designerNotifications")
                                                    <a class="dropdown-item notify" href="{{ route('designs.show',['design'=>$notification->data['design']['id']]) }}">
                                                      {{$notification->data['company']}} has bought your {{ $notification->data['design']['title'] }} design
                                                    </a>

                                                    @endif
                                                    @if($notification->type === "App\\Notifications\\UserWithdrawNotification")
                                                    <a class="dropdown-item notify" href="{{$notification->data['route']}}">
                                                      Your withdraw Request is {{$notification->data['state']}} check your email for more information
                                                    </a>

                                                    @endif
                                                @empty
                                                    <div class="alert alert-danger noNotification" style="width: 250px;height: 40px;">No unread notifications</div>
                                                @endforelse
                                            @elseif(Auth::user()->role == "user")
                                                @foreach (Auth::user()->unreadNotifications as $notification)
                                                    @if($notification->type === "App\\Notifications\\UserNotifications")
                                                        <a class="dropdown-item" href="{{ route('designs.show',['design'=>$notification->data['design']['id']]) }}">
                                                        {{$notification->data['designer']['name']}} has added a new design {{$notification['design']['title']}}
                                                        </a>
                                                    @elseif($notification->type === "App\\Notifications\\CompanyUserNotifications")
                                                        <a class="dropdown-item" href="{{ route('designs.show',['design'=>$notification->data['design']['id']]) }}">
                                                        {{$notification->data['company']}} has made your beloved design
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                </div>
                                @endif
                                @if( $user->role == "designer")
                                <div style="display: inline;margin-right: 20px;"> 
                                <a href="{{ route('designer.show',['designer'=>$user->id]) }}" style="color: black;"> Profile</a>
                                <a class="ml-3" href="{{ route('balance',$user) }}" style="color: black;">   Balance</a>
                                </div>
                                @elseif($user->role == "company")
                                <div style="display: inline;margin-right: 20px;"> 
                                <a href="{{ route('company.show',['company'=>$user->id]) }}" style="color: black;"> Profile</a>
                                </div>
                                @elseif( $user->role == "user")
                                <div style="display: inline;margin-right: 20px;"> 
                                    <a href="{{ route('user.show',['user'=>$user->id]) }}" style="color: black;"> Profile</a>
                                </div>
                                @endif
                            @endauth
                            @guest
                                <div class="up-item">
                                    <i class="flaticon-profile"></i>
                                    @if (Route::has('register'))
                                        <a href="{{ route('login') }}">Sign</a> In or
                                        <a href="{{ route('createAccount') }}">Create Account</a> 
                                       {{-- <div class="dropdown show">
                                          <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           Create Account
                                          </a>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a href="{{ route('register') }}" class="items">Register</a>
                                            <a href="{{ route('registeration.form','admin') }}" class="items">Register Admin</a>
                                            <a href="{{ route('registeration.form','user') }}" class="items">Register User</a>
                                            <a href="{{ route('registeration.form','company') }}" class="items">Register Company</a>
                                            <a href="{{ route('registeration.form','designer') }}" class="items">Register Designer</a>
                                          </div>
                                        </div>  --}}                                        
                                    @endif
                                </div>
                            @else
                                
                                <div class="up-item">
                                    <a id="navbarDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            @endguest
                            @if ($user_role == 'company')                                
                                <div class="up-item">
                                    <div class="shopping-card">
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    <span id="cart-count">{{ $cart_count }}</span>
                                    </div>
                                    <a href="{{ route('website.cart') }}">Shopping Cart</a>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-navbar">
            <div class="container">
                <!-- menu -->
                <ul class="main-menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('designer.index')}}">Designers</a></li>
                    <li><a href="{{ route('designs.index')}}">Designs</a></li>
                    <li><a href="{{route('company.index')}}">Companies</a></li>
                    <li><a href="#">Categories</a>
                        <ul class="sub-menu">
                            <li><a href="/designs?category=women">Women</a></li>
                            <li><a href="/designs?category=men">Men</a></li>
                            <li><a href="/designs?category=kids">Kids</a></li>
                            <li><a href="/designs?category=teenagers">Teenagers</a></li>
                        </ul>
                    </li>
                <li><a href="/about">How it works</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Header section end -->

    @yield('content')

    <!-- Footer section -->
    <section class="footer-section">
        <div class="social-links-warp">
            <div class="container">
            

<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --> 
<p class="text-white text-center mt-5">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->

            </div>
        </div>
    </section>
    <!-- Footer section end -->



    </div>

   
    <!--====== Javascripts & Jquery ======-->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    
    <script src="{{ asset('js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>  

    <script>
        function MarkAsRead()
            {
                count=$('#count').val();
                if(count>0)
                {
                    console.log("click");
                     $.get('/notification/MarkAsRead', function(data, status){
                        // alert("Data: " + data + "\nStatus: " + status);
                        $('#Notification-count').html(0);
                        $('#count').val(0);
                         $('#Notification-count').addClass("hideNotification");
   
                      }) ;
                }
            }

        $(document).ready(function()
        {
            console.log(Laravel.userId);
            if(Laravel.userId) 
            {
                window.Echo.private(`App.User.${Laravel.userId}`)
                .notification((notification) => {
                    count= $('#count').val();
                    count=parseInt(count)+1;
                    $('#Notification-count').html(count);
                    $('#count').val(count);
                    $('#Notification-count').removeClass("hideNotification");

                if(notification['type'] === 'App\\Notifications\\designerNotifications')
                {
                   if($('#notificationList .noNotification'))
                   {
                    $('#notificationList .noNotification').remove();
                   }

                   $('#notificationList a').each(function() {
                      $( this ).removeClass( "notify" );
                    });

                   $('#notificationList').prepend(`
                    <a class="dropdown-item notify" href="/design/${notification['design']['id']}" >
                                              ${notification['company']} has bought your ${notification['design']['title']} design
                                            </a>
                    `);
                }
                    else if(notification['type'] === 'App\\Notifications\\UserNotifications')
                    {
                            $('#notificationList').prepend(`
                            <a class="dropdown-item" href="/design/${notification['design_id']}" style="background-color:lightgray">
                            ${notification['designer']['name']} has just added a new design ${notification['design']['title']}
                            </a>
                            `);
                    }
                    else if(notification['type'] === 'App\\Notifications\\CompanyUserNotifications')
                    {
                            $('#notificationList').prepend(`
                            <a class="dropdown-item" href="${notification['product_link']}">
                            ${notification['company']} converted your lovely design into an amazing product ${notification['design']['title']}
                            </a>
                            `);
                    }
                    else if(notification['type'] === 'App\\Notifications\\UserWithdrawNotification')
                    {
                            $('#notificationList').prepend(`
                            <a class="dropdown-item" href="${notification['route']}">
                            Your withdraw Request is ${notification['state']} check your email for more information
                            </a>
                            `);
                            if($('#notificationList .noNotification'))
                            {
                                $('#notificationList .noNotification').remove();
                            }
                    }
                
                    console.log(notification['type']);
                });
                
            }
            $(document).on('click', '.add-card', function(){
                var design_id = $(this).data('id');
                $.post('{{ route('add-to-cart') }}', {"_token": "{{ csrf_token() }}","id": design_id}, function(response){
                    
                    if(response.status == '1'){

                        $(document).find('#cart-count').html(response.count);
                        
                    }
                        alert(response.msg);
                }).fail(function(error){

                })

            })


        });
    </script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
     <script src="//js.pusher.com/3.1/pusher.min.js"></script>
     <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 
     <script type="text/javascript">
       var notificationsWrapper   = $('.dropdown-notifications');
       var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
       var notificationsCountElem = notificationsToggle.find('i[data-count]');
       var notificationsCount     = parseInt(notificationsCountElem.data('count'));
       var notifications          = notificationsWrapper.find('ul.dropdown-menu');
 
       if (notificationsCount <= 0) {
         notificationsWrapper.hide();
       }
 
       // Enable pusher logging - don't include this in production
       // Pusher.logToConsole = true;
 
       var pusher = new Pusher('API_KEY_HERE', {
         encrypted: true
       });
 
       // Subscribe to the channel we specified in our Laravel Event
       var channel = pusher.subscribe('status-liked');
 
       // Bind a function to a Event (the full Laravel class)
       channel.bind('App\\Events\\StatusNotifin', function(data) {
         var existingNotifications = notifications.html();
         var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
         var newNotificationHtml = `
           <li class="notification active">
               <div class="media">
                 <div class="media-left">
                   <div class="media-object">
                     <img src="https://api.adorable.io/avatars/71/`+avatar+`.png" class="img-circle" alt="50x50" style="width: 50px; height: 50px;">
                   </div>
                 </div>
                 <div class="media-body">
                   <strong class="notification-title">`+data.message+`</strong>
                   <!--p class="notification-desc">Extra description can go here</p-->
                   <div class="notification-meta">
                     <small class="timestamp">about a minute ago</small>
                   </div>
                 </div>
               </div>
           </li>
         `;
         notifications.html(newNotificationHtml + existingNotifications);
 
         notificationsCount += 1;
         notificationsCountElem.attr('data-count', notificationsCount);
         notificationsWrapper.find('.notif-count').text(notificationsCount);
         notificationsWrapper.show();
       });
     </script>

     @stack('scripts')


</body>
</html>
