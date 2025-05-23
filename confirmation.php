<?php
require_once 'connexion.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$chambre_id = $_GET['chambre_id'] ?? null;
$date_debut = $_GET['debut'] ?? null;
$date_fin = $_GET['fin'] ?? null;

if (!$chambre_id || !$date_debut || !$date_fin) {
    header('Location: chambre.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM chambres WHERE id = ?");
$stmt->execute([$chambre_id]);
$chambre = $stmt->fetch();

if (!$chambre) {
    header('Location: chambre.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation - EasyStay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a2a3a;
            --secondary-color: #019e7d;
            --accent-color: #d4af37;
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
        .confirmation-container {
            max-width: 800px;
            margin: 5rem auto;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
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

        .confirmation-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .confirmation-header {
            margin-bottom: 2.5rem;
        }

        .confirmation-header .icon {
            font-size: 5rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            animation: bounce 1s ease infinite alternate;
        }

        @keyframes bounce {
            to {
                transform: translateY(-10px);
            }
        }

        .confirmation-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .confirmation-header h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .confirmation-header p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Reservation Details */
        .reservation-details {
            background: rgba(0,0,0,0.2);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            text-align: left;
        }

        .detail-item {
            display: flex;
            margin-bottom: 1.2rem;
            align-items: center;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .detail-content h3 {
            font-size: 1.1rem;
            color: var(--accent-color);
            margin-bottom: 0.3rem;
        }

        .detail-content p {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2.5rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            margin-top: 1.5rem;
        }

        .btn-primary {
            background: var(--accent-color);
            color: var(--primary-color);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:hover {
            background: #e8c252;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }

        .btn i {
            margin-right: 0.8rem;
        }

        /* Responsive Design */
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

            .confirmation-container {
                margin: 3rem 1.5rem;
                padding: 2rem 1.5rem;
            }

            .confirmation-header h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .detail-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-icon {
                margin-right: 0;
                margin-bottom: 1rem;
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
            <a href="avis.php">Avis Clients</a>
            <a href="logout.php">Déconnexion</a>
        <?php else : ?>
            <a href="login.php">Connexion</a>
            <a href="inscription.php">Inscription</a>
        <?php endif; ?>
    </div>
</div>

<div class="confirmation-container">
    <div class="confirmation-header">
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Réservation Confirmée !</h1>
        <p>Merci pour votre réservation chez EasyStay. Voici les détails de votre séjour.</p>
    </div>

    <div class="reservation-details">
        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="detail-content">
                <h3>Type de Chambre</h3>
                <p><?= htmlspecialchars($chambre['type']) ?> (<?= htmlspecialchars($chambre['capacité']) ?> personnes)</p>
            </div>
        </div>

        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="detail-content">
                <h3>Dates de Séjour</h3>
                <p>Du <?= htmlspecialchars($date_debut) ?> au <?= htmlspecialchars($date_fin) ?></p>
            </div>
        </div>

        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="detail-content">
                <h3>Prix Total</h3>
                <p><?= htmlspecialchars($chambre['prix']) ?> € par nuit</p>
            </div>
        </div>

        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="detail-content">
                <h3>Numéro de Réservation</h3>
                <p>#<?= rand(100000, 999999) ?></p>
            </div>
        </div>
    </div>

    <a href="reservation_form.php" class="btn btn-primary">
        <i class="fas fa-user-circle"></i> Voir mes réservations
    </a>
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
</script>
</body>
</html>