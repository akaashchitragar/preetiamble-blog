<nav class="bg-[#fff9ee]/95 backdrop-blur-md top-0 sticky z-50 w-full" x-data="{ open: false }">
    <div class="flex justify-between items-center w-full px-5 md:px-8 py-4 max-w-7xl mx-auto border-b border-[#DDD0BC]">

        <a class="font-headline italic text-xl md:text-2xl text-[#2C1E0F]" href="/">Preeti Amble</a>

        <!-- Desktop links -->
        <div class="hidden md:flex items-center space-x-8">
            <a class="font-body text-lg tracking-tight text-[#8c4a24] font-semibold border-b-2 border-[#8c4a24] transition-colors duration-300" href="/">Blogs</a>
            <a class="font-body text-lg tracking-tight text-[#564331] hover:text-[#8c4a24] transition-colors duration-300" href="#">About</a>
        </div>

        <div class="flex items-center gap-4">
            <!-- Letters pill — always visible -->
            <a class="bg-[#aa623a] text-[#fffbff] px-5 py-2 rounded-full font-label text-xs md:text-sm tracking-wide uppercase font-semibold transition-all duration-300 hover:bg-[#8c4a24]" href="#">
                Letters
            </a>
            <!-- Hamburger — mobile only -->
            <button class="md:hidden flex flex-col gap-1.5 p-1" @click="open = !open" aria-label="Toggle menu">
                <span class="block w-5 h-0.5 bg-[#2C1E0F] transition-all" :class="open ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="block w-5 h-0.5 bg-[#2C1E0F] transition-all" :class="open ? 'opacity-0' : ''"></span>
                <span class="block w-5 h-0.5 bg-[#2C1E0F] transition-all" :class="open ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden overflow-hidden transition-all duration-300 bg-[#fff9ee] border-b border-[#DDD0BC]"
         :class="open ? 'max-h-40' : 'max-h-0'">
        <div class="flex flex-col px-5 py-4 gap-4">
            <a class="font-body text-base text-[#8c4a24] font-semibold" href="/">Blogs</a>
            <a class="font-body text-base text-[#564331] hover:text-[#8c4a24] transition-colors" href="#">About</a>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
