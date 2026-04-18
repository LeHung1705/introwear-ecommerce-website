<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('assets/images/462693387_859571592991640_8271743291619247352_n.jpg') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/trangchu.css')}}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    @stack('styles')
</head>
<body>
    <!-- HEADER -->
    <header class="header-main">
        <div class="header-top">
            <div class="hamburger-menu">
                <i class="fas fa-bars"></i>
            </div>
            <div class="logo">
              <a href="{{route('home.index')}}">
                <img src="{{ asset('/assets/images/logo.png')}} " alt="INTROWEAR Logo" class="logo-img">
                <span class="logo-text"></span>
                </a>
            </div>
            
            <div class="search-bar">
              <i class="fas fa-search search-icon"></i>
              <input type="text" id="search-input" placeholder="Search">
            </div>
            <div id="box-content-search" class="search-result">
                <ul id="search-list"></ul>
            </div>
            
          @guest
            <div class="header-actions">
                <div class="icons">
                    <a href="{{route('login')}}" >
                    <i class="fas fa-user user-icon"></i>
                </a>
                    <div class="cart-wrapper">
                        <a href="{{route('cart.index')}}">
                        <i class="fas fa-shopping-cart cart-icon">
                          @if(Cart::instance('cart')->content()->count() > 0)
                          <sub>({{Cart::instance('cart')->content()->count()}})</sub>
                          @endif
                        </i>
                    </a>
                        <span class="cart-count"></span>
                    </div>
                </div>
            </div>
          @else 
            <div class="header-actions">
                <div class="icons">
                    <a style = "text-decoration :none; color : black;"href="{{ Auth::user()->utype=='ADM' ? route('admin.dashboard') : route('user.index')}}">
                    <span class="pr-6px">{{Auth::user()->name}}</span>
                    <i class="fas fa-user user-icon"></i>
                </a>
                    <div class="cart-wrapper">
                        <a href="{{route('cart.index')}}">
                        <i class="fas fa-shopping-cart cart-icon">
                          @if(Cart::instance('cart')->content()->count() > 0)
                          <sub>({{Cart::instance('cart')->content()->count()}})</sub>
                          @endif
                        </i>
                    </a>
                        <span class="cart-count"></span>
                    </div>
                </div>
            </div>
          @endguest
    </div>
        <div class="header-bottom responsive-nav">
            <nav>
                <ul>
                    <li><a href="{{route('home.index')}}" id="homepageLink">HOMEPAGE</a></li>
                    <li class="dropdown"><a href="{{route('shop.index')}}">SHOP</a></li>
                    <li><a href="{{route('aboutus.index')}}" id="aboutUsLink">ABOUT US</a></li>
                        <li><a href="{{ route('feedback.form') }}">FEEDBACK</a></li>
                </ul>
            </nav>

        </div>
       <!-- Dropdown kết quả tìm kiếm -->
      <div id="box-content-search" class="search-result">
          <ul id="search-list"></ul>
      </div>
    </header>
      
                 <div class="content">
                    <!-- Nội dung trang đặt ở đây -->
                    <!-- Nội dung trang đặt ở đây -->
                
    <!-- MAIN CONTENT -->
    @yield('content')
    
 </div> 
    <!-- FOOTER -->
    <footer class="footer">
      <!-- Top section of footer -->
      <div class="footer__top">
        <div class="footer__container-wide">
          <div class="footer__row">
            <!-- Certificate -->
            <div class="footer__certificate footer__col">
              <a href="#" target="_blank">
                <img
                  src="//theme.hstatic.net/200000677367/1001276449/14/dathongbao.png?v=2486"
                  alt="Bộ công thương"
                />
              </a>
            </div>

            <!-- Contact information -->
            <div class="footer__contact footer__col">
              <div class="contact-list">
                <p class="contact-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <span
                    ><strong>Địa chỉ:</strong><br />
                    METUB TOWER, số 35/2 Nguyễn Văn Hưởng, Phường Thảo Điền, TP.
                    Thủ Đức, TP. Hồ Chí Minh.</span
                  >
                </p>

                <p class="contact-item">
                  <i class="fas fa-building"></i>
                  <span
                    ><strong>Công ty:</strong><br />
                    CÔNG TY CỔ PHẦN VIETNAM NEW LIFESTYLE INCUBATION</span
                  >
                </p>

                <p class="contact-item">
                  <i class="fas fa-file-alt"></i>
                  <span><strong>MST: </strong> 0316416910</span>
                </p>
              </div>
            </div>

            <!-- Phone -->
            <div class="footer__phone footer__col">
              <p class="contact-item">
                <i class="fas fa-phone"></i>
                <span
                  ><strong>Điện thoại:</strong><br />
                  0877.534.588 (Zalo)</span
                >
              </p>
            </div>

            <!-- Email -->
            <div class="footer__email footer__col">
              <p class="contact-item">
                <i class="far fa-envelope"></i>
                <span><strong>Email:</strong> contact@introwear.com</span>
              </p>
            </div>

            <!-- Social media links -->
            <div class="footer__social footer__col">
              <ul class="social-list">
                <li class="social-item">
                  <a href="#" target="_blank" aria-label="Facebook"
                    ><i class="fab fa-facebook-f"></i
                  ></a>
                </li>
                <li class="social-item">
                  <a href="#" target="_blank" aria-label="Tiktok"
                    ><i class="fab fa-tiktok"></i
                  ></a>
                </li>
                <li class="social-item">
                  <a href="#" target="_blank" aria-label="YouTube"
                    ><i class="fab fa-youtube"></i
                  ></a>
                </li>
                <li class="social-item">
                  <a href="#" target="_blank" aria-label="Instagram"
                    ><i class="fab fa-instagram"></i
                  ></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom section of footer (copyright) -->
      <div class="footer__bottom">
        <!-- Policy links -->
        <div class="footer__policy">
          <ul class="policy-list">
            <li class="policy-item"><a href="./aboutus.html">Giới thiệu</a></li>
            <li class="policy-item"><a href="#">Điều khoản dịch vụ</a></li>
            <li class="policy-item"><a href="#">Phương thức thanh toán</a></li>
            <li class="policy-item">
              <a href="#">Chính sách vận chuyển & đổi trả</a>
            </li>
            <li class="policy-item"><a href="#">Liên hệ</a></li>
            <li class="policy-item">
              <a href="#">Chính sách thu thập và xử lý dữ liệu cá nhân</a>
            </li>
          </ul>
        </div>
        <div class="footer__container">
          <p class="footer__copyright">
            Copyright © 2025 <a href="#">INTROWEAR</a>. Powered by
            <a href="#" target="_blank">TEAM INTROWEAR</a>
          </p>
        </div>
      </div>
    </footer>

    <script src="{{ asset('assets/js/main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    $(function() {
    $("#search-input").on("keyup", function() {
        var searchQuery = $(this).val().trim();
        const $searchResult = $("#box-content-search");

        if (searchQuery.length > 1) {
            $.ajax({
                type: "GET",
                url: "{{ route('home.search') }}", // Thay bằng URL thực tế của bạn
                data: { query: searchQuery },
                dataType: 'json',
                success: function(data) {
                    $searchResult.empty();

                    if (data.length > 0) {
                        $searchResult.show();
                        $.each(data, function(index, item) {
                            var url = "{{ route('shop.product.details', ['id' => '__ID__']) }}".replace('__ID__', item.id);
                            $searchResult.append(`
                                <li class="product-item">
                                    <div class="image">
                                        <img src="{{ asset('uploads/products/') }}/${item.image}" alt="${item.product_name}">
                                    </div>
                                    <div class="name">
                                        <a href="${url}">${item.product_name}</a>
                                    </div>
                                </li>
                                ${index < data.length - 1 ? '<li><div class="divider"></div></li>' : ''}
                                      `);
                                  });
                              } else {
                                  $searchResult.hide();
                              }
                          },
                          error: function() {
                              $searchResult.hide();
                          }
                      });
                    } else {
                        $searchResult.hide();
                    }
                    });

                    // Ẩn dropdown khi nhấp ra ngoài
                    $(document).on('click', function(event) {
                        if (!$(event.target).closest('.search-bar').length && !$(event.target).closest('#box-content-search').length) {
                            $("#box-content-search").hide();
                        }
                    });

                    // Ẩn dropdown khi chọn một sản phẩm
                    $("#box-content-search").on('click', 'a', function() {
                        $("#box-content-search").hide();
                    });
                });
    </script>
    @stack('scripts')
  </body>
</html>
