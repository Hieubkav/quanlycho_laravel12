# Project Context

## Purpose

Ứng dụng khảo sát giá nông sản nhằm thu thập và quản lý dữ liệu giá cả từ các chợ trên địa bàn. Hệ thống cho phép phân công nhân viên khảo sát (members) đến các chợ, nhập giá sản phẩm theo ngày, và tạo báo cáo tổng hợp để admin theo dõi biến động thị trường.

## Tech Stack

- PHP 8.2.12
- Laravel Framework v12
- Livewire v3
- Livewire Volt v1
- Flux UI Free v2
- Tailwind CSS v4
- Pest v3
- PHPUnit v11
- Laravel Fortify v1
- Laravel Prompts v0
- Laravel Sail v1
- Laravel MCP v0
- Laravel Pint v1

## Project Conventions

### Code Style

- Sử dụng Laravel Pint để format code trước khi commit.
- Tuân thủ PSR standards.
- Luôn sử dụng curly braces cho control structures, kể cả khi chỉ có một dòng.
- Sử dụng explicit return type declarations cho tất cả methods và functions.
- Sử dụng PHP 8 constructor property promotion.
- Ưu tiên PHPDoc blocks thay vì inline comments.
- Enum keys sử dụng TitleCase (ví dụ: FavoritePerson, Monthly).

### Architecture Patterns

- MVC pattern với Eloquent ORM.
- Sử dụng Livewire Volt cho các components interactive.
- Flux UI cho UI components, fallback về Blade nếu không có.
- Laravel Fortify cho authentication và authorization.
- Eager loading để tránh N+1 query problems.
- Form Request classes cho validation thay vì inline.
- Queued jobs với ShouldQueue cho operations tốn thời gian.
- Eloquent API Resources cho APIs.

### Testing Strategy

- Sử dụng Pest cho tất cả tests.
- Feature tests cho hầu hết trường hợp, unit tests khi cần.
- Test tất cả happy paths, failure paths, và edge cases.
- Chạy minimal number of tests trước khi finalize changes.
- Sử dụng factories và seeders cho models.
- Datasets trong Pest cho validation rules.

### Git Workflow

- Sử dụng GitHub flow: master branch cho production, feature branches cho development.
- Pull requests cho tất cả changes.
- Commit messages descriptive, sử dụng imperative mood.

## Domain Context

Hệ thống quản lý khảo sát giá nông sản phục vụ cho việc thu thập dữ liệu giá cả thực tế từ các chợ trên địa bàn. Các chức năng chính bao gồm:

### Cho nhân viên khảo sát (Members):
- Đăng nhập riêng biệt
- Xem danh sách chợ được phân công
- Tạo phiếu khảo sát mới cho chợ (copy từ phiếu trước nếu có)
- Nhập giá sản phẩm theo ngày (VND), ghi chú
- Theo dõi tiến độ nhập liệu
- Kích hoạt/gửi phiếu khảo sát
- Xem lịch sử khảo sát cá nhân

### Cho admin:
- Quản lý tài khoản members và admins
- Quản lý danh sách chợ (markets) với địa chỉ chi tiết
- Quản lý danh sách sản phẩm (products) và đơn vị đo (units)
- Phân công chợ cho members (assignments)
- Xem tất cả phiếu khảo sát, chỉnh sửa nếu cần
- Tạo báo cáo tổng hợp theo khoảng thời gian (từ ngày đến ngày)
- Xuất báo cáo Excel
- Theo dõi biến động giá qua thời gian

### Khác:
- Authentication riêng biệt cho admins và members
- Responsive design cho mobile/desktop
- Autosave khi nhập giá
- Validation dữ liệu giá (số dương)

## Important Constraints

- Phải sử dụng đúng versions của các packages như liệt kê.
- Không thay đổi application dependencies mà không có approval.
- Stick to existing directory structure.
- Sử dụng Laravel Boost tools khi available.
- Frontend bundling yêu cầu npm run build hoặc composer run dev nếu thấy changes không reflect.

## External Dependencies

Không có external APIs hoặc services bên ngoài được sử dụng hiện tại.
