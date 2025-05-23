<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['utilisateur'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - EasyStay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a2a3a;
            --secondary-color: #019e7d;
            --accent-color: #d4af37;
            --light-text: #f8f9fa;
            --dark-bg: #0f1117;
            --card-bg: #1c1f2d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
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

        /* Profil Container */
        .profile-container {
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

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .profile-header {
            margin-bottom: 2.5rem;
        }

        .profile-header .icon {
            font-size: 5rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        .profile-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .profile-header h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Profile Details */
        .profile-details {
            background: rgba(0,0,0,0.2);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            text-align: left;
        }

        .detail-item {
            display: flex;
            margin-bottom: 1.5rem;
            align-items: center;
        }

        .detail-icon {
            width: 50px;
            height: 50px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color: var(--accent-color);
            font-size: 1.5rem;
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
            margin: 0 0.5rem;
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

        .btn-danger {
            background: var(--danger-color);
            color: white;
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            background: #e04a5a;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
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

            .profile-container {
                margin: 3rem 1.5rem;
                padding: 2rem 1.5rem;
            }

            .profile-header h1 {
                font-size: 2rem;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .btn {
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo"><i class="fas fa-hotel"></i>EasyStay</div>
    <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
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

<div class="profile-container">
    <div class="profile-header">
        <div class="icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h1>Mon Profil</h1>
    </div>

    <div class="profile-details">
        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="detail-content">
                <h3>Nom Complet</h3>
                <p><?= isset($user['nom']) ? htmlspecialchars($user['nom']) : 'Non spécifié' ?></p>
            </div>
        </div>

        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="detail-content">
                <h3>Email</h3>
                <p><?= isset($user['email']) ? htmlspecialchars($user['email']) : 'Non spécifié' ?></p>
            </div>
        </div>

        <?php if (isset($user['telephone'])) : ?>
        <div class="detail-item">
            <div class="detail-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="detail-content">
                <h3>Téléphone</h3>
                <p><?= htmlspecialchars($user['telephone']) ?></p>
            </div>
        </div>
        <?php endif; ?>

        

    
        <a href="logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Se déconnecter
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
</script>
</body>
</html>
