## Context
Đây là database schema cho ứng dụng quản lý chợ, nơi Sale đi khảo sát giá sản phẩm tại các Market, và tạo reports tổng hợp.

## Goals / Non-Goals
- Goals: Hoàn chỉnh database schema với relationships, indexes, seed data
- Non-Goals: Frontend UI, business logic, APIs (chỉ database layer)

## Decisions
- Decision: Sử dụng Laravel Eloquent với foreign keys và indexes tối ưu
- Alternatives considered: Raw SQL vs ORM - Chọn Eloquent vì consistency với Laravel ecosystem

## Risks / Trade-offs
- Risk: Complex relationships có thể gây N+1 queries -> Mitigation: Eager loading trong code
- Risk: Large seed data -> Mitigation: Chia thành multiple seeders

## Migration Plan
Run `php artisan migrate` và `php artisan db:seed` sau khi implementation

## Open Questions
- Cần thêm composite indexes cho queries phức tạp?
