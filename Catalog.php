<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get selected category
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Fetch products
if ($selectedCategory > 0) {
    $products = mysqli_query(
        $conn,
        "SELECT * FROM products WHERE category_id = $selectedCategory"
    );
} else {
    $products = mysqli_query($conn, "SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container">
            <h1>🛒 My E-Commerce Store</h1>
            <nav>
                <a href="catalog.php" class="active">Catalog</a>
                <a href="cart.php">Cart</a>
                <a href="api/logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container main-content">

        <aside class="sidebar">
            <h2>Categories</h2>
            <ul class="category-list">
                <li>
                    <a href="catalog.php">All</a>
                </li>
                <?php while ($cat = mysqli_fetch_assoc($categories)) : ?>
                <li>
                    <a href="catalog.php?category=<?= $cat['id'] ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                </li>
                <?php endwhile; ?>
            </ul>
        </aside>

        <main class="products-section">
            <h2>Products</h2>
            <div class="products-grid">
                <?php if (mysqli_num_rows($products) === 0): ?>
                <p>No products found.</p>
                <?php endif; ?>

                <?php while ($p = mysqli_fetch_assoc($products)) : ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p><?= htmlspecialchars($p['description']) ?></p>
                    <strong>$<?= number_format($p['price'], 2) ?></strong>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
        </main>

    </div>

</body>

</html>