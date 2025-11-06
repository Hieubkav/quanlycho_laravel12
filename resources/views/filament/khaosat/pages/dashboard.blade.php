<x-filament-panels::page>
    {{-- Hướng dẫn nhanh --}}
    <div class="mb-6">
        <x-filament::section 
            icon="heroicon-o-light-bulb"
            icon-color="warning"
            class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20"
        >
            <x-slot name="heading">
                📌 Hướng dẫn sử dụng
            </x-slot>
            
            <div class="prose dark:prose-invert max-w-none">
                <div class="grid gap-3 text-sm">
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-primary-500 text-white font-bold text-xs">1</span>
                        <p class="m-0"><strong>Tạo khảo sát mới:</strong> Nhấn "Khảo Sát" ở menu bên trái → Nhấn nút "Tạo" → Chọn chợ và ngày → Nhập giá sản phẩm</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-primary-500 text-white font-bold text-xs">2</span>
                        <p class="m-0"><strong>Xem lại khảo sát:</strong> Vào "Khảo Sát" → Nhấn "Xem" hoặc "Sửa" ở từng dòng</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-primary-500 text-white font-bold text-xs">3</span>
                        <p class="m-0"><strong>Xem danh sách chợ & sản phẩm:</strong> Dùng menu bên trái để xem thông tin</p>
                    </div>
                </div>
            </div>
        </x-filament::section>
    </div>

    {{-- Thống kê nhanh --}}
    <div class="grid gap-4 md:grid-cols-3 mb-6">
        <x-filament::section>
            <div class="text-center py-4">
                <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                    {{ auth()->guard('sale')->user()->markets()->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Chợ được phân công</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center py-4">
                <div class="text-4xl font-bold text-success-600 dark:text-success-400 mb-2">
                    {{ auth()->guard('sale')->user()->surveys()->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Tổng số khảo sát</div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-center py-4">
                <div class="text-4xl font-bold text-warning-600 dark:text-warning-400 mb-2">
                    {{ auth()->guard('sale')->user()->surveys()->whereMonth('survey_day', now()->month)->count() }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Khảo sát tháng này</div>
            </div>
        </x-filament::section>
    </div>

    {{-- Danh sách chợ được phân công --}}
    <x-filament::section 
        icon="heroicon-o-building-storefront"
        collapsible
        collapsed
    >
        <x-slot name="heading">
            🏪 Danh sách chợ được phân công
        </x-slot>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse(auth()->guard('sale')->user()->markets as $market)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <x-filament::icon 
                            icon="heroicon-m-building-storefront"
                            class="w-6 h-6 text-primary-500"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $market->name }}</p>
                        @if($market->address)
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $market->address }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                    <x-filament::icon 
                        icon="heroicon-o-information-circle"
                        class="w-12 h-12 mx-auto mb-3 text-gray-400"
                    />
                    <p>Bạn chưa được phân công vào chợ nào. Vui lòng liên hệ quản trị viên.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>
