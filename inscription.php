<?php
require_once 'connexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mdp = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $email, $mdp, $role]);

    $_SESSION['inscription_success'] = true;
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EasyStay</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-container {
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .register-header {
            margin-bottom: 30px;
        }

        .register-header h1 {
            color: var(--accent-color);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .register-header p {
            color: rgba(255,255,255,0.7);
        }

        .register-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .register-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--accent-color);
            font-weight: 500;
        }

        .register-form input,
        .register-form select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            color: var(--light-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .register-form select option {
            background: var(--card-bg);
        }

        .register-form input:focus,
        .register-form select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
            background: var(--accent-color);
            color: var(--primary-color);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }

        .btn i {
            margin-right: 10px;
        }

        .register-links {
            margin-top: 25px;
            font-size: 0.9rem;
        }

        .register-links a {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .register-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 30px 20px;
            }
            
            .register-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-user-plus"></i> Inscription</h1>
            <p>Créez votre compte EasyStay</p>
        </div>

        <form class="register-form" method="POST">
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom complet</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Votre mot de passe" required>
            </div>

            <div class="form-group">
                <label for="role"><i class="fas fa-user-tag"></i> Rôle</label>
                <select id="role" name="role" required>
                    <option value="">-- Sélectionnez votre rôle --</option>
                    <option value="client">Client</option>
                    <option value="propriétaire">Propriétaire</option>
                </select>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-user-plus"></i> S'inscrire
            </button>
        </form>

        <div class="register-links">
            <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>
</body>
</html>