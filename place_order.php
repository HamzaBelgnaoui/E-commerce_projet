<?php
// Include the database connection
require 'db_connect.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $name = isset($input['name']) ? htmlspecialchars($input['name']) : '';
    $email = isset($input['email']) ? htmlspecialchars($input['email']) : '';
    $orderData = isset($input['items']) ? $input['items'] : [];

    if (!empty($name) && !empty($email) && !empty($orderData)) {
        try {
            $sql = "INSERT INTO orders (customer_name, customer_email, order_details, order_date) 
                    VALUES (:name, :email, :order_details, NOW())";

            $stmt = $pdo->prepare($sql);
            $order_details_json = json_encode($orderData);

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':order_details' => $order_details_json
            ]);

            $orderId = $pdo->lastInsertId();

            echo json_encode([
                "success" => true,
                "message" => "Commande enregistrée avec succès",
                "orderId" => $orderId
            ]);

        } catch(PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Erreur lors de l'enregistrement de la commande: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Données de commande invalides ou incomplètes."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Méthode de requête invalide."
    ]);
}
?>