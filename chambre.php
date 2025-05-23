<?php
require_once 'connexion.php';
session_start(); // Démarrer la session pour vérifier la connexion

// Récupérer les chambres disponibles
$stmt = $pdo->query("SELECT * FROM chambres");
$chambres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Chambres de Luxe - EasyStay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a2a3a;
            --secondary-color: #019e7d;
            --accent-color: #d4af37;
            --text-color: #333;
            --light-text: #f8f9fa;
            --dark-bg: #0f1117;
            --card-bg: #1c1f2d;
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', sans-serif;
            background-color: var(--dark-bg);
            color: var(--light-text);
            line-height: 1.7;
        }

        /* Navigation */
        .navbar {
            background-color: rgba(26, 42, 58, 0.95);
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
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
            transition: var(--transition);
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
            transition: var(--transition);
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

        /* Main Content */
        .main-container {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .page-header h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .page-header p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            color: rgba(255,255,255,0.8);
        }

        /* Rooms Grid */
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }

        .room-card {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            transition: var(--transition);
            position: relative;
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .room-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .room-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .room-card:hover .room-img {
            transform: scale(1.05);
        }

        .room-content {
            padding: 2rem;
        }

        .room-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .room-features {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .feature {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
        }

        .feature i {
            color: var(--secondary-color);
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .room-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--light-text);
            margin-bottom: 1.5rem;
        }

        .room-price span {
            font-size: 1rem;
            font-weight: 400;
            color: rgba(255,255,255,0.6);
        }

        .booking-form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--light-text);
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            background: rgba(0,0,0,0.2);
            color: var(--light-text);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            text-align: center;
            width: 100%;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .btn:hover {
            background: #e8c252;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            display: inline-block;
            margin-top: 3rem;
            padding: 1rem 2.5rem;
            background: transparent;
            color: var(--accent-color);
            border: 2px solid var(--accent-color);
            font-weight: 600;
            border-radius: 50px;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* No Rooms Message */
        .no-rooms {
            text-align: center;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        .no-rooms i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        .no-rooms h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .page-header h1 {
                font-size: 2.5rem;
            }
            
            .rooms-grid {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

            .main-container {
                padding: 3rem 1.5rem;
            }

            .page-header h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .page-header p {
                font-size: 1rem;
            }
            
            .rooms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"><i class="fas fa-hotel"></i>EasyStay</div>
    <div class="menu-toggle" id="menuToggle">
        
    </div>
    <div class="menu-items" id="menuItems">
        <a href="index.php">Accueil</a>
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
</div>

<div class="main-container">
    <div class="page-header">
        <h1>Nos Suites & Chambres</h1>
        <p>Découvrez nos espaces raffinés conçus pour votre confort absolu, alliant élégance contemporaine et services haut de gamme.</p>
    </div>

    <?php if ($chambres) : ?>
        <div class="rooms-grid">
            <?php foreach ($chambres as $chambre) : ?>
                <div class="room-card">
                    <?php 
                      
                        $image = "images/simple.jpg";
                        $badge = "";
                        
                        if (strtolower($chambre['type']) == 'double') {
                            $image = "images/double.jpg";
                            $badge = "Populaire";
                        } elseif (strtolower($chambre['type']) == 'suite') {
                            $image = "images/sup.jpg";
                            $badge = "Luxe";
                        }
                    ?>
                    
                    <?php if ($badge) : ?>
                        <span class="room-badge"><?= $badge ?></span>
                    <?php endif; ?>
                    
                    <img src="<?= $image ?>" alt="Chambre <?= htmlspecialchars($chambre['type']) ?>" class="room-img">
                    
                    <div class="room-content">
                        <h3 class="room-title"><?= htmlspecialchars($chambre['type']) ?></h3>
                        
                        <div class="room-features">
                            <div class="feature">
                                <i class="fas fa-user-friends"></i>
                                <?= htmlspecialchars($chambre['capacité']) ?> personnes
                            </div>
                            <div class="feature">
                                <i class="fas fa-ruler-combined"></i>
                                <?= $chambre['type'] == 'Simple' ? '25' : ($chambre['type'] == 'Double' ? '35' : '50') ?> m²
                            </div>
                            <div class="feature">
                                <i class="fas fa-wifi"></i>
                                WiFi haut débit
                            </div>
                        </div>
                        
                        <div class="room-price">
                            <?= htmlspecialchars($chambre['prix']) ?> € <span>/ nuit</span>
                        </div>
                        
                        <form class="booking-form" method="get" action="reservation.php">
                            <div class="form-group">
                                <label for="debut-<?= $chambre['id'] ?>">Arrivée</label>
                                <input type="date" id="debut-<?= $chambre['id'] ?>" name="debut" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="fin-<?= $chambre['id'] ?>">Départ</label>
                                <input type="date" id="fin-<?= $chambre['id'] ?>" name="fin" required>
                            </div>
                            
                            <input type="hidden" name="chambre_id" value="<?= $chambre['id'] ?>">
                            
                            <button type="submit" class="btn">
                                <i class="fas fa-calendar-check"></i> Réserver maintenant
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="no-rooms">
            <i class="fas fa-door-closed"></i>
            <h3>Aucune chambre disponible</h3>
            <p>Nous sommes désolés, toutes nos chambres sont actuellement complètes. Veuillez réessayer plus tard.</p>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 4rem;">
        <a href="index.php" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>

<script>
   
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

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.min = today;
        });
        
        document.querySelectorAll('input[name="debut"]').forEach(startDateInput => {
            startDateInput.addEventListener('change', function() {
                const endDateId = this.id.replace('debut-', 'fin-');
                document.getElementById(endDateId).min = this.value;
            });
        });
    });
    function animateCards() {
        const cards = document.querySelectorAll('.room-card');
        
        cards.forEach((card, index) => {
            const cardPosition = card.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (cardPosition < screenPosition) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }
        });
    }

   
    document.querySelectorAll('.room-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });
    window.addEventListener('load', animateCards);
    window.addEventListener('scroll', animateCards);
</script>
</body>
</html>