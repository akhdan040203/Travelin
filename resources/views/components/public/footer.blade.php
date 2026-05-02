{{-- Premium Footer --}}
<footer class="bg-white border-t border-gray-50 pt-24 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-y-12 gap-x-8 md:gap-16 mb-20">
            {{-- Column 1: Branding --}}
            <div class="col-span-2 lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logoo3.png') }}" alt="Travelin Logo" class="w-full h-full object-contain rounded-full">
                    </div>
                    <span class="text-xl md:text-2xl font-black text-dark-900 tracking-tighter">Travel<span class="text-primary-500">in</span></span>
                </div>
                <p class="text-dark-300 text-xs md:text-sm leading-relaxed max-w-xl mb-8 font-medium">
                    Jelajahi keindahan dunia bersama Travelin. Kami menghadirkan pengalaman perjalanan yang tak terlupakan dengan paket wisata terbaik dan layanan premium di setiap destinasi pilihan Anda.
                </p>
            </div>

            {{-- Column 2: Support --}}
            <div class="col-span-1">
                <h4 class="text-[10px] font-black text-dark-900 uppercase tracking-[0.2em] mb-6">Support</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Support Center</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">FAQs</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Troubleshooting</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Feedback</a></li>
                </ul>
            </div>

            {{-- Column 3: Company --}}
            <div class="col-span-1">
                <h4 class="text-[10px] font-black text-dark-900 uppercase tracking-[0.2em] mb-6">Company</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">About Us</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Careers</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Blog</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Contact</a></li>
                </ul>
            </div>

            {{-- Column 4: Legal --}}
            <div class="col-span-1">
                <h4 class="text-[10px] font-black text-dark-900 uppercase tracking-[0.2em] mb-6">Legal</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Privacy Policy</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Terms of Service</a></li>
                    <li><a href="#" class="text-dark-300 hover:text-primary-500 text-xs font-bold transition-all">Cookie Policy</a></li>
                </ul>
            </div>
        </div>

        {{-- Social Icons --}}
        <div class="flex justify-end gap-3 mb-12">
            @php $socials = ['twitter', 'facebook', 'linkedin', 'instagram']; @endphp
            @foreach($socials as $social)
                <a href="#" class="w-10 h-10 border border-gray-100 rounded-full flex items-center justify-center text-dark-300 hover:border-dark-900 hover:text-dark-900 transition-all group">
                    <span class="sr-only">{{ $social }}</span>
                    <div class="w-4 h-4 bg-current"></div> {{-- Placeholder for icons --}}
                </a>
            @endforeach
        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-8 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[10px] font-bold text-dark-200 uppercase tracking-widest">
                Copyright © Travelin {{ date('Y') }}
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-[10px] font-bold text-dark-200 uppercase tracking-widest hover:text-dark-900 transition-all">Privacy Policy</a>
                <a href="#" class="text-[10px] font-bold text-dark-200 uppercase tracking-widest hover:text-dark-900 transition-all">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>
