@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('error') }}
    </div>
@endif

@if (session('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('message') }}
    </div>
@endif

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Tạo khảo sát mới</h1>
            <p class="mt-2 text-lg text-gray-600">Chọn ngày và chợ để bắt đầu khảo sát giá sản phẩm</p>
        </div>

        @if (!$currentSurvey)
            <div class="mt-10 max-w-2xl mx-auto bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Thông tin khảo sát</h3>
                    <p class="mt-1 text-sm text-gray-600">Điền thông tin để tạo khảo sát mới</p>
                </div>
                <form wire:submit="createSurvey" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="surveyDate" class="block text-sm font-medium text-gray-900 mb-2">Ngày khảo sát</label>
                            <input
                                wire:model="surveyDate"
                                type="date"
                                id="surveyDate"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            />
                            @error('surveyDate')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="selectedMarket" class="block text-sm font-medium text-gray-900 mb-2">Chọn chợ</label>
                            <select wire:model="selectedMarket" id="selectedMarket" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option value="">-- Chọn chợ --</option>
                                @foreach ($this->markets as $market)
                                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedMarket')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-x-3">
                        <button type="button" wire:click="cancel" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                            Hủy
                        </button>
                        <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Tạo khảo sát
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-10 max-w-4xl mx-auto bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Nhập giá sản phẩm - {{ $currentSurvey->market->name }} ({{ $currentSurvey->survey_day->toDateString() }})
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Điền giá cho các sản phẩm. Để trống nếu không có giá.
                    </p>
                </div>
                <div class="p-6">
                    @error('prices')
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror
                    <form wire:submit="savePrices" class="space-y-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($this->products as $product)
                                <div>
                                    <label for="price-{{ $product->id }}" class="block text-sm font-medium text-gray-900 mb-2">
                                        {{ $product->name }} ({{ $product->unit->name ?? 'N/A' }})
                                    </label>
                                    <input
                                        wire:model="prices.{{ $product->id }}"
                                        type="number"
                                        step="0.01"
                                        placeholder="Nhập giá"
                                        id="price-{{ $product->id }}"
                                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    />
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-end gap-x-3">
                            <button type="button" wire:click="cancel" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                Hủy
                            </button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                                Lưu giá
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
