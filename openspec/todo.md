# Danh sách chức năng cần triển khai cho dự án QuanLyCho (Laravel 12)

Dựa trên tham khảo dự án khuyennongcantho (Next.js với Convex backend), đây là ứng dụng quản lý nông nghiệp với các chức năng chính như khảo sát, đơn vị, sản phẩm, thành viên, chợ, báo cáo. Chúng ta sẽ áp dụng tương tự cho ứng dụng quản lý chợ ở Cần Thơ, sử dụng Laravel 12 với Filament, Volt, Livewire.

## Thứ tự ưu tiên triển khai (từ cơ bản đến nâng cao)

## Việc cần làm

### 1. **Tạo ra các model, migration và seed data phù hợp với dự án**
- **Quy tắc chung**: Mọi bảng đều có các cột `active` (true/false để ẩn/hiện), `order` (sắp xếp), `notes` (ghi chú nếu cần), và `created_at`, `updated_at` (thời gian theo giờ Việt Nam).
- **User**: Bảng quản lý admin. Sử dụng để login vào `/admin` của Filament. Trường: name, email, password, phone, role, active, order, created_at, updated_at. Role = "admin" để truy cập tất cả. Index: by_email, by_active, by_role, by_order.
- **Sale**: Bảng quản lý người đi lấy giá. Trường: name, phone, email, password, address, note, active, order, created_at, updated_at. Được tạo và quản lý bởi User. Index: by_name, by_active, by_order.
- **Market**: Bảng quản lý chợ. Trường: name, address (chi tiết + province/district/ward), note, active, order, created_at, updated_at. Quan hệ many-to-many với Sale. Index: by_name, by_active, by_order.
- **Unit**: Bảng quản lý đơn vị đo lường. Trường: name, active, order, created_at, updated_at. Index: by_name, by_active, by_order.
- **Products**: Bảng quản lý sản phẩm. Trường: name, unitId, is_default (boolean để mark list chung 40-50 món, chỉ Admin edit/delete), note, active, order, created_at, updated_at. Seed data tạo list mặc định. Index: by_name, by_active, by_order, by_unitId, by_is_default.
- **Surveys**: Bảng quản lý khảo sát. Trường: marketId, saleId, surveyDay, note, active, order, created_at, updated_at. Gắn với chợ, Sale cập nhật price cho Products chung. Index: by_surveyDay, by_marketId, by_saleId, by_active, by_order.
- **SurveyItems**: Bảng item khảo sát. Trường: surveyId, productId, price, note, active, order, created_at, updated_at. Price per sản phẩm trong list chung, per survey. Index: by_surveyId, by_productId, by_active, by_order.
- **Reports**: Bảng quản lý báo cáo. Trường: fromDay, toDay, generatedAt, createdByAdminId, summaryRows (JSON), includedSurveyIds (array), order, active, created_at, updated_at. Index: by_generatedAt, by_from_to, by_active, by_order.
- **ReportItems**: Bảng item báo cáo. Trường: reportId, surveyId, productId, price, note, active, order, created_at, updated_at. Index: by_reportId, by_surveyId, by_productId, by_active, by_order.
- **Settings**: Bảng cài đặt. Trường: key="global", brand_name, logo_url, favicon_url, active, order, created_at, updated_at. Chỉ một hàng. Index: by_key, by_active, by_order.

### 2. **Viết các resource/page trong Filament admin panel**
- Tạo resource cho User: Chỉ role admin có thể view/edit profile, không tạo thêm user. Thêm tính năng reset password cho admin khi quên (qua email).
- Tạo resource cho Sale: CRUD đầy đủ (create, list, edit, delete), với form chọn Market (many-to-many). User cấp TK Sale. Thêm nút sinh mật khẩu mới để cấp cho sale nếu quên (admin gửi lại tài khoản).
- Tạo resource cho Market: CRUD, với form địa chỉ chi tiết.
- Tạo resource cho Unit: CRUD đơn giản.
- Tạo resource cho Products: CRUD, với filter is_default, chỉ role admin edit/delete Products chung.
- Tạo resource cho Surveys: View list và details, không edit/delete bởi Sale.
- Tạo resource cho Reports: View/generate báo cáo, với filter ngày và chợ.
- Tạo resource cho Settings: Chỉ một record global, edit brand/logo. Chỉ role admin truy cập.

### 3. **Viết trang login cho Sale để vào web lấy giá sản phẩm ở chợ**
- Tạo trang login riêng cho Sale tại `/khaosat` (không dùng Filament), sử dụng Volt + Flux UI.
- Form login với email/phone và password. Sale không có chức năng reset password tự động; nếu quên, phải nhờ admin gửi lại tài khoản (hoặc sinh mật khẩu mới).
- Sau login, redirect đến trang chủ `/khaosat` (dashboard đơn giản: chỉ nói đâu là trang nào với hướng dẫn đơn giản).
- Từ dashboard, Sale chọn ngày lấy giá, tạo đơn khảo sát (hiển thị tên Sale và thông tin chợ đã chọn), hiện list 40-50 sản phẩm để điền giá (responsiv, dễ dùng ở chợ).
- TK Sale do User cấp (ai cũng được, nhưng chỉ Admin đụng resource User và Settings).
- Thêm validation: required fields, check active Sale.
- Sử dụng Laravel Fortify hoặc custom auth cho Sale.

### 4. **Viết trang quản lý khảo sát giá cho Sale, xem lịch sử khảo sát**
- **Trang dashboard Sale**: Sau login, chọn chợ để bắt đầu khảo sát mới.
- **Trang khảo sát**: List 40-50 sản phẩm (từ Products is_default=true), form nhập price nhanh (sử dụng Volt để update real-time). UI tối ưu cho mobile/chợ: large buttons, auto-save, no refresh.
- **Trang lịch sử khảo sát**: List các survey đã làm, với filter by date/market, view details SurveyItems.
- **Yêu cầu UI**: Thân thiện, nhanh chóng – không cần bàn ghế, dùng dropdowns hoặc sliders cho price, confirm trước submit.

### 5. **Làm các trang tổng kết thống kê báo cáo**
- **Trang báo cáo admin**: Chọn khoảng ngày và chợ, generate báo cáo (summaryRows: tổng hợp giá trung bình, min/max).
- **Trang thống kê**: Charts (Flux UI) hiển thị trend giá per sản phẩm/chợ, export PDF/Excel.
- **Trang chi tiết báo cáo**: View ReportItems, filter by product.

## Ghi chú triển khai
- Sử dụng Filament v4 cho admin panel.
- Volt cho các trang cần interactivity.
- Flux UI cho components.
- Thiết lập Resend cho email: Đưa API key vào file .env và cấu hình gửi email cho ứng dụng.
- Pint để format code.
- Luôn test sau mỗi thay đổi với Pest.
- Đảm bảo list Products chung (is_default=true) không bị Sale edit/delete, chỉ Admin.
- Test với 40-50 products trong seed data để verify performance và UI.
