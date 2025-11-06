<div class="overflow-x-auto">
    {{-- Header --}}
    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b-2 border-gray-300 dark:border-gray-600 pb-3 mb-2">
        <div class="grid grid-cols-[60px_minmax(200px,1fr)_100px_150px_minmax(150px,300px)] gap-3 px-2 min-w-[800px]">
            <div class="text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">STT</span>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Tên sản phẩm</span>
            </div>
            <div class="text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Đơn vị</span>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Giá (đ)</span>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Ghi chú</span>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="min-w-[800px]">
        @foreach ($products as $index => $product)
            @php
                $isEven = $index % 2 === 0;
                $bgClass = $isEven ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-900';
                $stt = $index + 1;
            @endphp

            <div class="grid grid-cols-[60px_minmax(200px,1fr)_100px_150px_minmax(150px,300px)] gap-3 items-center py-2.5 px-2 border-b border-gray-100 dark:border-gray-800 {{ $bgClass }}">
                {{-- STT --}}
                <div class="text-center">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $stt }}</span>
                </div>

                {{-- Tên sản phẩm --}}
                <div>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                </div>

                {{-- Đơn vị --}}
                <div class="flex justify-center">
                    <span class="inline-flex items-center shrink-0 rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                        {{ $product->unit->name }}
                    </span>
                </div>

                {{-- Giá --}}
                <div>
                    <x-filament::input.wrapper :valid="! $errors->has('data.prices.'.$product->id.'.price')">
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
                </div>

                {{-- Ghi chú --}}
                <div>
                    <textarea 
                        wire:model="data.prices.{{ $product->id }}.notes"
                        placeholder="Ghi chú..."
                        rows="1"
                        class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 sm:text-sm sm:leading-6"
                    ></textarea>
                </div>
            </div>
        @endforeach
    </div>
</div>
