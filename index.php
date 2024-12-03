<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
// Requête SQL avec tri des ID dans l'ordre croissant
$sql = "SELECT * FROM users ORDER BY id ASC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDEV</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container pt-4">
        <!-- Bouton de déconnexion -->
        <div class="d-flex justify-content-end">
            <a href="http://localhost/PROJET/logout.php" class="btn btn-danger">Logout</a>
        </div>
        <h1>Users List</h1>

        <!-- Bouton pour afficher le formulaire d'ajout -->
        <div id="add-user-button" class="mb-3">
            <button class="btn btn-success w-100" onclick="showAddForm()">Add a user</button>
        </div>

        <!-- Formulaire d'ajout caché par défaut -->
        <div id="add-user-form" style="display: none;">
            <form action="add.php" method="post">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
                <input type="password" class="form-control" placeholder="Password" name="pass" required>
                <select name="role" id="" class="form-select" required>
                    <option value="">Choose a role</option>
                    <option value="guest">Guest</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="d-flex justify-content-between mt-2">
                    <button class="btn btn-success">Confirm add</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddForm()">Annuler ajout</button>
                </div>
            </form>
        </div>

        <table class="table table-stripped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EMAIL</th>
                    <th>PASSWORD</th>
                    <th>ROLE</th>
        
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as  $row) : ?>
                    <tr>
                        <td class="text-center"><?= $row['id'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['password'] ?></td>
                        <td><?= $row['role'] ?></td>
                        <td class="text-center"><a href="delete.php?idd=<?= $row['id'] ?>" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a></td>
                        <td class="text-center"><a href="editer.php?idd=<?= $row['id'] ?>" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fonction pour afficher le formulaire
        function showAddForm() {
            document.getElementById('add-user-form').style.display = 'block';
            document.getElementById('add-user-button').style.display = 'none';
        }

        // Fonction pour cacher le formulaire
        function hideAddForm() {
            document.getElementById('add-user-form').style.display = 'none';
            document.getElementById('add-user-button').style.display = 'block';
        }
    </script>
</body>

</html>
