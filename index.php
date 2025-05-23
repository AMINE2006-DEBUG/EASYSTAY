<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EasyStay - Hôtel 5 étoiles à Casablanca offrant un luxe contemporain, des services exceptionnels et des chambres haut de gamme">
    <title>EasyStay Casablanca - Hôtel de Luxe & Spa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a2a3a;
            --secondary-color: #019e7d;
            --accent-color: #d4af37;
            --text-color: #333;
            --light-text: #f8f9fa;
            --background-color: #f5f7fa;
            --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
            --transition-fast: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --section-padding: 6rem 2rem;
        }

        * { 
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            background: var(--background-color);
            color: var(--text-color);
            line-height: 1.7;
            overflow-x: hidden;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Typography */
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            line-height: 1.3;
        }

        p {
            font-size: 1.1rem;
            color: #555;
        }

        /* Navigation */
        .navbar {
            background-color: rgba(26, 42, 58, 0.9);
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            padding: 1rem 5%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .navbar a {
            color: var(--light-text);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            margin: 0 0.5rem;
            border-radius: 30px;
            transition: var(--transition-fast);
            position: relative;
        }

        .navbar a:hover {
            color: var(--accent-color);
        }

        .navbar a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: var(--transition-fast);
        }

        .navbar a:hover::after {
            width: 70%;
        }

        .navbar .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--light-text);
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }

        .logo i {
            color: var(--accent-color);
            margin-right: 0.8rem;
            font-size: 1.5rem;
        }

        .navbar .menu-items {
            display: flex;
            align-items: center;
        }

        /* Hero Section */
        .header-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .header-container::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.2));
        }

        .header-img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            z-index: -1;
            animation: zoomEffect 20s infinite alternate;
        }

        @keyframes zoomEffect {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .header-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--light-text);
            text-align: center;
            padding: 4rem;
            border-radius: 15px;
            width: 90%;
            max-width: 1000px;
            opacity: 0;
            animation: fadeIn 1.5s forwards 0.5s;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .header-content h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
            position: relative;
            display: inline-block;
        }

        .header-content h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--accent-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .header-content p {
            font-size: 1.4rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            color: rgba(255,255,255,0.9);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: var(--accent-color);
            color: var(--primary-color);
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 1rem;
            transition: var(--transition-fast);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
            background: #e8c252;
        }

        /* Secondary Image Section */
        .image-secondaire {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
        }

        .img-sous-header {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: brightness(0.8);
        }

        .texte-sur-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--light-text);
            padding: 2.5rem;
            text-align: center;
            font-size: 2.2rem;
            width: 90%;
            max-width: 900px;
            opacity: 0;
            transform: translate(-50%, -40%);
            animation: slideUp 1s forwards 0.8s;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .texte-sur-image h2 {
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            position: relative;
            padding-bottom: 1.5rem;
        }

        .texte-sur-image h2::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 3px;
            background: var(--accent-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Search Section */
        .recherche {
            position: relative;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: -100px auto 100px;
            padding: 3.5rem;
            text-align: center;
            z-index: 10;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .recherche::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: var(--secondary-color);
            border-radius: 50%;
            top: -50px;
            left: -50px;
            opacity: 0.1;
        }

        .recherche::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            opacity: 0.1;
        }

        .recherche h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        .recherche h2::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .recherche form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        .recherche input, .recherche select {
            padding: 1rem 1.2rem;
            width: 280px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
            transition: var(--transition-fast);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .recherche input:focus,
        .recherche select:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.1);
        }

        .recherche button {
            padding: 1rem 2.5rem;
            background: var(--accent-color);
            color: var(--primary-color);
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: var(--transition-fast);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .recherche button i {
            margin-left: 0.5rem;
        }

        .recherche button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
            background: #e8c252;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-title p {
            max-width: 700px;
            margin: 1.5rem auto 0;
        }

        .chambres {
            padding: var(--section-padding);
            background: #fff;
        }

        .chambres-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .carte {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: var(--transition-fast);
            width: 100%;
            max-width: 350px;
            position: relative;
        }

        .carte:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .carte-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 2;
        }

        .carte img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .carte:hover img {
            transform: scale(1.05);
        }

        .carte .contenu {
            padding: 2rem;
            position: relative;
        }

        .carte h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .carte p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .carte .btn-secondary {
            display: inline-block;
            padding: 0.7rem 1.5rem;
            background: transparent;
            color: var(--accent-color);
            font-size: 0.9rem;
            font-weight: 600;
            border: 2px solid var(--accent-color);
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition-fast);
            text-decoration: none;
        }

        .carte .btn-secondary:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* Content Sections */
        section {
            padding: var(--section-padding);
            position: relative;
        }

        .why-choose-us {
            background: #f9f9f9;
        }

        .why-choose-us::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .feature-card {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: var(--transition-fast);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .citation {
            background: linear-gradient(rgba(26, 42, 58, 0.9), rgba(26, 42, 58, 0.9)), url('images/parallax-bg.jpg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: var(--light-text);
            text-align: center;
            padding: 8rem 2rem;
            position: relative;
        }

        blockquote {
            font-size: 1.8rem;
            font-style: italic;
            font-weight: 500;
            margin-bottom: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.7;
            position: relative;
            padding: 0 2rem;
        }

        blockquote::before,
        blockquote::after {
            content: '"';
            font-size: 3rem;
            color: var(--accent-color);
            opacity: 0.5;
            position: absolute;
        }

        blockquote::before {
            top: -20px;
            left: -10px;
        }

        blockquote::after {
            bottom: -40px;
            right: -10px;
        }

        .citation-author {
            font-size: 1.3rem;
            color: var(--accent-color);
            font-weight: 600;
            margin-top: 3rem;
            letter-spacing: 1px;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: var(--light-text);
            padding: 5rem 2rem 2rem;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-logo h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .footer-logo h3 i {
            color: var(--accent-color);
            margin-right: 0.8rem;
        }

        .footer-logo p {
            font-size: 1rem;
            color: #bdc3c7;
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: var(--light-text);
            transition: var(--transition-fast);
        }

        .social-links a:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-5px);
        }

        .footer-info, .footer-services, .footer-contact {
            text-align: left;
        }

        .footer h4 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.8rem;
        }

        .footer h4::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: var(--accent-color);
            bottom: 0;
            left: 0;
        }

        .footer ul {
            list-style: none;
        }

        .footer ul li {
            margin-bottom: 0.8rem;
        }

        .footer ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: var(--transition-fast);
            display: flex;
            align-items: center;
        }

        .footer ul li a i {
            margin-right: 0.8rem;
            color: var(--accent-color);
            font-size: 0.8rem;
        }

        .footer ul li a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: #bdc3c7;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-fast);
            z-index: 999;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            background: #e8c252;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .header-content h1 {
                font-size: 3.5rem;
            }
            
            .header-content p {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 992px) {
            :root {
                --section-padding: 5rem 2rem;
            }
            
            .header-content h1 {
                font-size: 3rem;
            }
            
            .texte-sur-image {
                font-size: 1.8rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1.5rem;
            }

            .navbar .menu-items {
                flex-direction: column;
                margin-top: 1.5rem;
                width: 100%;
                display: none;
            }

            .navbar .menu-items.active {
                display: flex;
            }

            .navbar a {
                margin: 0.5rem 0;
                width: 100%;
                text-align: center;
            }

            .menu-toggle {
                display: block;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }

            .header-content {
                padding: 2rem;
                width: 95%;
            }

            .header-content h1 {
                font-size: 2.5rem;
            }

            .image-secondaire {
                height: 400px;
            }

            .texte-sur-image {
                font-size: 1.5rem;
                padding: 1.5rem;
            }

            .recherche {
                margin: -80px auto 80px;
                padding: 2.5rem;
            }

            .recherche form {
                flex-direction: column;
                align-items: center;
            }

            .recherche input, 
            .recherche select {
                width: 100%;
            }

            blockquote {
                font-size: 1.5rem;
                padding: 0 1rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --section-padding: 4rem 1.5rem;
            }
            
            .header-content h1 {
                font-size: 2rem;
            }
            
            .header-content p {
                font-size: 1rem;
            }
            
            .btn-primary, .recherche button {
                padding: 0.8rem 1.8rem;
                font-size: 0.9rem;
            }
            
            .texte-sur-image {
                font-size: 1.3rem;
                padding: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            blockquote {
                font-size: 1.2rem;
            }
            
            .footer-container {
                grid-template-columns: 1fr;
            }
            
            .footer-info, 
            .footer-services, 
            .footer-contact {
                text-align: center;
            }
            
            .footer h4::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="logo"><i class="fas fa-hotel"></i>EasyStay</div>
        <div class="menu-toggle" id="menuToggle">
           
        </div>
        <div class="menu-items" id="menuItems">
            <a href="#accueil.php">Accueil</a>
            <a href="chambre.php">Chambres</a>
            
            <?php if (isset($_SESSION['utilisateur'])) : ?>
                <a href="profil.php">Mon Profil</a>
                <a href="recherche.php">recherche</a>
                <a href="reservation_form.php">Mes Réservations</a>
                <a href="avis.php">Avis Clients</a>
                <a href="logout.php">Déconnexion</a>
            <?php else : ?>
                <a href="login.php">Connexion</a>
                <a href="inscription.php">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="header-container" id="home">
        <img src="ground.jpg" alt="Façade luxueuse de l'hôtel EasyStay à Casablanca" class="header-img">
        <div class="header-content">
            <h1>Bienvenue chez EasyStay</h1>
            <p>Découvrez un havre de paix au cœur de Casablanca, où luxe contemporain et hospitalité marocaine se rencontrent pour créer une expérience inoubliable.</p>
            <a href="#recherche" class="btn-primary">Réserver maintenant <i class="fas fa-arrow-right"></i></a>
        </div>
    </header>
    <div class="image-secondaire">
        <img src="vue.jpg" alt="Vue panoramique exclusive depuis les suites EasyStay" class="img-sous-header">
        <div class="texte-sur-image">
            <h2>Votre refuge urbain avec vue spectaculaire sur Casablanca</h2>
        </div>
    </div>
    <div class="recherche" id="recherche">
        <h2>Réservez votre séjour de rêve</h2>
        <form action="recherche.php" method="post">
            <div class="form-group">
                <label for="type">Type de chambre</label>
                <select name="type" id="type" required>
                    <option value="">Sélectionnez...</option>
                    <option value="Simple">Chambre Simple</option>
                    <option value="Double">Chambre Double</option>
                    <option value="Supérieure">Suite Supérieure</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="date_debut">Date d'arrivée</label>
                <input type="date" name="date_debut" id="date_debut" required>
            </div>
            
            <div class="form-group">
                <label for="date_fin">Date de départ</label>
                <input type="date" name="date_fin" id="date_fin" required>
            </div>
            
            <button type="submit"><i class="fas fa-search"></i> Trouver une chambre</button>
        </form>
    </div>

    <section class="chambres">
        <div class="section-title">
            <h2>Nos Chambres Exclusives</h2>
            <p>Découvrez nos espaces raffinés conçus pour votre confort absolu</p>
        </div>
        
        <div class="chambres-container">
            <article class="carte">
                <span class="carte-badge">Best Seller</span>
                <img src="images/simple.jpg" alt="Chambre Simple Deluxe EasyStay">
                <div class="contenu">
                    <h3>Chambre Simple Deluxe</h3>
                    <p>Un sanctuaire élégant avec lit king-size, espace de travail ergonomique et salle de bain en marbre. Parfaite pour les voyageurs d'affaires.</p>
                    <a href="chambre.php?type=simple" class="btn-secondary">Voir détails <i class="fas fa-angle-right"></i></a>
                </div>
            </article>

            <article class="carte">
                <img src="images/double.jpg" alt="Chambre Double Premium EasyStay">
                <div class="contenu">
                    <h3>Chambre Double Premium</h3>
                    <p>Harmonie d'espace et d'élégance avec deux lits queen-size, coin salon et vue partielle sur la ville. Idéale pour les familles.</p>
                    <a href="chambre.php?type=double" class="btn-secondary">Voir détails <i class="fas fa-angle-right"></i></a>
                </div>
            </article>

            <article class="carte">
                <span class="carte-badge">Luxe</span>
                <img src="images/sup.jpg" alt="Suite Supérieure EasyStay">
                <div class="contenu">
                    <h3>Suite Supérieure</h3>
                    <p>Espace généreux avec salon séparé, balcon privé offrant une vue panoramique, baignoire freestanding et services VIP inclus.</p>
                    <a href="chambre.php?type=supérieure" class="btn-secondary">Voir détails <i class="fas fa-angle-right"></i></a>
                </div>
            </article>
        </div>
    </section>
    <section class="why-choose-us">
        <div class="section-title">
            <h2>L'Expérience EasyStay</h2>
            <p>Ce qui fait de nous le choix privilégié des voyageurs exigeants</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h3>Service Personnalisé</h3>
                <p>Notre équipe dévouée est disponible 24/7 pour anticiper et satisfaire vos moindres désirs.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Gastronomie d'Exception</h3>
                <p>Restaurant primé proposant une fusion de saveurs marocaines et internationales avec produits locaux.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h3>Spa Luxury</h3>
                <p>Centre de bien-être avec soins sur mesure, hammam traditionnel et piscine intérieure chauffée.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h3>Emplacement Idéal</h3>
                <p>Au cœur de Casablanca, à proximité des centres d'affaires, shopping et sites culturels majeurs.</p>
            </div>
        </div>
    </section>

    <section>
        <div class="section-title">
            <h2>Notre Philosophie</h2>
            <p>L'art de l'hospitalité réinventé</p>
        </div>
        
        <div style="max-width: 900px; margin: 0 auto;">
            <p style="margin-bottom: 2rem;">
                Fondé en 2010, EasyStay incarne l'alliance parfaite entre tradition d'accueil marocaine et standards internationaux de luxe. Notre établissement 5 étoiles a été conçu comme un écrin contemporain où chaque détail a été pensé pour votre bien-être.
            </p>
            
            <p style="margin-bottom: 2rem;">
                Avec 120 chambres et suites, 3 restaurants gastronomiques, un spa de 1500m² et des salles d'événements ultramodernes, nous nous engageons à créer des expériences sur mesure, qu'il s'agisse d'un voyage d'affaires, d'une escapade romantique ou de vacances en famille.
            </p>
            
            <p>
                Notre équipe multiculturelle, formée aux plus hauts standards de service, met tout en œuvre pour transformer votre séjour en une collection de moments précieux et mémorables.
            </p>
        </div>
    </section>

    <section class="citation">
        <blockquote>
            <p>"EasyStay a redéfini mon attente d'un hôtel de luxe. Chaque visite est une expérience sur mesure où l'excellence du service rencontre un design raffiné."</p>
        </blockquote>
        <p class="citation-author">– Pierre D., Client VIP depuis 2015</p>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h3><i class="fas fa-hotel"></i>EasyStay</h3>
                <p>Votre oasis de luxe au cœur de Casablanca</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-info">
                <h4>Informations</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> À propos de nous</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Nos services</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Politique de confidentialité</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Programme fidélité</a></li>
                </ul>
            </div>

            <div class="footer-services">
                <h4>Nos Services</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Hébergement luxe</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Restaurant gastronomique</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Spa & Bien-être</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Événements & Mariages</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Contactez-nous</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Boulevard de la Corniche, Casablanca</a></li>
                    <li><a href="tel:+212123456789"><i class="fas fa-phone"></i> +212 123 456 789</a></li>
                    <li><a href="mailto:reservations@easystay.com"><i class="fas fa-envelope"></i> reservations@easystay.com</a></li>
                    <li><a href="#"><i class="fas fa-clock"></i> 24/7</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 EasyStay. Tous droits réservés. | Conçu avec <i class="fas fa-heart" style="color: #ff5e9c;"></i> pour votre confort</p>
        </div>
    </footer>

    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
      
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
    
            const backToTop = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });

        const menuToggle = document.getElementById('menuToggle');
        const menuItems = document.getElementById('menuItems');
        
        menuToggle.addEventListener('click', function() {
            menuItems.classList.toggle('active');
        });

        const navLinks = document.querySelectorAll('.navbar a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    menuItems.classList.remove('active');
                }
            });
        });

    
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

    
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_debut').min = today;
            document.getElementById('date_fin').min = today;
      
            document.getElementById('date_debut').addEventListener('change', function() {
                document.getElementById('date_fin').min = this.value;
            });
        });

        function animateOnScroll() {
            const elements = document.querySelectorAll('.feature-card, .carte, .section-title');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;
                
                if (elementPosition < screenPosition) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }

        document.querySelectorAll('.feature-card, .carte').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        document.querySelector('.section-title').style.opacity = '0';
        document.querySelector('.section-title').style.transform = 'translateY(30px)';
        document.querySelector('.section-title').style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        window.addEventListener('load', animateOnScroll);
        window.addEventListener('scroll', animateOnScroll);
    </script>
</body>
</html>

