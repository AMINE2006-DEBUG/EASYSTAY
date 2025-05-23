<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=easystay2', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

// Vérifier le rôle
if ($_SESSION['utilisateur']['role'] !== 'propriétaire') {
    header('Location: index.php');
    exit;
}

// Récupérer les réservations
$stmt_reservations = $pdo->prepare("
    SELECT r.id, u.nom AS client, c.type AS chambre, r.date_debut, r.date_fin
    FROM reservations r
    JOIN utilisateurs u ON r.utilisateur_id = u.id
    JOIN chambres c ON r.chambre_id = c.id
");
$stmt_reservations->execute();
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis
$stmt_avis = $pdo->prepare("
    SELECT avis.*, utilisateurs.nom AS client, chambres.type 
    FROM avis 
    INNER JOIN chambres ON avis.chambre_id = chambres.id 
    INNER JOIN utilisateurs ON avis.utilisateur_id = utilisateurs.id
");
$stmt_avis->execute();
$avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Propriétaire - EasyStay</title>
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
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--dark-bg);
            color: var(--light-text);
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color), #252b42);
            color: var(--accent-color);
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 255, 231, 0.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        header h1 {
            font-size: 26px;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }

        header h1 i {
            margin-right: 10px;
        }

        nav a {
            color: var(--accent-color);
            text-decoration: none;
            margin-left: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 6px;
        }

        nav a:hover {
            background: rgba(212, 175, 55, 0.2);
        }

        .welcome-section {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 255, 231, 0.05);
            max-width: 1000px;
            margin: 40px auto;
            padding: 40px;
            text-align: center;
        }

        .welcome-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 255, 231, 0.05);
            padding: 30px;
            margin-bottom: 40px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 255, 231, 0.1);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 20px;
            color: var(--accent-color);
            border-bottom: 1px solid rgba(0, 255, 231, 0.2);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .card h3 i {
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid rgba(0, 255, 231, 0.1);
            text-align: left;
        }

        th {
            background: rgba(0, 255, 231, 0.1);
            color: var(--accent-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        tbody tr:hover {
            background: rgba(0, 255, 231, 0.03);
        }

        .rating {
            color: var(--accent-color);
        }

        .empty-rating {
            color: rgba(255,255,255,0.2);
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 20px;
                text-align: center;
            }

            nav {
                margin-top: 15px;
            }

            nav a {
                margin: 0 10px;
            }

            .welcome-section, .card {
                padding: 25px;
            }

            th, td {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-user-shield"></i> EasyStay | Espace Propriétaire</h1>
        <nav>
            <a href="index2.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </nav>
    </header>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h2>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['nom'] ?? 'Propriétaire') ?> !</h2>
            <p>Gérez vos réservations et consultez les avis clients depuis votre espace personnel.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-calendar-check"></i> Réservations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['client']) ?></td>
                                <td><?= htmlspecialchars($r['chambre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($r['date_debut'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($r['date_fin'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Aucune réservation trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3><i class="fas fa-star"></i> Avis Clients</h3>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Chambre</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['client']) ?></td>
                                <td><?= htmlspecialchars($a['type']) ?></td>
                                <td class="rating">
                                    <?php 
                                    $fullStars = (int)$a['note'];
                                    $emptyStars = 5 - $fullStars;
                                    
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star empty-rating"></i>';
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($a['commentaire']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Aucun avis trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Animation pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 150 * index);
            });
        });
    </script>
</body>
</html>

