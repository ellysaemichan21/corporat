<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felix Elite Garment Care | B2B Luxury Laundry</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #09090b; /* zinc-950 */
            color: #fafafa; /* zinc-50 */
        }
        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .glass-nav {
            background: rgba(9, 9, 11, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .luxury-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #aa8529 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .luxury-bg {
            background: linear-gradient(135deg, #d4af37 0%, #aa8529 100%);
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        
        .scroll-animate { opacity: 0; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col selection:bg-amber-600 selection:text-white relative">
    
    <!-- Ambient Golden Particles -->
    <canvas id="luxury-particles" class="fixed inset-0 pointer-events-none z-40 opacity-80"></canvas>

    <!-- Sticky Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0 -ml-2 md:-ml-0">
                    <a href="{{ route('public.landing') }}" class="flex items-center">
                        <img src="{{ asset('test/images/logo.png') }}" alt="Logo" class="h-8 md:h-10 max-w-[250px] object-contain opacity-90 drop-shadow-md transition-transform hover:scale-105">
                    </a>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="ml-10 flex items-center space-x-6">
                        <a href="{{ route('public.landing') }}" class="hover:text-amber-400 text-zinc-300 px-3 py-2 rounded-md text-sm font-medium transition-colors tracking-wide uppercase">{{ __('Home') }}</a>
                        <a href="{{ url('/instructions') }}" class="hover:text-amber-400 text-zinc-300 px-3 py-2 rounded-md text-sm font-medium transition-colors tracking-wide uppercase">{{ __('How It Works') }}</a>
                        <a href="{{ url('/order') }}" class="magnetic-btn inline-block luxury-bg text-zinc-950 px-5 py-2.5 rounded-sm text-sm font-bold tracking-wide uppercase shadow-[0_0_15px_rgba(212,175,55,0.3)]">{{ __('Book Service') }}</a>
                        
                        <!-- Language Switcher -->
                        <div class="flex items-center gap-2 border-l border-zinc-800 pl-6 ml-2">
                            <a href="{{ route('lang.switch', 'en') }}" class="text-xs font-bold {{ App::getLocale() === 'en' ? 'text-amber-500' : 'text-zinc-500 hover:text-zinc-300' }}">EN</a>
                            <span class="text-zinc-700 text-xs">/</span>
                            <a href="{{ route('lang.switch', 'id') }}" class="text-xs font-bold {{ App::getLocale() === 'id' ? 'text-amber-500' : 'text-zinc-500 hover:text-zinc-300' }}">ID</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <!-- Corporate Footer -->
    <footer class="border-t border-zinc-800 bg-zinc-950 pt-16 pb-8 mt-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-8">
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('test/images/logo.png') }}" alt="Felix Logo" class="h-8 md:h-10 max-w-[200px] object-contain mb-6 opacity-90">
                    <p class="text-zinc-400 text-sm leading-relaxed max-w-md">
                        {{ __("Elevating the standard of living for premier B2B apartment contracts. We provide uncompromising attention to detail, structural garment restoration, and white-glove concierge delivery to your private residence.") }}
                    </p>
                </div>
                <div>
                    <h3 class="text-zinc-100 text-sm font-semibold uppercase tracking-wider mb-4">{{ __('Company') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('About Us') }}</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('Partner Program') }}</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('Sustainability') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-zinc-100 text-sm font-semibold uppercase tracking-wider mb-4">{{ __('Legal') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('Terms of Service') }}</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="#" class="text-zinc-400 hover:text-amber-400 text-sm transition-colors">{{ __('Liability Agreement') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-16 pt-8 border-t border-zinc-900 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('test/images/logo.png') }}" alt="Felix Logo" class="h-5 md:h-6 w-auto opacity-70">
                    <p class="text-zinc-500 text-sm">
                        &copy; {{ date('Y') }} {{ __('All rights reserved.') }}
                    </p>
                </div>
                <div class="flex space-x-6">
                    <span class="text-zinc-600 text-sm">{{ __('Exclusively B2B') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Scroll Animations (Fade In Up)
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });
            
            document.querySelectorAll('.scroll-animate').forEach((el) => {
                observer.observe(el);
            });

            // 2. Ambient Golden Particles
            const canvas = document.getElementById('luxury-particles');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let particlesArray;
                
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                
                class Particle {
                    constructor() {
                        this.x = Math.random() * canvas.width;
                        this.y = Math.random() * canvas.height;
                        this.size = Math.random() * 2.5 + 1; // Bigger size
                        this.speedX = Math.random() * 0.4 - 0.2;
                        this.speedY = Math.random() * 0.6 - 0.6; // Drift up faster
                    }
                    update() {
                        this.x += this.speedX;
                        this.y += this.speedY;
                        if (this.x < 0 || this.x > canvas.width || this.y < 0) {
                            this.x = Math.random() * canvas.width;
                            this.y = canvas.height;
                            this.size = Math.random() * 2.5 + 1;
                        }
                    }
                    draw() {
                        ctx.fillStyle = 'rgba(212, 175, 55, 0.9)'; // More opaque gold
                        ctx.beginPath();
                        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                        ctx.fill();
                    }
                }
                
                function initParticles() {
                    particlesArray = [];
                    for (let i = 0; i < 100; i++) { // More particles
                        particlesArray.push(new Particle());
                    }
                }
                initParticles();
                
                function animateParticles() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    for (let i = 0; i < particlesArray.length; i++) {
                        particlesArray[i].update();
                        particlesArray[i].draw();
                    }
                    requestAnimationFrame(animateParticles);
                }
                animateParticles();
                
                window.addEventListener('resize', () => {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                    initParticles();
                });
            }

            // 3. Magnetic Buttons
            document.querySelectorAll('.magnetic-btn').forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    // Move the button slightly towards the cursor
                    btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
                    btn.style.transition = "none"; // Remove CSS transition for snappy tracking
                });
                
                btn.addEventListener('mouseleave', () => {
                    btn.style.transition = "transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)";
                    btn.style.transform = 'translate(0px, 0px)';
                });
            });

            // 4. Parallax Hero Float Tracking
            const hero = document.getElementById('parallax-hero');
            const heroBg = document.getElementById('parallax-bg');
            const heroContent = document.getElementById('parallax-content');
            
            if (hero) {
                hero.addEventListener('mousemove', (e) => {
                    // Calculate mouse position relative to center of screen
                    const x = (window.innerWidth / 2 - e.clientX) / 30;
                    const y = (window.innerHeight / 2 - e.clientY) / 30;
                    
                    // Move background subtly in opposite direction
                    if (heroBg) {
                        heroBg.style.transform = `translate(${x * 0.5}px, ${y * 0.5}px) scale(1.05)`;
                    }
                    // Move content slightly in same direction to create 3D glass depth
                    if (heroContent) {
                        heroContent.style.transform = `translate(${x * -0.8}px, ${y * -0.8}px)`;
                    }
                });
                
                hero.addEventListener('mouseleave', () => {
                    if (heroBg) heroBg.style.transform = `translate(0px, 0px) scale(1.05)`;
                    if (heroContent) heroContent.style.transform = `translate(0px, 0px)`;
                });
            }
        });
    </script>
</body>
</html>
