<?php
require 'db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare the SQL statement to delete the order
        $sql = 'DELETE FROM orders WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement with the provided id
        $stmt->execute(['id' => $id]);
        
        // Redirect to the admin orders page after deletion
        header('Location: admin_orders.php');
        exit();
    } catch (PDOException $e) {
        // Handle the error if something goes wrong
        echo "Erreur lors de la suppression de la commande : " . $e->getMessage();
    }
} else {
    // Handle the case where the id is not set or is not numeric
    echo "ID invalide";
}

