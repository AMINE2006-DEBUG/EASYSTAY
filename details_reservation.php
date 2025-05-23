<?php
require_once 'connexion.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: reservation_form.php');
    exit;
}

$reservation_id = $_GET['id'];
$utilisateur_id = $_SESSION['utilisateur']['id'];

// Récupération des détails de la réservation
$stmt = $pdo->prepare("
    SELECT r.*, c.type AS chambre_type, c.prix AS chambre_prix, 
           c.description AS chambre_description, c.image AS chambre_image,
           u.nom AS client_nom, u.email AS client_email
    FROM reservations r
    JOIN chambres c ON r.chambre_id = c.id
    JOIN utilisateurs u ON r.utilisateur_id = u.id
    WHERE r.id = ? AND r.utilisateur_id = ?
");
$stmt->execute([$reservation_id, $utilisateur_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    $_SESSION['error'] = "Réservation introuvable ou vous n'avez pas les droits";
    header('Location: reservation_form.php');
    exit;
}

// Formatage des dates
$dateDebut = new DateTime($reservation['date_debut']);
$dateFin = new DateTime($reservation['date_fin']);
$nuits = $dateDebut->diff($dateFin)->days;
$prixTotal = $nuits * $reservation['chambre_prix'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Réservation - EasyStay</title>
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
            max-width: 1000px;
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

        /* Reservation Details */
        .reservation-details {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .reservation-details::before {
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
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .reservation-id {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
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

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-section h2 {
            color: var(--accent-color);
            font-size: 1.3rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .detail-item {
            display: flex;
            margin-bottom: 15px;
        }

        .detail-label {
            width: 150px;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
        }

        .detail-value {
            flex: 1;
            font-weight: 500;
        }

        .chambre-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .chambre-description {
            line-height: 1.8;
            color: rgba(255,255,255,0.8);
        }

        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-back {
            background-color: rgba(255,255,255,0.1);
            color: var(--light-text);
        }

        .btn-back:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }

        .btn-cancel {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
        }

        .btn-cancel:hover {
            background-color: rgba(220, 53, 69, 0.3);
            transform: translateY(-3px);
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

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .reservation-details {
                padding: 20px;
            }
            
            .reservation-id {
                font-size: 1.2rem;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
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
    <h1><i class="fas fa-calendar-alt"></i> Détails de la Réservation</h1>
    
    <div class="reservation-details">
        <div class="reservation-header">
            <div class="reservation-id">Réservation #<?= htmlspecialchars($reservation['id']) ?></div>
            <div class="status status-<?= strtolower(str_replace('ée', 'e', $reservation['statut'])) ?>">
                <?= ucfirst(htmlspecialchars($reservation['statut'])) ?>
            </div>
        </div>
        
        <div class="detail-grid">
            <div class="detail-section">
                <h2><i class="fas fa-hotel"></i> Chambre réservée</h2>
                
                <?php if (!empty($reservation['chambre_image'])): ?>
                    <img src="<?= htmlspecialchars($reservation['chambre_image']) ?>" alt="<?= htmlspecialchars($reservation['chambre_type']) ?>" class="chambre-image">
                <?php endif; ?>
                
                <div class="detail-item">
                    <div class="detail-label">Type de chambre:</div>
                    <div class="detail-value"><?= htmlspecialchars($reservation['chambre_type']) ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Prix par nuit:</div>
                    <div class="detail-value"><?= number_format($reservation['chambre_prix'], 2) ?> €</div>
                </div>
                
                <h3>Description</h3>
                <p class="chambre-description">
                    <?= !empty($reservation['chambre_description']) ? htmlspecialchars($reservation['chambre_description']) : 'Aucune description disponible' ?>
                </p>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-calendar-day"></i> Dates de séjour</h2>
                
                <div class="detail-item">
                    <div class="detail-label">Date d'arrivée:</div>
                    <div class="detail-value"><?= $dateDebut->format('d/m/Y') ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Date de départ:</div>
                    <div class="detail-value"><?= $dateFin->format('d/m/Y') ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Nombre de nuits:</div>
                    <div class="detail-value"><?= $nuits ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Prix total:</div>
                    <div class="detail-value"><?= number_format($prixTotal, 2) ?> €</div>
                </div>
                
                <?php if (!empty($reservation['date_annulation'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">Date d'annulation:</div>
                        <div class="detail-value">
                            <?= (new DateTime($reservation['date_annulation']))->format('d/m/Y à H:i') ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-user"></i> Informations client</h2>
                
                <div class="detail-item">
                    <div class="detail-label">Nom:</div>
                    <div class="detail-value"><?= htmlspecialchars($reservation['client_nom']) ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value"><?= htmlspecialchars($reservation['client_email']) ?></div>
                </div>
                
                <?php if (!empty($reservation['client_telephone'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">Téléphone:</div>
                        <div class="detail-value"><?= htmlspecialchars($reservation['client_telephone']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
            

                
                <?php if (!empty($reservation['commentaires'])): ?>
                    <div class="detail-item">
                        <div class="detail-label">Commentaires:</div>
                        <div class="detail-value"><?= htmlspecialchars($reservation['commentaires']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="actions">
            <a href="reservation_form.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            
            <?php if ($reservation['statut'] == 'en_attente'): ?>
                <button onclick="if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) window.location.href='annuler_reservation.php?id=<?= $reservation['id'] ?>'" 
                        class="btn btn-cancel">
                    <i class="fas fa-times"></i> Annuler
                </button>
            <?php endif; ?>
        </div>
    </div>
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