# IS207 - Phát triển Ứng Dụng Web 
# INTROWEAR – Trang web bán hàng demo

**Giới thiệu dự án:** INTROWEAR là một trang web thương mại điện tử mẫu (demo) được phát triển trên nền tảng **Laravel**. Mục đích của dự án là minh họa các chức năng cơ bản của một website bán hàng hiện đại như xem danh mục sản phẩm, xem chi tiết sản phẩm, thêm sản phẩm vào giỏ hàng và thanh toán đơn hàng. Người dùng có thể duyệt các danh mục, lọc/sắp xếp sản phẩm và sử dụng tính năng phân trang danh sách sản phẩm để tìm kiếm dễ dàng. Dự án sử dụng Laravel – một framework PHP nổi tiếng với cú pháp linh hoạt và nhiều tính năng mạnh mẽ. Ngoài ra, dự án có thể bao gồm giao diện quản trị (admin) cho phép quản lý danh mục, sản phẩm và đơn hàng.

## Tính năng

* **Xem sản phẩm theo danh mục:** Hiển thị danh sách sản phẩm thuộc các danh mục khác nhau (ví dụ: thời trang nam, thời trang nữ, phụ kiện, v.v.).
* **Chi tiết sản phẩm:** Xem hình ảnh, mô tả, giá cả và thông tin chi tiết cho từng sản phẩm.
* **Thêm vào giỏ hàng:** Người dùng có thể thêm sản phẩm vào giỏ hàng, xem tóm tắt giỏ hàng, chỉnh sửa số lượng hoặc xóa sản phẩm trong giỏ.
* **Giỏ hàng và thanh toán:** Quản lý giỏ hàng và thực hiện đặt hàng (thanh toán) đơn giản. (Nếu có tính năng thanh toán tích hợp, người dùng sẽ thanh toán đặt hàng trực tuyến.)
* **Lọc, sắp xếp, tìm kiếm:** Có thể lọc hoặc sắp xếp sản phẩm theo tiêu chí (theo giá, bán chạy, mới nhất, v.v.) và hỗ trợ tìm kiếm sản phẩm.
* **Phân trang sản phẩm:** Danh sách sản phẩm được chia trang giúp tải trang nhanh hơn (Laravel tích hợp sẵn **phân trang** đơn giản và tiện dụng).
* **Quản trị:** Khu vực admin (nếu được triển khai) cho phép thêm/sửa/xóa danh mục, sản phẩm, quản lý đơn hàng và tài khoản người dùng.

## Cách cài đặt

1. **Yêu cầu hệ thống:** Cài đặt sẵn **PHP** (phiên bản 8.x trở lên; Laravel 11 yêu cầu tối thiểu PHP 8.2) và **Composer**, cùng một hệ quản trị cơ sở dữ liệu như MySQL/MariaDB.
2. **Clone repository:** Thực hiện lệnh `git clone https://github.com/LeHung1705/INTROWEAR.git` để tải mã nguồn về.
3. **Chuyển vào thư mục dự án:** `cd INTROWEAR`.
4. **Cài đặt thư viện:** Chạy lệnh `composer install` để cài đặt các package cần thiết.
5. **Tạo file cấu hình môi trường:** Sao chép file `.env.example` thành `.env` (lệnh: `cp .env.example .env`), sau đó chạy `php artisan key:generate` để tạo khóa ứng dụng.
6. **Cấu hình cơ sở dữ liệu:** Mở file `.env` vừa tạo và thiết lập các thông tin kết nối CSDL (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) theo môi trường của bạn.
7. **Chạy Migrations và Seed:** Chạy lệnh `php artisan migrate` để tạo các bảng trong cơ sở dữ liệu, sau đó chạy `php artisan db:seed` để ghi seed dữ liệu (nếu có).
8. **Khởi động ứng dụng:** Cuối cùng, chạy `php artisan serve` để khởi động server phát triển. Truy cập vào địa chỉ [http://localhost:8000](http://localhost:8000) (hoặc [http://127.0.0.1:8000](http://127.0.0.1:8000)) trên trình duyệt để xem website.

Nếu gặp lỗi liên quan đến quyền ghi thư mục, hãy đảm bảo thư mục `storage` và `bootstrap/cache` có quyền ghi phù hợp. Sau khi chạy lệnh `php artisan storage:link` (nếu dự án sử dụng), bạn đã sẵn sàng vận hành ứng dụng.

## Thông tin thêm (cấu trúc dự án)

* **`routes/web.php`**: File định nghĩa các route cho giao diện web. Các route này sẽ ánh xạ tới các Controller tương ứng để xử lý yêu cầu.
* **`app/Http/Controllers/`**: Chứa các Controller, là nơi tập trung logic xử lý các yêu cầu từ route (ví dụ: `ProductController` xử lý xem danh sách/sản phẩm).
* **`app/Models/`**: (Nếu có) Chứa các Model đại diện cho bảng CSDL (ví dụ: `Product`, `Category`).
* **`resources/views/`**: Thư mục chứa các file **view** (giao diện) sử dụng Blade template. Ví dụ, danh sách sản phẩm có thể nằm trong `resources/views/products.blade.php`.
* **`database/migrations/`**: Chứa các file migration để định nghĩa cấu trúc bảng trong cơ sở dữ liệu (mỗi file đặt tên theo timestamp).
* **`database/seeders/`**: (Nếu sử dụng) Chứa các lớp seed để thêm dữ liệu mẫu vào CSDL. Mặc định, `DatabaseSeeder` sẽ gọi các seeder khác khi chạy `php artisan db:seed`.

Đây là cấu trúc chung của một dự án Laravel. Khi cần tuỳ chỉnh hoặc mở rộng, bạn có thể tạo thêm Route mới, Controller mới (với `php artisan make:controller`), Model mới (với `php artisan make:model`), hoặc View mới (với `php artisan make:view` hoặc tạo file blade thủ công trong `resources/views`). Các lệnh **Artisan** (như `make:controller`, `migrate`, `db:seed`, v.v.) được Laravel cung cấp để hỗ trợ phát triển nhanh hơn.


