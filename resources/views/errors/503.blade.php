<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance | Felix Elite Garment Care</title>
    <link rel="icon" type="image/png" href="{{ asset('test/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,600,700" rel="stylesheet" />
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #09090b; /* zinc-950 */
            color: #fafafa; /* zinc-50 */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }

        h1, h2, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .luxury-gradient {
            background: linear-gradient(135deg, #d4af37 0%, #aa8529 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.15); /* Subtle gold border */
            padding: 4rem 3rem;
            border-radius: 2px; /* Sharp corners for premium feel */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            max-width: 600px;
            backdrop-filter: blur(12px);
            animation: fadeInUp 1s ease-out forwards;
            position: relative;
            z-index: 10;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-wrapper {
            margin-bottom: 2.5rem;
        }

        .logo-wrapper img {
            height: 60px;
            object-fit: contain;
            opacity: 0.9;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }

        p {
            font-size: 1rem;
            line-height: 1.7;
            color: #a1a1aa; /* zinc-400 */
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .badge {
            display: inline-block;
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
            padding: 0.4rem 1.2rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        /* Ambient Golden Particles */
        #luxury-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        .spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto 2rem;
            border: 2px solid rgba(212, 175, 55, 0.2);
            border-top-color: #d4af37;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .contact-info {
            font-size: 0.85rem;
            color: #71717a; /* zinc-500 */
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }
        
        .contact-info a {
            color: #d4af37;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        
        .contact-info a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <canvas id="luxury-particles"></canvas>

    <div class="container">
        <div class="logo-wrapper">
            <img src="{{ asset('test/images/logo.png') }}" alt="Felix Elite Garment Care">
        </div>
        
        <div class="spinner"></div>

        <div class="badge">Scheduled Maintenance</div>
        
        <h1 class="luxury-gradient">Elevating Your Experience</h1>
        
        <p>Felix Elite Garment Care is currently undergoing scheduled system enhancements to ensure our bespoke services continue to exceed your expectations. Our digital boutique will return shortly.</p>
        
        <div class="contact-info">
            For urgent concierge inquiries, please contact your dedicated account manager. <br>
            &copy; {{ date('Y') }} Felix Elite. All rights reserved.
        </div>
    </div>

    <script>
        // Ambient Golden Particles
        const canvas = document.getElementById('luxury-particles');
        const ctx = canvas.getContext('2d');
        let particlesArray;
        
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = Math.random() * 0.4 - 0.2;
                this.speedY = Math.random() * 0.6 - 0.6;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width || this.y < 0) {
                    this.x = Math.random() * canvas.width;
                    this.y = canvas.height;
                    this.size = Math.random() * 2 + 0.5;
                }
            }
            draw() {
                ctx.fillStyle = 'rgba(212, 175, 55, 0.7)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        
        function initParticles() {
            particlesArray = [];
            for (let i = 0; i < 50; i++) {
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
    </script>
</body>
</html>
