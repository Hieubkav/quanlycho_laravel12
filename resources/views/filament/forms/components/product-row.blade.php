@php
    $bgClass = $isEven ? 'bg-gray-50/50 dark:bg-gray-800/30' : 'bg-white dark:bg-gray-900';
@endphp

<div class="grid grid-cols-[50px_1fr_100px_150px_200px] gap-3 items-start py-2.5 px-2 border-b border-gray-100 dark:border-gray-800 {{ $bgClass }}">
    {{-- STT --}}
    <div class="text-center pt-2">
        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $stt }}</span>
    </div>

    {{-- Tên sản phẩm --}}
    <div class="pt-2">
        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
    </div>

    {{-- Đơn vị --}}
    <div class="flex justify-center pt-2">
        <span class="inline-flex items-center shrink-0 rounded-md bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
            {{ $product->unit->name }}
        </span>
    </div>

    {{-- Giá --}}
    <div>
        <x-filament::input.wrapper :valid="true">
            <x-filament::input
                type="number"
                wire:model="data.prices.{{ $productId }}.price"
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
            wire:model="data.prices.{{ $productId }}.notes"
            placeholder="..."
            rows="1"
            class="block w-full rounded-lg border-gray-300 shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 sm:text-sm sm:leading-6"
        ></textarea>
    </div>
</div>
