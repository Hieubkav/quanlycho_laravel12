@if (session('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('message') }}
    </div>
@endif
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Chào mừng, {{ auth('sale')->user()->name }}</h1>
            <p class="mt-2 text-lg text-gray-600">Trang khảo sát giá sản phẩm tại chợ</p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Khảo sát mới</dt>
                                <dd class="text-lg font-medium text-gray-900">Chọn ngay và chờ để bắt đầu</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="-ml-2 -mt-2 flex flex-wrap items-bottom">
                            <flux:button variant="primary" wire:click="startSurvey" class="ml-2 mt-2">Bắt đầu khảo sát</flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Lịch sử khảo sát</dt>
                                <dd class="text-lg font-medium text-gray-900">Xem các khảo sát đã thực hiện</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="-ml-2 -mt-2 flex flex-wrap items-bottom">
                            <flux:button variant="outline" wire:click="viewHistory" class="ml-2 mt-2">Xem lịch sử</flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Đăng xuất</dt>
                                <dd class="text-lg font-medium text-gray-900">Kết thúc phiên làm việc</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="-ml-2 -mt-2 flex flex-wrap items-bottom">
                            <flux:button variant="danger" wire:click="logout" class="ml-2 mt-2">Đăng xuất</flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($showSurveyForm)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tạo khảo sát mới</h3>
                <form wire:submit="createSurvey">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Ngày khảo sát</label>
                        <flux:input
                            wire:model="surveyDate"
                            type="date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                        @error('surveyDate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Chọn chợ</label>
                        <select wire:model="selectedMarket" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Chọn chợ --</option>
                            @foreach ($this->markets as $market)
                                <option value="{{ $market->id }}">{{ $market->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedMarket')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <flux:button type="button" wire:click="$set('showSurveyForm', false)" variant="outline">
                        Hủy
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                        Tạo khảo sát
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if ($currentSurvey)
    <div class="mt-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                Nhập giá sản phẩm - {{ $currentSurvey->market->name }} ({{ $currentSurvey->survey_day->toDateString() }})
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Điền giá cho các sản phẩm. Để trống nếu không có giá.
                </p>
            </div>
            <div class="border-t border-gray-200">
                @error('prices')
                    <div class="px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded mb-4">
                        {{ $message }}
                    </div>
                @enderror
                <form wire:submit="savePrices" class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->products as $product)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    {{ $product->name }} ({{ $product->unit->name ?? 'N/A' }})
                                </label>
                                <flux:input
                                    wire:model="prices.{{ $product->id }}"
                                    type="number"
                                    step="0.01"
                                    placeholder="Giá"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <flux:button type="button" wire:click="$set('currentSurvey', null); $set('prices', [])" variant="outline">
                        Hủy
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                        Lưu giá
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
