## Why
Dự án cần có cơ sở dữ liệu hoàn chỉnh để quản lý chợ, bao gồm users, sales, markets, products, surveys, reports. Hiện tại chỉ có admin panel access, thiếu schema cho dữ liệu kinh doanh.

## What Changes
- Thêm capability "market-management" với database schema hoàn chỉnh
- Tạo models, migrations, relationships cho toàn bộ hệ thống quản lý chợ
- Seed data mặc định cho units, products, settings
- **BREAKING**: Thay đổi User model để thêm role, active, phone, etc.

## Impact
- Affected specs: Thêm mới market-management
- Affected code: Tất cả models, migrations, seeders
- Không ảnh hưởng đến existing admin panel functionality
