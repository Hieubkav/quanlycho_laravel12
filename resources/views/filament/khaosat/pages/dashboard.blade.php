<x-filament-panels::page>
    <div class="grid gap-4 md:gap-6 lg:grid-cols-2">
        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Thông tin của bạn</h2>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Họ tên:</dt>
                        <dd class="text-gray-900 dark:text-white">{{ auth()->guard('sale')->user()->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Email:</dt>
                        <dd class="text-gray-900 dark:text-white">{{ auth()->guard('sale')->user()->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Số điện thoại:</dt>
                        <dd class="text-gray-900 dark:text-white">{{ auth()->guard('sale')->user()->phone }}</dd>
                    </div>
                </dl>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Thống kê</h2>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Số chợ được phân công:</dt>
                        <dd class="text-gray-900 dark:text-white">{{ auth()->guard('sale')->user()->markets()->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Tổng số khảo sát:</dt>
                        <dd class="text-gray-900 dark:text-white">{{ auth()->guard('sale')->user()->surveys()->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-600 dark:text-gray-400">Khảo sát tháng này:</dt>
                        <dd class="text-gray-900 dark:text-white">
                            {{ auth()->guard('sale')->user()->surveys()->whereMonth('survey_day', now()->month)->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </x-filament::card>
    </div>

    <div class="mt-6">
        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Danh sách chợ được phân công</h2>
                <ul class="space-y-2">
                    @forelse(auth()->guard('sale')->user()->markets as $market)
                        <li class="flex items-center gap-2">
                            <x-filament::icon 
                                icon="heroicon-m-building-storefront"
                                class="w-5 h-5 text-primary-500"
                            />
                            <span>{{ $market->name }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500">Bạn chưa được phân công vào chợ nào.</li>
                    @endforelse
                </ul>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
