<?php
require_once 'connexion.php';
session_start();

$resultats = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $sql = "SELECT * FROM chambres 
            WHERE type = ? AND id NOT IN (
                SELECT chambre_id FROM reservations
                WHERE (date_debut < ? AND date_fin > ?)
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$type, $date_fin, $date_debut]);
    $resultats = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Chambres - EasyStay</title>
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
            font-size: 2.5rem;
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

        /* Search Form */
        .search-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 3rem;
            margin: 0 auto 4rem;
            max-width: 800px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--accent-color);
        }

        .form-group select,
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

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .search-btn {
            grid-column: 1 / -1;
            padding: 1rem;
            background: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .search-btn:hover {
            background: #e8c252;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        /* Results Section */
        .results-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .results-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
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
            font-size: 1.5rem;
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

        .book-btn {
            display: block;
            padding: 0.9rem;
            background: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .book-btn:hover {
            background: #e8c252;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        .no-results i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        .no-results h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
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
            }

            .navbar a {
                margin: 0.5rem 0;
                width: 100%;
                text-align: center;
            }

            .main-container {
                padding: 3rem 1.5rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .search-container {
                padding: 2rem;
            }
        }

        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .rooms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="logo"><i class="fas fa-hotel"></i> EasyStay</div>
        <div class="menu-items">
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
    </nav>

    <div class="main-container">
        <div class="page-header">
            <h1>Rechercher une chambre</h1>
            <p>Trouvez la chambre parfaite pour votre séjour chez EasyStay</p>
        </div>

        <div class="search-container">
            <form method="post" class="search-form">
                <div class="form-group">
                    <label for="type">Type de chambre</label>
                    <select name="type" id="type" required>
                        <option value="Simple">Simple</option>
                        <option value="Double">Double</option>
                        <option value="Supérieure">Supérieure</option>
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

                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </form>
        </div>

        <div class="results-container">
            <?php if (!empty($resultats)) : ?>
                <h2 class="results-title">Chambres disponibles</h2>
                
                <div class="rooms-grid">
                    <?php foreach ($resultats as $chambre) : 
                        // Déterminer l'image en fonction du type de chambre
                        $image = "images/simple.jpg";
                        if (strtolower($chambre['type']) == 'double') {
                            $image = "images/double.jpg";
                        } elseif (strtolower($chambre['type']) == 'supérieure') {
                            $image = "images/sup.jpg";
                        }
                    ?>
                        <div class="room-card">
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($chambre['type']) ?>" class="room-img">
                            
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
                                </div>
                                
                                <div class="room-price">
                                    <?= htmlspecialchars($chambre['prix']) ?> € <span>/ nuit</span>
                                </div>
                                
                                <a href="reservation.php?chambre_id=<?= $chambre['id'] ?>&debut=<?= htmlspecialchars($_POST['date_debut'] ?? '') ?>&fin=<?= htmlspecialchars($_POST['date_fin'] ?? '') ?>" class="book-btn">
                                    <i class="fas fa-calendar-check"></i> Réserver maintenant
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
                <div class="no-results">
                    <i class="fas fa-door-closed"></i>
                    <h3>Aucune chambre disponible</h3>
                    <p>Nous n'avons trouvé aucune chambre correspondant à vos critères de recherche. Veuillez essayer d'autres dates ou un autre type de chambre.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Initialisation des dates
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_debut').min = today;
            document.getElementById('date_fin').min = today;
            
            // Synchronisation des dates
            document.getElementById('date_debut').addEventListener('change', function() {
                document.getElementById('date_fin').min = this.value;
            });
        });

        // Animation des cartes
        function animateCards() {
            const cards = document.querySelectorAll('.room-card');
            
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease ' + (index * 0.1) + 's';
                
                const cardPosition = card.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;
                
                if (cardPosition < screenPosition) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            });
        }

        // Détection du scroll pour les animations
        window.addEventListener('load', animateCards);
        window.addEventListener('scroll', animateCards);
    </script>
</body>
</html>