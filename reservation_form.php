<?php
require_once 'connexion.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur']['id'];

// Gestion des messages
$success_message = $_SESSION['success'] ?? null;
$error_message = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Récupération des réservations
$stmt = $pdo->prepare("
    SELECT r.id, r.date_debut, r.date_fin, r.statut,
           c.type AS chambre_type, c.prix AS chambre_prix,
           c.image AS chambre_image
    FROM reservations r
    JOIN chambres c ON r.chambre_id = c.id
    WHERE r.utilisateur_id = ?
    ORDER BY r.date_debut DESC
");
$stmt->execute([$utilisateur_id]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - EasyStay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
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

        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        h1 {
            color: var(--accent-color);
            margin-bottom: 30px;
            text-align: center;
            font-family: 'Playfair Display', serif;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-error {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Reservation List */
        .reservation-list {
            display: grid;
            gap: 20px;
        }

        .reservation-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .reservation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
        }

        .reservation-id {
            font-weight: bold;
            color: var(--accent-color);
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--warning-color);
        }

        .status-confirmed {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success-color);
        }

        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            margin-bottom: 10px;
        }

        .detail-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 500;
        }

        .chambre-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .actions {
            margin-top: 15px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-details {
            background-color: rgba(88, 166, 255, 0.2);
            color: #58a6ff;
        }

        .btn-details:hover {
            background-color: rgba(88, 166, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-cancel {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
        }

        .btn-cancel:hover {
            background-color: rgba(220, 53, 69, 0.3);
            transform: translateY(-2px);
        }

        .no-reservations {
            text-align: center;
            padding: 40px;
            background: var(--card-bg);
            border-radius: 10px;
        }

        .no-reservations i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: row;
                padding: 1rem;
            }

            .menu-toggle {
                display: block;
            }

            .navbar .menu-items {
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                padding: 1rem;
                display: none;
                box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            }

            .navbar .menu-items.active {
                display: flex;
            }

            .navbar a {
                margin: 0.5rem 0;
                width: 100%;
                text-align: center;
            }

            .reservation-details {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
                gap: 8px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            
            .navbar .logo {
                font-size: 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
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

<div class="container">
    <h1><i class="fas fa-calendar-alt"></i> Mes Réservations</h1>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($reservations)) : ?>
        <div class="no-reservations">
            <i class="fas fa-calendar-times"></i>
            <h3>Aucune réservation trouvée</h3>
            <p>Vous n'avez pas encore effectué de réservation.</p>
            <a href="reservation.php" class="btn btn-details">
                <i class="fas fa-plus"></i> Faire une réservation
            </a>
        </div>
    <?php else : ?>
        <div class="reservation-list">
            <?php foreach ($reservations as $reservation) : ?>
                <?php
                    $dateDebut = new DateTime($reservation['date_debut']);
                    $dateFin = new DateTime($reservation['date_fin']);
                    $nuits = $dateDebut->diff($dateFin)->days;
                    $prixTotal = $nuits * $reservation['chambre_prix'];
                    
                    $statutFormate = strtolower(str_replace('ée', 'e', $reservation['statut']));
                    $statusClass = 'status-' . $statutFormate;
                ?>
                
                <div class="reservation-card">
                    <div class="reservation-header">
                        <span class="reservation-id">Réservation #<?= htmlspecialchars($reservation['id']) ?></span>
                        <span class="status <?= $statusClass ?>">
                            <?= ucfirst(htmlspecialchars($reservation['statut'])) ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($reservation['chambre_image'])): ?>
                        <img src="<?= htmlspecialchars($reservation['chambre_image']) ?>" alt="Chambre <?= htmlspecialchars($reservation['chambre_type']) ?>" class="chambre-image">
                    <?php endif; ?>
                    
                    <div class="reservation-details">
                        <div class="detail-item">
                            <div class="detail-label">Chambre</div>
                            <div class="detail-value"><?= htmlspecialchars($reservation['chambre_type']) ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Prix/nuit</div>
                            <div class="detail-value"><?= number_format($reservation['chambre_prix'], 2) ?> €</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Dates</div>
                            <div class="detail-value">
                                <?= $dateDebut->format('d/m/Y') ?> - <?= $dateFin->format('d/m/Y') ?>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Durée</div>
                            <div class="detail-value"><?= $nuits ?> nuit<?= $nuits > 1 ? 's' : '' ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Prix total</div>
                            <div class="detail-value"><?= number_format($prixTotal, 2) ?> €</div>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <a href="details_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-details">
                            <i class="fas fa-info-circle"></i> Détails
                        </a>
                        
                        <?php if ($reservation['statut'] == 'en_attente') : ?>
                            <button onclick="if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) window.location.href='annuler_reservation.php?id=<?= $reservation['id'] ?>'" 
                                    class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Menu mobile
    const menuToggle = document.getElementById('menuToggle');
    const menuItems = document.getElementById('menuItems');
    
    menuToggle.addEventListener('click', function() {
        menuItems.classList.toggle('active');
    });

    // Fermer le menu quand un lien est cliqué (mobile)
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