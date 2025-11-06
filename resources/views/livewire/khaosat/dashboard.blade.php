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
        <!-- Card 1: Khảo sát mới -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="p-6">
        <div class="flex items-center mb-4">
        <div class="flex-shrink-0">
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
        </div>
        </div>
        <div class="ml-4">
        <h3 class="text-lg font-semibold text-gray-900">Khảo sát mới</h3>
            <p class="text-sm text-gray-600">Chọn ngay và chờ để bắt đầu</p>
            </div>
        </div>
        <button wire:click="startSurvey" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
        Bắt đầu khảo sát
        </button>
        </div>
        </div>

            <!-- Card 2: Lịch sử khảo sát -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="p-6">
        <div class="flex items-center mb-4">
        <div class="flex-shrink-0">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="ml-4">
        <h3 class="text-lg font-semibold text-gray-900">Lịch sử khảo sát</h3>
        <p class="text-sm text-gray-600">Xem các khảo sát đã thực hiện</p>
        </div>
        </div>
        <button wire:click="viewHistory" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
        Xem lịch sử
        </button>
        </div>
        </div>

        <!-- Card 3: Đăng xuất -->
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
            <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            </div>
        </div>
        <div class="ml-4">
        <h3 class="text-lg font-semibold text-gray-900">Đăng xuất</h3>
        <p class="text-sm text-gray-600">Kết thúc phiên làm việc</p>
        </div>
        </div>
        <button wire:click="logout" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
            Đăng xuất
        </button>
        </div>
        </div>
        </div>
    </div>
</div>




