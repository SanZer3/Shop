<?php
session_start();

// Charger les produits depuis le fichier JSON
$products = json_decode(file_get_contents("./products.json"), true);
$total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>

<body>
    <div class="container pt-5">
        <h1>Order Confirmation</h1>

        <?php if (empty($_SESSION['panier'])): ?>
            <p>Your cart is empty. Please add some products before validating your cart.</p>
            <a href="com.php" class="btn btn-secondary">Go Back to Products</a>
        <?php else: ?>
            <h3>Thank you for your order!</h3>
            <p>Here is a summary of your purchase:</p>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($_SESSION['panier'] as $id => $qte):
                        $product = array_filter($products, function ($prod) use ($id) {
                            return $prod['id'] == $id;
                        });
                        $product = reset($product);

                        if ($product):
                            $subtotal = $product['price'] * $qte;
                            $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= htmlspecialchars($product['title']) ?></td>
                            <td><?= $qte ?></td>
                            <td><?= $product['price'] ?> €</td>
                            <td><?= $subtotal ?> €</td>
                        </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: <?= $total ?> €</h3>

            <p>Your order has been successfully validated. You will receive a confirmation email shortly.</p>
        <?php endif; ?>

        <!-- Vider le panier après validation -->
        <?php $_SESSION['panier'] = []; ?>

        <a href="com.php" class="btn btn-primary mt-4">Return to Shop</a>
    </div>
</body>

</html>
