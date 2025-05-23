<?php
require_once 'connexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?");
    $stmt->execute([$email, $mot_de_passe]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        $_SESSION['utilisateur'] = $utilisateur;

        if ($utilisateur['role'] === 'propriétaire') {
            header("Location: index2.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - EasyStay</title>
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

        .login-container {
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

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--accent-color);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: rgba(255,255,255,0.7);
        }

        .login-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: var(--accent-color);
            font-weight: 500;
        }

        .login-form input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            color: var(--light-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .login-form input:focus {
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

        .login-links {
            margin-top: 25px;
            font-size: 0.9rem;
        }

        .login-links a {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: var(--danger-color);
            background: rgba(220, 53, 69, 0.1);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-message i {
            margin-right: 8px;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-sign-in-alt"></i> Connexion</h1>
            <p>Accédez à votre compte EasyStay</p>
        </div>

        <?php if (!empty($erreur)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?= $erreur ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="login-links">
            <p>Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
            
        </div>
    </div>
</body>
</html>
