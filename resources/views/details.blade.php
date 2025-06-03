@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/product-detail.css') }}" />
@endpush
@section('content')
       <!-- Content-1 -->
      <div class="product-detail-container">
        <div class="product-detail-container-item1">
          <div class="slider-wrapper">
             <div class="slider-viewport">
             {{-- <div class="slider-inner" id="slider-inner">
                <div class="slider-item" id="slider-item1">
                  <img
                    src="{{asset('uploads/products')}}/{{$product->image}}"
                    alt="ex"
                    width="93px"
                  />
                </div> 
              </div>  --}}
            </div> 
          </div>
          <div class="product-images">
            <img
              src="{{asset('uploads/products')}}/{{$product->image}}"
              alt="ex"
              width="100%"
            />
          </div>
        </div>
        <div class="product-detail-container-item2">
          <div class="product-name">
            <span style="font-size: 30px; font-weight: 400">{{$product->product_name}}</span>
          </div>
          <div class="product-price">
            <span style="font-size: 20px; font-weight: bold">{{number_format($product->price_sale,0,',',',')}}₫</span>
            <span
              style="
                font-size: 14px;
                color: #979797;
                font-weight: bold;
                text-decoration: line-through;
              "
              >{{number_format($product->price,0,',',',')}}₫
            </span>
            <span style="font-size: 14px; color: red; font-weight: bold">-{{number_format(($product->price-$product->price_sale)*100/$product->price,0,'.','')}}%</span>
          </div>
          <div class="product-price-save">
            <span style="font-size: 16px; font-weight: bold"
              >(Tiết kiệm {{number_format($product->price-$product->price_sale,0,',',',')}}₫)</span
            >
          </div>
           <div class="product-size-container">
            <span style="font-size: 16px; font-weight: 200">Màu sắc</span>
            <div class="product-color">
              <div class="product-color-item auto-choose">
                <button id="product-color-1" style="background-color: {{$product->color}}"></button>
              </div>
              
            </div>
          </div>
          <div class="product-size-container">
            <span style="font-size: 16px; font-weight: 200">Kích thước</span>
            <div class="product-size">
              <div style="border: 1px solid black; font-size: 16px;" class="product-size-item">{{$product->size}}</div>
            </div>
          </div>
          <!-- Form cho "Thêm vào giỏ" -->
          @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
            <a href="{{route('cart.index')}}" class="add-to-cart" style="font-weight:400px;">Go to cart</a>
          @else
          <form action="{{route('cart.add')}}" method="post" enctype="multipart/form-data">
            @csrf
             <div class="quantity-wrapper">
                <label for="quantity" class="quantity-label">Số lượng</label>
                   <div class="quantity-control">
                        <input type="number" id="quantity" name="quantity" class="quantity-input" value="1" min="1" />
                      <div class="quantity-buttons">
                       <button type="button" id="increase-btn">+</button>
                       <button type="button" id="decrease-btn">−</button>
                      </div>
                    </div>
            </div>
            <input type="hidden" name="id" value="{{$product->id}}">
            <input type="hidden" name="name" value="{{$product->product_name}}">
            <input type="hidden" name="price" value="{{$product->price_sale}}">
            <input type="hidden" name="size" value="{{$product->size}}">
            <input type="hidden" name="color" value="{{$product->color}}">
            
            <!-- Button "Mua ngay" - sẽ submit form với action khác -->
            <button type="button" class="buy-now" onclick="buyNow()" style="text-decoration:none; color:black; border:none; cursor:pointer;">
              <span class="text" style='font-weight: 400'>MUA NGAY</span>
            </button>
            
            <!-- Button "Thêm vào giỏ" -->
            <button type="submit" class="add-to-cart">
              <span class="text" style='font-weight: 400'>THÊM VÀO GIỎ</span>
            </button>
          </form>
          @endif

          <div class="product-description">
            <button class="product-description-item">
              <span style="font-size: 16px; color: black; font-weight: 300"
                >Thông tin sản phẩm</span
              >
              <i class="fa fa-chevron-down"></i>
            </button>
            <div class="product-description-item-content" id="product-info">
              {{-- <p><strong>Màu sắc:</strong>{{$product->color}}</p>
              <p><strong>Chất liệu:</strong> Cotton</p> --}}
              {{-- <p><strong>Số lượng:</strong> 1 set gồm 3 đôi</p> --}}
              <p class="description-information " style="width: 100%;white-space: pre-line;">
              {{$product->description}}
              </p>
            </div>
            <button class="product-description-item">
              <span style="font-size: 16px; color: black; font-weight: 300">Kích thước</span>
              <i class="fa fa-chevron-down"></i>
            </button>
            <div class="product-description-item-content">
              <img src="{{asset('uploads/products/bangsize.jpg')}}" alt="ex" width="100%" />
            </div>
            <button class="product-description-item">
              <span style="font-size: 16px; color: black; font-weight: 300">Chính sách giao hàng</span>
              <i class="fa fa-chevron-down"></i>
            </button>
            <div class="product-description-item-content">
              <div class="shipping-info">
                <h3>1. Thời gian giao hàng</h3>
                <p>
                  Đối với nội thành Hồ Chí Minh: Giao hàng trong 1 – 2 ngày
                  (Không tính chủ nhật và các ngày lễ Tết).
                </p>
                <p>
                  Đối với ngoại thành: Giao hàng trong 3 - 5 ngày (Không tính
                  chủ nhật và các ngày lễ Tết).
                </p>
                <p>
                  <strong>Lưu ý:</strong> Thời gian có thể dao động thêm 3 - 5
                  ngày đối với các đợt giảm giá lớn.
                </p>

                <h3>2. Xác nhận đơn hàng</h3>
                <p>
                  Đơn hàng qua tổng đài điện thoại & chat fanpage được nhân viên
                  INTROWEAR hỗ trợ tạo đơn.
                </p>
                <p>
                  Đơn hàng sau khi tạo, trong trường hợp có phát sinh, hoặc thay
                  đổi, sẽ được nhân viên CSKH của INTROWEAR liên lạc qua điện
                  thoại để xử lý.
                </p>

                <h3>3. Quy trình giao nhận hàng hóa</h3>
                <p>
                  Hình thức thanh toán trực tiếp khi nhận hàng, khách hàng thanh
                  toán trực tiếp cho nhân viên giao hàng ngay sau khi nhận hàng,
                  thanh toán đầy đủ cho toàn bộ giá trị đơn hàng.
                </p>
                <p>
                  Hình thức đã thanh toán bằng chuyển khoản, thanh toán qua thẻ,
                  khách hàng chỉ nhận hàng và ký nhận với nhân viên giao hàng.
                </p>
                <p>
                  <strong>Lưu ý:</strong> INTROWEAR không hỗ trợ chính sách đồng
                  kiểm khi quý khách hàng nhận hàng từ đơn vị vận chuyển.
                </p>

                <h3>4. Số lần giao hàng</h3>
                <p>
                  Nhân viên giao - nhận hàng sẽ giao hàng tối đa trong 03
                  lần/đơn hàng.
                </p>
                <p>
                  Trường hợp lần đầu giao hàng không thành công, Nhân viên giao
                  - nhận hàng sẽ liên hệ để sắp xếp lịch giao hàng lần 02 với
                  bạn. Tổng cộng bạn có 03 lần để nhận đơn hàng.
                </p>
                <p>
                  Xin lưu ý rằng trong trường hợp chịu ảnh hưởng của thiên tai
                  hoặc các sự kiện đặc biệt khác tác động không thể thay đổi thì
                  chúng mình sẽ bảo lưu quyền thay đổi thời gian giao hàng mà
                  không cần báo trước.
                </p>
                <p>
                  Ngoài ra, tụi mình hiểu được việc chờ đợi món đồ mình yêu
                  thích quá lâu sẽ là một trải nghiệm chưa tốt. Do đó, để được
                  hỗ trợ giao hàng gấp/giao trong ngày, bạn có thể liên hệ với
                  tụi mình qua mục FB Chat ngay tại Website hoặc Instagram của
                  tụi mình nhé.
                </p>

                <h3>5. Kiểm tra tình trạng đơn hàng</h3>
                <p>
                  Bạn có thể liên hệ với bộ phận chăm sóc khách hàng của
                  INTROWEAR qua các trang Mạng xã hội (Facebook, Instagram,
                  TikTok) hoặc mục FB CHAT ngay tại Website bằng việc cung cấp
                  cho tụi mình mã số đơn hàng, số điện thoại đã đặt đơn. Chúng
                  mình sẽ kiểm tra và phản hồi ngay khi nhận được yêu cầu kiểm
                  tra đơn hàng của bạn.
                </p>
                <p>
                  <strong>Lưu ý:</strong> INTROWEAR luôn cố gắng để mang đến
                  trải nghiệm tốt nhất cho khách hàng, tuy nhiên trên thực tế
                  quá trình vận chuyển có thể phát sinh nhiều rủi ro ngoài ý
                  muốn khiến việc giao hàng bị chậm trễ. Trong trường hợp này,
                  INTROWEAR sẽ chủ động thông báo tình trạng đơn hàng đến bạn,
                  hoặc để nhận được hỗ trợ nhanh chóng và tốt nhất, bạn vui lòng
                  liên hệ bộ phận CSKH theo số 0877.534.588 các trang Mạng xã
                  hội (Facebook, Instagram, TikTok) hoặc mục FB CHAT ngay tại
                  Website.
                </p>
              </div>
            </div>
            <button class="product-description-item">
              <span style="font-size: 16px; color: black; font-weight: 300"
                >Chính sách đổi trả</span
              >
              <i class="fa fa-chevron-down"></i>
            </button>
            <div class="product-description-item-content">
              <div class="return-policy">
                <h3>1. Điều kiện đổi trả:</h3>
                <p>
                  Hỗ trợ đổi trả sản phẩm trong vòng 03 ngày kể từ ngày nhận
                  hàng:
                </p>
                <p>
                  Đối với trường hợp đổi size: vui lòng thanh toán chi phí phát
                  sinh.
                </p>
                <p>
                  Đối với trường hợp đổi trả sản phẩm lỗi do sản xuất hoặc vận
                  chuyển nhầm: Vui lòng gửi kèm hình ảnh / video để được hỗ trợ
                  giải quyết nhanh nhất.
                </p>
                <p>
                  Sản phẩm đổi trả phải đúng với sản phẩm đặt mua, chưa qua sử
                  dụng, chưa qua giặt giũ và còn nguyên tem mác:
                </p>
                <p>Áp dụng 01 lần đổi / 01 đơn hàng</p>

                <h3>2. Thời gian đổi trả:</h3>
                <p>
                  Với những đơn hàng tại TP. Hồ Chí Minh: Đổi trả từ 2 - 3 ngày
                </p>
                <p>Với những đơn hàng tỉnh: Đổi trả từ 3 - 5 ngày</p>

                <h3>3. Quy trình đổi trả:</h3>
                <p>
                  Để INTROWEAR có thể hỗ trợ và bảo vệ quyền lợi tiêu dùng của
                  khách hàng theo chính sách đổi trả, cụ thể quy trình đổi, trả
                  hàng như sau:
                </p>
                <p>
                  <strong>Bước 1:</strong> Khách hàng gửi yêu cầu đổi trả qua
                  các kênh liên lạc của INTROWEAR Hotline, Fanpage, Email. Chúng
                  tôi sẽ hướng dẫn khách hàng cách đổi/trả sản phẩm, nếu yêu cầu
                  đổi/trả sản phẩm của khách hàng đáp ứng điều kiện đổi trả.
                </p>
                <p>
                  <strong>Bước 2:</strong> Vui lòng cung cấp thông tin khiếu
                  nại: mã đơn hàng, tên sản phẩm, số lượng, mô tả tình trạng sản
                  phẩm, lí do đổi trả, kèm ảnh chụp (bắt buộc)
                </p>
                <p>
                  <strong>Bước 3:</strong> Phản hồi đổi trả và giải pháp từ
                  INTROWEAR
                </p>
                <p>
                  <strong>Bước 4:</strong> Khách hàng gửi trả hàng thu hồi
                  nguyên kiện cho đơn vị vận chuyển được tư vấn
                </p>
                <p>
                  <strong>Bước 5:</strong> Khách hàng nhận sản phẩm thay thế
                  hoặc nhận hoàn tiền (nếu đã được xác nhận thanh toán)
                </p>

                <h3>4. Chính sách hoàn tiền:</h3>
                <p>
                  Các đơn hàng được mua và thanh toán qua các Phương thức thanh
                  toán đã được liệt kê: COD, Chuyển khoản, Thẻ nội địa, Thẻ quốc
                  tế sẽ được hoàn lại toàn bộ hoặc 1 phần số tiền đã thanh toán
                  trong các trường hợp sau:
                </p>
                <p>Sản phẩm hết hàng không thể giao cho khách</p>
                <p>Sản phẩm hỏng khi sử dụng và không thể giao bù đổi</p>
                <p>
                  Một hoặc một vài sản phẩm trong đơn hàng không thể giao cho
                  khách (hoàn tiền 1 phần tương đương giá trị món hàng)
                </p>
                <p>
                  INTROWEAR không hoàn lại tiền chênh lệch (thừa) trong trường
                  hợp có yêu cầu thay đổi từ khách hàng, đổi một sản phẩm mới có
                  giá trị thấp hơn sản phẩm đã đặt ban đầu.
                </p>
                <p>
                  Thời gian hoàn tiền: trong 07 ngày làm việc kể từ khi giải
                  pháp ở Quy trình đổi trả (Bước 3) được đồng thuận, INTROWEAR
                  sẽ thực hiện việc thanh toán thông qua chuyển khoản.
                </p>
                <p><strong>Lưu ý:</strong></p>
                <p>Không áp dụng đổi/trả với sản phẩm quà tặng.</p>
                <p>
                  Chỉ hỗ trợ trả hàng nếu quà tặng/sản phẩm mua kèm/sản phẩm mua
                  theo gói còn nguyên mới và được gửi lại đầy đủ.
                </p>

                <h3>5. Phí chuyển trả hàng:</h3>
                <p>
                  Nếu bạn đổi trả lại hàng, toàn bộ phí gửi sản phẩm phát sinh
                  sẽ do bạn thanh toán. INTROWEAR sẽ chịu trách nhiệm thanh toán
                  phí gửi trả lại hàng cho các bạn trong trường hợp:
                </p>
                <p>
                  Hàng hóa không đúng chủng loại, mẫu mã như quý khách đặt hàng.
                </p>
                <p>Hàng hóa bị hư hại trong quá trình vận chuyển.</p>
                <p>Hàng hóa bị lỗi sản xuất.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Content-2 -->
      <div class="product-suggest-container">
        <div class="product-suggest-title">Gợi ý cho bạn</div>
        <div class="product-suggest">
        @foreach ($rproducts as $rproduct )
            
        
          
          <a class="product-suggest-item" href="{{route('shop.product.details',['id'=>$rproduct->id])}}" style="text-decoration: none; font-size: 13px;">
            <img id="product-suggest-item-img-1" src="{{asset('uploads/products')}}/{{$rproduct->image}}" alt="{{$rproduct->product_name}}" width="100%" />
            <div class="product-suggest-item-name">{{$rproduct->product_name}}</div>
            <div class="product-suggest-item-price">
              <span
                id="product-suggest-item-cost-1"
                style="font-size: 12px; color: black; font-weight: 200"
                >{{number_format($rproduct->price_sale,0,',',',')}}₫</span
              ><span
                id="product-suggest-item-actual-cost-1"
                style="
                  font-size: 12px;
                  color: #979797;
                  font-weight: 200;
                  text-decoration: line-through;
                "
                >{{number_format($rproduct->price,0,',',',')}}₫</span
              >
            
          </div>
        </a>
          @endforeach
          {{-- <div class="product-suggest-item">
            <img id="product-suggest-item-img-2" src="/Website/assets/images/so_mi_xanh.png" alt="STRIPED BOXY SHIRT" width="100%" />
            <div class="product-suggest-item-name">STRIPED BOXY SHIRT</div>
            <div class="product-suggest-item-price">
              <span
                id="product-suggest-item-cost-2"
                style="font-size: 12px; color: black; font-weight: 200"
                >580,000₫</span
              ><span
                id="product-suggest-item-actual-cost-2"
                style="
                  font-size: 12px;
                  color: #979797;
                  font-weight: 200;
                  text-decoration: line-through;
                "
                >650,000₫</span
              >
            </div>
          </div>
          <div class="product-suggest-item">
            <img id="product-suggest-item-img-3" src="/Website/assets/images/cardian_hong.png" alt="BASIC TYPO BOXY LONG-SLEEVE T-SHIRT" width="100%" />
            <div class="product-suggest-item-name">BASIC TYPO BOXY LONG-SLEEVE T-SHIRT</div>
            <div class="product-suggest-item-price">
              <span
                id="product-suggest-item-cost-3"
                style="font-size: 12px; color: black; font-weight: 200"
                >450,000₫</span
              ><span
                id="product-suggest-item-actual-cost-3"
                style="
                  font-size: 12px;
                  color: #979797;
                  font-weight: 200;
                  text-decoration: line-through;
                "
                >520,000₫</span
              >
            </div>
          </div>
          <div class="product-suggest-item" id="product-suggest-item-4">
            <img id="product-suggest-item-img-4" src="/Website/assets/images/sweater_xanh.png" alt="STAFF BIG BOXY LONG SLEEVE T-SHIRT" width="100%" />
            <div class="product-suggest-item-name">STAFF BIG BOXY LONG SLEEVE T-SHIRT</div>
            <div class="product-suggest-item-price">
              <span
                id="product-suggest-item-cost-4"
                style="font-size: 12px; color: black; font-weight: 200"
                >309,000₫</span
              ><span
                id="product-suggest-item--actual-cost-4"
                style="
                  font-size: 12px;
                  color: #979797;
                  font-weight: 200;
                  text-decoration: line-through;
                "
                >450,000₫</span
              >
            </div>
          </div> --}}
        </div>
      </div>


      <!-- Content-3 -->
      <div class="product-watched-container">
        <div class="product-watched-title">Sản phẩm đã xem</div>
        <div class="product-watched">
        @foreach ($viewedProducts as $vproduct )
          <a class="product-watched-item" href="{{route('shop.product.details',['id'=>$vproduct->id])}}" style="text-decoration: none; font-size: 13px;">
            <img id="product-watched-item-img-1" src="{{asset('uploads/products')}}/{{$vproduct->image}}" alt="ex" width="100%" />
            <div class="product-watched-item-name">{{$vproduct->product_name}}</div>
            <div class="product-watched-item-price">
              <span id="product-watched-item-cost-1" style="font-size: 12px; color: black; font-weight: 200">{{number_format($vproduct->price_sale,0,',',',')}}₫</span>
              <span id="product-watched-item-actual-cost-1" style="font-size: 12px; color: #979797; font-weight: 200; text-decoration: line-through;">{{number_format($vproduct->price_sale,0,',',',')}}₫</span>
            </div>
          </a>
          {{-- <div class="product-watched-item">
            <img id="product-watched-item-img-2" src="" alt="ex" width="100%" />
            <div class="product-watched-item-name"></div>
            <div class="product-watched-item-price">
              <span id="product-watched-item-cost-2" style="font-size: 12px; color: black; font-weight: 200"></span>
              <span id="product-watched-item-actual-cost-2" style="font-size: 12px; color: #979797; font-weight: 200; text-decoration: line-through;"></span>
            </div>
          </div>
          <div class="product-watched-item">
            <img id="product-watched-item-img-3" src="" alt="ex" width="100%" />
            <div class="product-watched-item-name"></div>
            <div class="product-watched-item-price">
              <span id="product-watched-item-cost-3" style="font-size: 12px; color: black; font-weight: 200"></span>
              <span id="product-watched-item-actual-cost-3" style="font-size: 12px; color: #979797; font-weight: 200; text-decoration: line-through;"></span>
            </div>
          </div>
          <div class="product-watched-item" id="product-watched-item-4">
            <img id="product-watched-item-img-4" src="" alt="ex" width="100%" />
            <div class="product-watched-item-name"></div>
            <div class="product-watched-item-price">
              <span id="product-watched-item-cost-4" style="font-size: 12px; color: black; font-weight: 200"></span>
              <span id="product-watched-item-actual-cost-4" style="font-size: 12px; color: #979797; font-weight: 200; text-decoration: line-through;"></span>
            </div>
          </div> --}}
           @endforeach
        </div>
      </div>
@endsection
@push('scripts')
  <script src="{{asset('assets/js/product-detail.js')}}"></script>
  <script src="{{asset('assets/js/main.js')}}"></script>
  <script>
    function buyNow() {
      const form = document.querySelector('form[action="{{route('cart.add')}}"]');
      form.action = "{{route('cart.buy_now')}}";
      form.submit();
    }

      document.addEventListener("DOMContentLoaded", function () {
      const qtyInput = document.getElementById("quantity");
      const increaseBtn = document.getElementById("increase-btn");
      const decreaseBtn = document.getElementById("decrease-btn");

      increaseBtn.addEventListener("click", function () {
        let current = parseInt(qtyInput.value) || 1;
        qtyInput.value = current + 1;
      });

      decreaseBtn.addEventListener("click", function () {
        let current = parseInt(qtyInput.value) || 1;
        if (current > 1) {
          qtyInput.value = current - 1;
        }
      });
    });

</script>
@endpush