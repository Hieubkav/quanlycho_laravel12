## Why
Để cho phép nhân viên sale (sales personnel) có thể truy cập vào hệ thống để thực hiện khảo sát giá sản phẩm trực tiếp tại chợ, cải thiện hiệu quả thu thập dữ liệu thị trường và hỗ trợ quản lý giá cả kịp thời.

## What Changes
- Thêm trang login riêng cho Sale tại `/khaosat` (không sử dụng Filament), sử dụng Volt + Flux UI.
- Form login với email/phone và password, validation cho required fields và check active Sale.
- Sau login, redirect đến dashboard `/khaosat` với hướng dẫn đơn giản về cách sử dụng.
- Từ dashboard, Sale chọn ngày lấy giá, tạo đơn khảo sát (hiển thị tên Sale và thông tin chợ đã chọn), hiển thị list 40-50 sản phẩm để điền giá (responsive, dễ dùng ở chợ).
- Sale không có chức năng reset password tự động; nếu quên, phải nhờ admin gửi lại tài khoản hoặc sinh mật khẩu mới.
- Sử dụng Laravel Fortify hoặc custom auth cho Sale.
- TK Sale do User (admin) cấp, chỉ Admin đụng resource User và Settings.

## Impact
- Affected specs: Thêm mới capability `sale-survey-access` (không ảnh hưởng đến specs hiện có `admin` hoặc `market-management`).
- Affected code: Thêm routes mới, Volt components, authentication logic, survey creation logic, validation. Dự kiến ảnh hưởng đến `routes/web.php`, `app/Livewire/Volt/`, `config/auth.php`, và database queries cho Sale và Survey.
- Không breaking changes: Chỉ thêm tính năng mới, không thay đổi hành vi hiện có.
