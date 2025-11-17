<footer class="bg-gray-800 text-white mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-xl font-bold mb-4">Về Chúng Tôi</h3>
                <p class="text-gray-300">
                    Website tin tức công nghệ hàng đầu Việt Nam, cập nhật tin tức mới nhất về Laravel, PHP và công nghệ web.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-xl font-bold mb-4">Liên Kết</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Trang Chủ</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Về Chúng Tôi</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Liên Hệ</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white">Chính Sách</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h3 class="text-xl font-bold mb-4">Liên Hệ</h3>
                <ul class="space-y-2 text-gray-300">
                    <li>Email: contact@example.com</li>
                    <li>Phone: 0123 456 789</li>
                    <li>Address: Hà Nội, Việt Nam</li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
