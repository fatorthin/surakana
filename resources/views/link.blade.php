<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surakana Roastery</title>
    <meta name="keywords" content="surakana, roastery, kopi, coffee, solo, surakarta">
    <meta name="description" content="Surakana Roastery – Kopi pilihan dari Solo untuk semua kalangan.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            color: #1C1C1C;
            line-height: 1.6;
        }

        /* ---- NAVBAR ---- */
        .navbar {
            background-color: #ffffff;
            border-bottom: 3px solid #8B5A2B;
            padding: 0.75rem 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        #logo {
            display: flex;
            align-items: center;
            height: 64px;
        }

        #logo img {
            max-height: 56px;
            width: auto;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-menu a {
            text-decoration: none;
            color: #1C1C1C;
            font-weight: 500;
            position: relative;
            transition: color .3s;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #8B5A2B;
            transition: width .3s cubic-bezier(.4, 0, .2, 1);
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        /* Hamburger */
        .menu-toggle {
            display: none;
        }

        .hamburger {
            display: none;
            position: relative;
            width: 28px;
            height: 24px;
            cursor: pointer;
            z-index: 2;
        }

        .hamburger .line {
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: #5C4033;
            border-radius: 3px;
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .hamburger .line:nth-child(1) {
            top: 3px;
        }

        .hamburger .line:nth-child(2) {
            top: 10px;
        }

        .hamburger .line:nth-child(3) {
            top: 17px;
        }

        .menu-toggle:checked~.hamburger .line:nth-child(1) {
            top: 10px;
            transform: rotate(45deg);
        }

        .menu-toggle:checked~.hamburger .line:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle:checked~.hamburger .line:nth-child(3) {
            top: 10px;
            transform: rotate(-45deg);
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: #ffffff;
                flex-direction: column;
                padding: 1.2rem 5%;
                gap: 1rem;
                box-shadow: 0 8px 16px rgba(0, 0, 0, .1);
                transform: scaleY(0);
                transform-origin: top;
                transition: transform .4s cubic-bezier(.4, 0, .2, 1);
            }

            .menu-toggle:checked~.nav-menu {
                transform: scaleY(1);
            }

            .btn-link {
                width: 240px;
                height: 68px;
                font-size: 1.05rem;
            }

            .button-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 240px));
            }

            main {
                padding-top: 78px;
            }
        }

        @media (max-width: 480px) {
            .section {
                padding: 3rem 5%;
            }

            .section-title {
                font-size: 1.9rem;
            }
        }

        /* ---- MAIN ---- */
        main {
            padding-top: 85px;
        }

        /* ---- SECTIONS ---- */
        .section {
            padding: 4rem 5%;
            opacity: 0;
            transform: translateY(40px);
            animation: fadeUp .9s cubic-bezier(.25, .1, .25, 1) forwards;
        }

        .section:nth-of-type(1) {
            animation-delay: 100ms;
        }

        .section:nth-of-type(2) {
            animation-delay: 300ms;
        }

        .section:nth-of-type(3) {
            animation-delay: 500ms;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---- SECTION TITLE ---- */
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1C1C1C;
            margin-bottom: 2.2rem;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 3px;
            background-color: #8B5A2B;
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            animation: lineGrow 1s cubic-bezier(.4, 0, .2, 1) forwards;
            animation-delay: 600ms;
            opacity: 0;
        }

        @keyframes lineGrow {
            to {
                opacity: 1;
                width: 90px;
            }
        }

        .outlined-title {
            text-shadow: -2px -2px 0 #fff, 2px -2px 0 #fff, -2px 2px 0 #fff, 2px 2px 0 #fff;
        }

        /* ---- PATTERN SECTION ---- */
        .pattern-section {
            background: url("{{ asset('pattern.png') }}");
            background-color: #ffffff;
            background-attachment: fixed;
            background-repeat: repeat;
        }

        /* ---- SOCIAL SECTION ---- */
        .social-section {
            background-color: #1C1C1C;
        }

        .social-section .section-title {
            color: #ffffff;
        }

        .social-section .section-title::after {
            background-color: #8B5A2B;
        }

        /* ---- BUTTONS ---- */
        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 260px));
            gap: 1.8rem;
            justify-content: center;
        }

        .btn-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 260px;
            height: 72px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 0;
            transition: all .4s cubic-bezier(.4, 0, .2, 1);
            background-color: #ffffff;
            border: 4px solid #8B5A2B;
            color: #1C1C1C;
            box-shadow: 4px 4px 0 #8B5A2B;
            opacity: 0;
            transform: translateY(30px);
            animation: btnPop .7s cubic-bezier(.4, 0, .2, 1) forwards;
        }

        .btn-link:hover {
            background-color: #8B5A2B;
            color: #ffffff;
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 12px 0 #5C4033;
        }

        .btn-link:hover img {
            filter: brightness(0) invert(1);
        }

        .social-btn {
            background-color: #5C4033;
            border: 4px solid #ffffff;
            color: #ffffff;
            box-shadow: 4px 4px 0 #ffffff;
        }

        .social-btn:hover {
            background-color: #8B5A2B;
            border-color: #ffffff;
            color: #ffffff;
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 12px 0 #ffffff;
        }

        .button-grid .btn-link:nth-child(1) {
            animation-delay: 400ms;
        }

        .button-grid .btn-link:nth-child(2) {
            animation-delay: 550ms;
        }

        .button-grid .btn-link:nth-child(3) {
            animation-delay: 700ms;
        }

        .button-grid .btn-link:nth-child(4) {
            animation-delay: 850ms;
        }

        @keyframes btnPop {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---- MAP ---- */
        .map-container {
            overflow: hidden;
            box-shadow: 6px 6px 0 #5C4033;
            transition: transform .4s cubic-bezier(.4, 0, .2, 1);
        }

        .map-container:hover {
            transform: scale(1.02);
        }

        .address-text {
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ---- FOOTER ---- */
        footer {
            background-color: #5C4033;
            color: #ffffff;
            text-align: center;
            padding: 1.8rem 5%;
            font-size: 1rem;
        }

        /* ---- FLOATING WA ---- */
        .floating-wa {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 64px;
            height: 64px;
            background-color: #25D366;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.1rem;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(37, 211, 102, .4);
            z-index: 1000;
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        .floating-wa:hover {
            transform: scale(1.15);
            box-shadow: 0 10px 25px rgba(37, 211, 102, .5);
        }
    </style>
</head>

<body>

    <!-- HEADER / NAVBAR -->
    <header>
        <nav class="navbar">
            <div id="logo">
                <img src="{{ asset('surakana.png') }}" alt="Surakana Roastery">
            </div>

            <input type="checkbox" id="menu-toggle" class="menu-toggle">
            <label for="menu-toggle" class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </label>

            <ul class="nav-menu">
                <li><a href="#"><i class="fa-solid fa-mug-hot"></i>&nbsp;Home</a></li>
                <li><a href="#ecommerce"><i class="fa-solid fa-cart-shopping"></i>&nbsp;E-commerce</a></li>
                <li><a href="#social"><i class="fa-solid fa-share-nodes"></i>&nbsp;Social Media</a></li>
                <li><a href="#alamat"><i class="fa-solid fa-map-location"></i>&nbsp;Address</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <!-- E-COMMERCE -->
        <section id="ecommerce" class="section pattern-section">
            <div class="container" style="max-width:900px;margin:auto;">
                <h2 class="section-title outlined-title">e-Commerce</h2>
                <div class="button-grid">
                    <a href="https://shopee.co.id/surakanaroastery" target="_blank" rel="noopener" class="btn-link">
                        <img width="40" height="40" src="https://img.icons8.com/color/48/shopee.png"
                            alt="Shopee">
                        Shopee
                    </a>
                    <a href="https://www.tokopedia.com/surakana-roastery" target="_blank" rel="noopener"
                        class="btn-link">
                        <img width="40" height="40" src="https://img.icons8.com/nolan/64/tokopedia.png"
                            alt="Tokopedia">
                        Tokopedia
                    </a>
                    <a href="https://vt.tiktok.com/ZSHjnvwHK/?page=Mall" target="_blank" rel="noopener"
                        class="btn-link">
                        <img width="40" height="40" src="https://img.icons8.com/color/48/tiktok--v1.png"
                            alt="TikTok Shop">
                        TikTok Shop
                    </a>
                </div>
            </div>
        </section>

        <!-- SOCIAL MEDIA -->
        <section id="social" class="section social-section">
            <div class="container" style="max-width:900px;margin:auto;">
                <h2 class="section-title">Social Media</h2>
                <div class="button-grid">
                    <a href="https://www.instagram.com/surakana.roastery" target="_blank" rel="noopener"
                        class="btn-link social-btn">
                        <img width="40" height="40" src="https://img.icons8.com/fluency/48/instagram-new.png"
                            alt="Instagram">
                        Instagram
                    </a>
                    <a href="https://web.facebook.com/people/Surakana-Roastery/61586411185123/" target="_blank"
                        rel="noopener" class="btn-link social-btn">
                        <img width="40" height="40" src="https://img.icons8.com/color/48/facebook.png"
                            alt="Facebook">
                        Facebook
                    </a>
                    <a href="https://x.com/surakana" target="_blank" rel="noopener" class="btn-link social-btn">
                        <img width="40" height="40" src="https://img.icons8.com/ios-filled/50/twitterx--v1.png"
                            alt="X" style="filter:invert(1)">
                        X
                    </a>
                    <a href="https://www.tiktok.com/@surakana.roastery" target="_blank" rel="noopener"
                        class="btn-link social-btn">
                        <img width="40" height="40" src="https://img.icons8.com/doodle/48/tiktok--v2.png"
                            alt="TikTok">
                        TikTok
                    </a>
                </div>
            </div>
        </section>

        <!-- ADDRESS -->
        <section id="alamat" class="section">
            <div class="container" style="max-width:900px;margin:auto;">
                <h2 class="section-title">Address</h2>
                <p class="address-text">
                    Jl. Porong RT 03 / III, Pucanggsawit, Jebres, Surakarta, 57125.
                </p>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.062608143231!2d110.8474565758837!3d-7.568153574750759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a17c4f4a7f125%3A0x2634373938a84901!2sSurakana%20Roastery!5e0!3m2!1sen!2sid!4v1776762397874!5m2!1sen!2sid"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} | All right reserved by surakana.roastery</p>
        </div>
    </footer>

    <!-- FLOATING WHATSAPP -->
    <a href="https://wa.me/+6285171170987?text=Halo%20Surakana%20Roastery,%20saya%20mau%20pesan%20kopi%20dong?"
        target="_blank" rel="noopener" class="floating-wa" title="Chat via WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <script>
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                const cb = document.getElementById('menu-toggle');
                if (cb) cb.checked = false;
            });
        });
    </script>

</body>

</html>
