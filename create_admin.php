<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ecomme");

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Informations de l'utilisateur
$username = 'admin';
$email = 'admin@example.com';
$password = 'admin'; // Mot de passe en clair
$role = 'admin';

// Hacher le mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Préparer la requête d'insertion
$stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $hashed_password, $email, $role);

// Exécuter la requête
if ($stmt->execute()) {
    echo "Utilisateur admin inséré avec succès.";
} else {
    echo "Erreur lors de l'insertion : " . $stmt->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
