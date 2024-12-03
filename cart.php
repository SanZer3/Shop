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
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>

<body>
    <div class="container pt-5">
        <h1>Your Cart</h1>

        <?php if (empty($_SESSION['panier'])): ?>
            <p>Your cart is empty.</p>
            <!-- Bouton "Continue Shopping" -->
            <a href="com.php" class="btn btn-secondary btn-lg mt-3">Continue Shopping</a>
        <?php else: ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($_SESSION['panier'] as $id => $qte):
                        // Rechercher le produit dans le fichier JSON
                        $product = array_filter($products, function ($prod) use ($id) {
                            return $prod['id'] == $id;
                        });
                        $product = reset($product); // Récupérer le premier produit correspondant

                        if ($product): // Vérifier que le produit existe
                            $subtotal = $product['price'] * $qte; // Calculer le total pour cet article
                            $total += $subtotal; // Ajouter au total général
                    ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= htmlspecialchars($product['title']) ?></td>
                            <td>
                                <!-- Formulaire pour mettre à jour la quantité -->
                                <form action="updateQuantity.php" method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="number" name="quantity" value="<?= $qte ?>" min="1" class="form-control me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td><?= $product['price'] ?> €</td>
                            <td><?= $subtotal ?> €</td>
                            <td>
                                <a href="removeFromCart.php?id=<?= $id ?>" class="btn btn-sm btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="6">Product not found for ID: <?= $id ?></td></tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: <?= $total ?> €</h3>

            <!-- Boutons "Continue Shopping" et "Validate Cart" -->
            <div class="d-flex justify-content-between mt-4">
                <a href="com.php" class="btn btn-secondary btn-lg">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-success btn-lg">Validate Cart</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
