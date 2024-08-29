<?php
session_start();

// Vérification de l'authentification et du rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: projet2_Ecommerce.html");
    exit();
}

// Include the database connection
require 'db_connect.php';

// Fetch orders from the database
try {
    $sql = "SELECT * FROM orders ORDER BY order_date DESC";
    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - Administration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #collapse1 {
            overflow-y: scroll;
            height: 400px;
        }
        .my-4 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Commandes - Administration</h1>
        <p>Connecté en tant que : <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</p>
        <a href="logout.php" class="btn btn-danger mb-3">Se déconnecter</a>
        <div id="collapse1" class="panel-collapse collapse show">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom du client</th>
                            <th>Email du client</th>
                            <th>Panier</th>
                            <th>Total</th>
                            <th>Date de création</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders) {
                            foreach ($orders as $order) {
                                $orderDetails = json_decode($order['order_details'], true);
                                $total = 0;
                                foreach ($orderDetails as $item) {
                                    $total += $item['price'] * $item['quantity'];
                                }
                        ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($orderDetails as $item) { ?>
                                    <li><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</li>
                                    <?php } ?>
                                </ul>
                            </td>
                            <td><?php echo number_format($total, 2); ?> €</td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>
                                <a href="update.php?id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('delete.php?id=<?php echo $order['id']; ?>')">Delete</button>
                            </td>
                        </tr>
                        <?php } } else { ?>
                        <tr><td colspan='7'>Aucune commande trouvée</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(url) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cette commande ?")) {
                window.location.href = url;
            }
        }
    </script>
</body>
</html>
