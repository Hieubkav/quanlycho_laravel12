<div class="overflow-x-auto">
    <table class="w-full min-w-[960px] text-sm border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <thead class="sticky top-0 z-10 bg-white/95 dark:bg-gray-900/95 backdrop-blur">
            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 divide-x divide-gray-200 dark:divide-gray-700">
                <th class="py-3 px-4 text-center w-16">STT</th>
                <th class="py-3 px-4 text-left">Tên sản phẩm</th>
                <th class="py-3 px-4 text-center w-32">Đơn vị</th>
                <th class="py-3 px-4 w-44 text-left">Giá (đ)</th>
                <th class="py-3 px-4 w-[280px] text-left">Ghi chú</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($products as $index => $product)
                @php
                    $stt = $index + 1;
                @endphp
                <tr
                    wire:key="product-{{ $product->id }}"
                    class="divide-x divide-gray-100 dark:divide-gray-800 hover:bg-gray-50/80 dark:hover:bg-gray-800/70 transition-colors"
                >
                    <td class="py-3 px-4 text-center align-middle">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $stt }}</span>
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                    </td>
                    <td class="py-3 px-4 text-center align-middle">
                        <span class="inline-flex items-center justify-center rounded-md bg-green-50 px-3 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                            {{ $product->unit->name }}
                        </span>
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <x-filament::input.wrapper :valid="! $errors->has('data.prices.'.$product->id.'.price')" class="w-full">
                            <x-filament::input
                                type="number"
                                wire:model="data.prices.{{ $product->id }}.price"
                                placeholder="0"
                                min="0"
                                step="0.01"
                            />
                            <x-slot name="suffix">
                                <span class="text-sm text-gray-500">đ</span>
                            </x-slot>
                        </x-filament::input.wrapper>
                    </td>
                    <td class="py-3 px-4 align-middle">
                        <textarea
                            wire:model="data.prices.{{ $product->id }}.notes"
                            placeholder="Ghi chú..."
                            rows="1"
                            class="block w-full min-h-[2.75rem] resize-none rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500"
                        ></textarea>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
