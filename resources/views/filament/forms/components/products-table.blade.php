<div class="overflow-x-auto">
    <table class="w-full min-w-[960px] text-sm">
        <thead class="sticky top-0 z-10 bg-white/95 dark:bg-gray-900/95 backdrop-blur border-b border-gray-200 dark:border-gray-700">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                <th class="py-3 px-3 text-center w-14">STT</th>
                <th class="py-3 px-3">Ten san pham</th>
                <th class="py-3 px-3 text-center w-28">Don vi</th>
                <th class="py-3 px-3 w-40">Gia (d)</th>
                <th class="py-3 px-3 w-[260px]">Ghi chu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($products as $index => $product)
                @php
                    $stt = $index + 1;
                @endphp
                <tr wire:key="product-{{ $product->id }}" class="bg-white odd:bg-gray-50 dark:bg-gray-900 dark:odd:bg-gray-800/70">
                    <td class="py-2.5 px-3 text-center align-middle">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $stt }}</span>
                    </td>
                    <td class="py-2.5 px-3 align-middle">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                    </td>
                    <td class="py-2.5 px-3 align-middle">
                        <span class="mx-auto inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                            {{ $product->unit->name }}
                        </span>
                    </td>
                    <td class="py-2.5 px-3 align-middle">
                        <x-filament::input.wrapper :valid="! $errors->has('data.prices.'.$product->id.'.price')">
                            <x-filament::input
                                type="number"
                                wire:model="data.prices.{{ $product->id }}.price"
                                placeholder="0"
                                min="0"
                                step="0.01"
                            />
                            <x-slot name="suffix">
                                <span class="text-sm text-gray-500">d</span>
                            </x-slot>
                        </x-filament::input.wrapper>
                    </td>
                    <td class="py-2.5 px-3 align-middle">
                        <textarea
                            wire:model="data.prices.{{ $product->id }}.notes"
                            placeholder="Ghi chu..."
                            rows="1"
                            class="block w-full min-h-[2.5rem] resize-none rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500"
                        ></textarea>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
