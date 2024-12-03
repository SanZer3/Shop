<?php
session_start();

// Vérifier si la session 'user' existe. Si non, l'initialiser avec les valeurs par défaut.
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'role' => 'guest',      // Par défaut, l'utilisateur est un invité
        'isLoggedIn' => false   // Par défaut, l'utilisateur n'est pas connecté
    ];
}

// Vérifier si les clés 'role' et 'isLoggedIn' sont bien définies dans la session
if (!isset($_SESSION['user']['role'])) {
    $_SESSION['user']['role'] = 'guest';  // Valeur par défaut si la clé n'existe pas
}

if (!isset($_SESSION['user']['isLoggedIn'])) {
    $_SESSION['user']['isLoggedIn'] = false;  // Valeur par défaut si la clé n'existe pas
}

// Charger les produits depuis le fichier JSON
$products = json_decode(file_get_contents("./products.json"), true);

// Vérifier si la session 'panier' existe, sinon l'initialiser
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Si l'utilisateur est admin et connecté, rediriger vers index.php
if ($_SESSION['user']['role'] === 'admin' && $_SESSION['user']['isLoggedIn']) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
.button-container {
    position: fixed;
    top: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
    z-index: 9999;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.button-hidden {
    opacity: 0;  /* Cache les boutons */
    transform: translateY(-50px);  /* Déplace les boutons hors de l'écran */
}

.view-cart-btn,
.manage-account-btn,
.auth-btn {
    width: auto;
    white-space: nowrap;  /* Empêche le retour à la ligne dans les boutons */
}

.auth-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 9999;
}

.container .row {
    row-gap: 50px;
}

.product-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    transition: transform 0.3s;
    height: 380px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-card:hover {
    transform: scale(1.05);
}

.product-image {
    max-width: 100%;
    height: 150px;
    object-fit: contain;
    border-radius: 8px;
}

.product-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.product-price {
    color: #28a745;
    font-size: 1.1rem;
}

.product-description {
    font-size: 0.9rem;
    color: #777;
}

.add-to-cart-btn {
    width: 100%;
}


    </style>
</head>

<body>
    

<!-- Bouton de gestion utilisateur -->
<?php if (!$_SESSION['user']['isLoggedIn']): ?>
    <a href="login.php" class="btn btn-primary auth-btn">Login</a>
<?php else: ?>
    <a href="logout.php" class="btn btn-danger auth-btn">Logout</a>
    <?php if ($_SESSION['user']['role'] === 'guest'): ?>
        <div class="button-container">
            <a href="manageAccount.php" class="btn btn-warning manage-account-btn">Manage Your Account</a>
            <a href="cart.php" class="btn btn-success view-cart-btn">View Cart</a>
        </div>
    <?php endif; ?>
<?php endif; ?>


    <!-- Affichage des produits -->
    <div class="container pt-4">
        <h1>Hello to our Shop! <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            
        </body>
        </html></h1>
        
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="<?= $product['images'][0] ?>" alt="<?= $product['title'] ?>" class="product-image">
                        <h3 class="product-title"><?= $product['title'] ?></h3>
                        <p class="product-description"><?= substr($product['description'], 0, 100) . '...' ?></p>
                        <p class="product-price"><?= $product['price'] ?> USD</p>
                        <button class="btn btn-primary add-to-cart-btn" onclick="add(<?= $product['id'] ?>)">
                            <i class="bi bi-basket"></i> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function add(id) {
            const qte = 1; // Par défaut, ajouter une quantité de 1
            window.location.href = `addToCart.php?id=${id}&qte=${qte}`; // Passer l'ID et la quantité
        }
    
    let lastScrollTop = 0;  // Position précédente du scroll
    const buttonContainer = document.querySelector('.button-container');
    
    // Fonction pour gérer l'apparence des boutons pendant le défilement
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        // Si on fait défiler la page vers le bas
        if (currentScroll > lastScrollTop) {
            buttonContainer.classList.add('button-hidden'); // Cacher les boutons
        } else {
            buttonContainer.classList.remove('button-hidden'); // Réafficher les boutons
        }
        
        // Mémoriser la position du scroll
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Empêche le scroll négatif
    });
</script>

</body>

</html>
