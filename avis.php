<?php
require_once 'connexion.php';
session_start();

$utilisateur_id = $_SESSION['utilisateur']['id'] ?? null;
$success_message = '';
$errors = [];

// Traitement ajout avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $utilisateur_id) {
    $chambre_id = $_POST['chambre_id'] ?? null;
    $note = $_POST['note'] ?? null;
    $commentaire = trim($_POST['commentaire'] ?? '');

    // Validation
    if (empty($chambre_id)) {
        $errors['chambre'] = "Veuillez sélectionner une chambre";
    }
    
    if (empty($note) || $note < 1 || $note > 5) {
        $errors['note'] = "Veuillez donner une note entre 1 et 5";
    }
    
    if (empty($commentaire) || strlen($commentaire) < 10) {
        $errors['commentaire'] = "Votre commentaire doit contenir au moins 10 caractères";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO avis (utilisateur_id, chambre_id, note, commentaire) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$utilisateur_id, $chambre_id, $note, $commentaire])) {
            $success_message = "Avis ajouté avec succès !";
        } else {
            $errors['general'] = "Une erreur s'est produite lors de l'enregistrement";
        }
    }
}

// Récupérer les avis
$stmt = $pdo->query("
    SELECT a.*, u.nom AS auteur, c.type AS type_chambre
    FROM avis a
    JOIN utilisateurs u ON a.utilisateur_id = u.id
    JOIN chambres c ON a.chambre_id = c.id
    ORDER BY a.id DESC
");
$avis = $stmt->fetchAll();

// Récupérer les chambres pour le formulaire
$chambres = $pdo->query("SELECT * FROM chambres")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis Clients - EasyStay</title>
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
            background-color: var(--dark-bg);
            color: var(--light-text);
            line-height: 1.6;
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
            transition: all 0.3s ease;
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
            transition: all 0.3s ease;
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
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
            position: relative;
        }

        .page-header h1::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Avis List */
        .avis-list {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .avis-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--accent-color);
        }

        .avis-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .avis-author {
            font-weight: bold;
            color: var(--accent-color);
        }

        .avis-room {
            font-style: italic;
            color: rgba(255,255,255,0.7);
        }

        .avis-rating {
            color: var(--accent-color);
            font-weight: bold;
        }

        .avis-content {
            margin-bottom: 1rem;
        }

        .avis-date {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            text-align: right;
        }

        /* Review Form */
        .review-form {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--accent-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            color: var(--light-text);
            font-size: 1rem;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem 1.5rem;
            background: var(--accent-color);
            color: var(--primary-color);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1rem;
            }

            .navbar .menu-items {
                flex-direction: column;
                margin-top: 1rem;
                width: 100%;
            }

            .navbar a {
                margin: 0.5rem 0;
                width: 100%;
                text-align: center;
            }

            .container {
                padding: 0 15px;
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

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-comments"></i> Avis des Clients</h1>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success_message ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $errors['general'] ?>
            </div>
        <?php endif; ?>

        <div class="avis-list">
            <?php if (empty($avis)): ?>
                <div class="avis-card" style="text-align: center;">
                    <p>Aucun avis pour le moment. Soyez le premier à donner votre avis !</p>
                </div>
            <?php else: ?>
                <?php foreach ($avis as $a): ?>
                    <div class="avis-card">
                        <div class="avis-header">
                            <span class="avis-author"><?= htmlspecialchars($a['auteur']) ?></span>
                            <span class="avis-rating"><?= htmlspecialchars($a['note']) ?>/5 ★</span>
                        </div>
                        <p class="avis-room">Chambre <?= htmlspecialchars($a['type_chambre']) ?></p>
                        <div class="avis-content">
                            <?= nl2br(htmlspecialchars($a['commentaire'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($utilisateur_id): ?>
            <div class="review-form">
                <h2><i class="fas fa-pencil-alt"></i> Laisser un avis</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="chambre_id">Type de chambre</label>
                        <select id="chambre_id" name="chambre_id" class="form-control" required>
                            <option value="">-- Sélectionnez une chambre --</option>
                            <?php foreach ($chambres as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['type']) ?> (<?= htmlspecialchars($c['capacité']) ?> pers)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['chambre'])): ?>
                            <span style="color: var(--danger-color); font-size: 0.9rem;"><?= $errors['chambre'] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="note">Note (1 à 5)</label>
                        <input type="number" id="note" name="note" class="form-control" min="1" max="5" required>
                        <?php if (!empty($errors['note'])): ?>
                            <span style="color: var(--danger-color); font-size: 0.9rem;"><?= $errors['note'] ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea id="commentaire" name="commentaire" class="form-control" required></textarea>
                        <?php if (!empty($errors['commentaire'])): ?>
                            <span style="color: var(--danger-color); font-size: 0.9rem;"><?= $errors['commentaire'] ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Envoyer l'avis
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="avis-card" style="text-align: center;">
                <p><a href="login.php" style="color: var(--accent-color);">Connectez-vous</a> pour laisser un avis.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>