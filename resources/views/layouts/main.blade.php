<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Travelin - Jelajahi keindahan Indonesia bersama kami. Booking paket wisata terbaik dengan harga terjangkau.')">

    <title>@yield('title', 'Travelin - Jelajahi Indonesia')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/travelin-mark-transparent.png') }}?v={{ filemtime(public_path('images/travelin-mark-transparent.png')) }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">



    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-dark-900">
    {{-- Navbar --}}
    @include('components.public.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.public.footer')

    @livewireScripts

    <script>


        // Navbar scroll effect
        function updateNavbarState() {
            const navbar = document.getElementById('navbar');
            if (!navbar) return;

            const shell = document.getElementById('navbar-shell');
            const logo = document.getElementById('logo-text');
            const isHeroPage = navbar.dataset.transparent === 'true';

            if (window.scrollY > 50) {
                shell?.classList.add('bg-white/85', 'border-gray-100');
                shell?.classList.remove('bg-white/10', 'border-white/20');
                document.querySelectorAll('.nav-link-hero').forEach(el => {
                    el.classList.remove('text-white');
                    el.classList.add('text-dark-900');
                });
                if (logo) {
                    logo.classList.remove('text-white');
                    logo.classList.add('text-dark-900');
                }
            } else {
                if (isHeroPage) {
                    shell?.classList.remove('bg-white/85', 'border-gray-100');
                    shell?.classList.add('bg-white/10', 'border-white/20');
                    document.querySelectorAll('.nav-link-hero').forEach(el => {
                        el.classList.add('text-white');
                        el.classList.remove('text-dark-900');
                    });
                    if (logo) {
                        logo.classList.add('text-white');
                        logo.classList.remove('text-dark-900');
                    }
                } else {
                    shell?.classList.add('bg-white/85', 'border-gray-100');
                    shell?.classList.remove('bg-white/10', 'border-white/20');
                    document.querySelectorAll('.nav-link-hero').forEach(el => {
                        el.classList.remove('text-white');
                        el.classList.add('text-dark-900');
                    });
                    if (logo) {
                        logo.classList.remove('text-white');
                        logo.classList.add('text-dark-900');
                    }
                }
            }
        }

        window.addEventListener('scroll', updateNavbarState, { passive: true });
        window.addEventListener('DOMContentLoaded', updateNavbarState);
        updateNavbarState();

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const path = document.getElementById('hamburger-svg-path');
            
            menu.classList.toggle('hidden');
            
            if (!menu.classList.contains('hidden')) {
                path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            } else {
                path.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            }
        });

        function updateWishlistBadges(count) {
            document.querySelectorAll('.js-wishlist-badge').forEach(badge => {
                badge.textContent = count > 9 ? '9+' : count;
                badge.classList.toggle('hidden', count <= 0);
            });

            document.querySelectorAll('.js-wishlist-nav-icon').forEach(icon => {
                icon.classList.toggle('text-red-500', count > 0);
                icon.setAttribute('fill', count > 0 ? 'currentColor' : 'none');
            });
        }

        function setWishlistButtonState(form, wishlisted) {
            const button = form.querySelector('.js-wishlist-button');
            const icon = form.querySelector('.js-wishlist-icon');
            const label = form.querySelector('.js-wishlist-label');

            if (icon) {
                icon.setAttribute('fill', wishlisted ? 'currentColor' : 'none');
            }

            if (label) {
                label.textContent = wishlisted ? 'Tersimpan' : 'Tambah Wishlist';
            }

            if (button && label) {
                button.classList.toggle('bg-red-500', wishlisted);
                button.classList.toggle('text-white', wishlisted);
                button.classList.toggle('shadow-red-500/25', wishlisted);
                button.classList.toggle('hover:bg-red-600', wishlisted);
                button.classList.toggle('bg-white', !wishlisted);
                button.classList.toggle('text-dark-900', !wishlisted);
                button.classList.toggle('hover:bg-red-50', !wishlisted);
                button.classList.toggle('hover:text-red-500', !wishlisted);
            }
        }

        function animateWishlistFly(source) {
            const target = document.querySelector('.js-wishlist-nav-target') || document.querySelector('.js-wishlist-nav-icon');
            if (!source || !target) return;

            const sourceRect = source.getBoundingClientRect();
            const targetRect = target.getBoundingClientRect();
            const startX = sourceRect.left + sourceRect.width / 2;
            const startY = sourceRect.top + sourceRect.height / 2;
            const endX = targetRect.left + targetRect.width / 2;
            const endY = targetRect.top + targetRect.height / 2;

            const flyer = document.createElement('div');
            flyer.innerHTML = `
                <svg class="w-5 h-5 text-red-500 drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            `;
            flyer.style.position = 'fixed';
            flyer.style.left = `${startX - 10}px`;
            flyer.style.top = `${startY - 10}px`;
            flyer.style.zIndex = '140';
            flyer.style.pointerEvents = 'none';
            flyer.style.transition = 'transform 650ms cubic-bezier(0.22, 1, 0.36, 1), opacity 650ms ease';
            flyer.style.transform = 'translate3d(0, 0, 0) scale(1)';
            flyer.style.opacity = '1';
            document.body.appendChild(flyer);

            requestAnimationFrame(() => {
                flyer.style.transform = `translate3d(${endX - startX}px, ${endY - startY}px, 0) scale(0.45)`;
                flyer.style.opacity = '0.15';
            });

            window.setTimeout(() => {
                flyer.remove();
                target.classList.add('scale-110');
                window.setTimeout(() => target.classList.remove('scale-110'), 180);
            }, 680);
        }

        document.addEventListener('submit', async event => {
            const form = event.target;
            if (!form.matches('.js-wishlist-form')) return;

            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            button?.setAttribute('disabled', 'disabled');
            animateWishlistFly(button);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                if (!response.ok) throw new Error('Wishlist request failed');

                const data = await response.json();
                document.querySelectorAll(`.js-wishlist-form[data-destination-id="${form.dataset.destinationId}"]`).forEach(item => {
                    setWishlistButtonState(item, data.wishlisted);
                });
                updateWishlistBadges(data.count);
            } catch (error) {
                form.submit();
            } finally {
                button?.removeAttribute('disabled');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
