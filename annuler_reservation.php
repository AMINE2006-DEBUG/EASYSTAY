<?php
require_once 'connexion.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Aucune réservation spécifiée";
    header('Location: reservation_form.php');
    exit;
}

$reservation_id = $_GET['id'];
$utilisateur_id = $_SESSION['utilisateur']['id'];

// Vérification de la réservation
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND utilisateur_id = ?");
$stmt->execute([$reservation_id, $utilisateur_id]);
$reservation = $stmt->fetch();

if (!$reservation) {
    $_SESSION['error'] = "Réservation introuvable ou vous n'avez pas les droits";
    header('Location: reservation_form.php');
    exit;
}

$stmt = $pdo->prepare("UPDATE reservations SET statut = 'annulée' WHERE id = ?");
if ($stmt->execute([$reservation_id])) {
    $_SESSION['success'] = "Réservation #$reservation_id annulée avec succès";
} else {
    $_SESSION['error'] = "Erreur lors de l'annulation";
}

header('Location: reservation_form.php');
exit;
?>