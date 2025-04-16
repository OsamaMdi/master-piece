<x-app-layout>
    <div class="relative min-h-screen flex items-center justify-center bg-gray-100">

        <!-- طبقة الغباش فوق كل الصفحة -->
        <div class="absolute inset-0 bg-white/50 backdrop-blur-sm z-0"></div>

        <!-- الكارد -->
        <div class="relative max-w-3xl w-full bg-white overflow-hidden shadow-xl rounded-2xl p-10 text-center animate-fade-in border-4"
             style="border-color: #3b82f6; z-index: 10;">

            <h1 class="text-4xl font-extrabold text-gray-800 mb-6 animate-slide-down">
                ⚠️ You Are Already Logged In!
            </h1>

            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                You are already logged into your account. <br>
                If you want to login with another account, please logout first.
            </p>

            <div class="flex justify-center gap-4">
                <!-- زر Go Back -->
                <a href="javascript:history.back()"
                   class="inline-block px-8 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 hover:scale-105 transform transition-all duration-300">
                    Go Back
                </a>

                <!-- زر Logout -->
                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="inline-block px-8 py-3 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 hover:scale-105 transform transition-all duration-300">
                    Logout
                </a>
            </div>

            <!-- فورم تسجيل الخروج -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

        </div>

    </div>

    <!-- Custom Animations -->
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        .animate-slide-down {
            animation: slideDown 1s ease-out forwards;
        }
    </style>
</x-app-layout>
