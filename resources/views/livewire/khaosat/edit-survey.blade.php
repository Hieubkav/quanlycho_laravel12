@if (session('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('message') }}
    </div>
@endif

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa khảo sát</h1>
            <p class="mt-2 text-lg text-gray-600">{{ $survey->market->name }} - {{ $survey->survey_day->toDateString() }}</p>
        </div>

        <div class="mt-10 bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Cập nhật giá sản phẩm</h3>
                <p class="mt-1 text-sm text-gray-600">Điền giá cho các sản phẩm. Để trống nếu không có giá.</p>
            </div>
            <div class="p-6">
                @error('prices')
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror
                <form wire:submit="updatePrices" class="space-y-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sản phẩm
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Giá (VND)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($this->products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $product->unit->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                wire:model="prices.{{ $product->id }}"
                                                type="number"
                                                step="0.01"
                                                placeholder="0"
                                                class="w-32 py-2 px-3 border border-gray-300 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end gap-x-3">
                        <button type="button" wire:click="back" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                            Quay lại
                        </button>
                        <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Cập nhật giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
