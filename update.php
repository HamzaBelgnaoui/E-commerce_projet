<?php
// Include the database connection
require 'db_connect.php';

// Get the order ID from the URL
$id = $_GET['id'];

// Fetch the order details
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
$stmt->execute(['id' => $id]);
$order = $stmt->fetch(PDO::FETCH_OBJ);

if (!$order) {
    die("Commande non trouvée.");
}

// Update order details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $order_details = $_POST['order_details']; // Assuming this is JSON encoded

    $sql = 'UPDATE orders SET customer_name = :customer_name, customer_email = :customer_email, order_details = :order_details WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'customer_name' => $customer_name,
        'customer_email' => $customer_email,
        'order_details' => $order_details,
        'id' => $id
    ]);

    header('Location: admin_orders.php'); // Redirect to the orders admin page after updating
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la commande</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">Modifier la commande</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="customer_name">Nom du client :</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($order->customer_name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="customer_email">Email du client :</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($order->customer_email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="order_details">Détails de la commande :</label>
                        <textarea class="form-control" id="order_details" name="order_details" rows="5" required><?php echo htmlspecialchars($order->order_details); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="admin_orders.php" class="btn btn-secondary ml-2">Retour à la liste des commandes</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
