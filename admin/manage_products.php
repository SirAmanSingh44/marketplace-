<?php
// admin/manage_products.php
session_start();
require_once '../includes/db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

// Delete Product
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$del_id]);
        $success = "Listing #$del_id has been removed from marketplace.";
    } catch (PDOException $e) {
        $error = "Error removing listing: " . $e->getMessage();
    }
}

// Add Product - Simplified for now
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $img = $_POST['image_url'];
    $cat = $_POST['category'];
    $cond = $_POST['condition'];
    $stock = $_POST['stock'];
    $seller = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, category, `condition`, stock, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $desc, $price, $img, $cat, $cond, $stock, $seller]);
        $success = "New treasure successfully listed on Nexus Market!";
    } catch (PDOException $e) {
        $error = "Failed to list item: " . $e->getMessage();
    }
}

// Fetch all products
try {
    $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    die("Error fetching inventory: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <title>Manage Inventory | Nexus Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="d-flex flex-column h-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="dashboard.php" style="background: linear-gradient(45deg, #6366f1, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">NEXUS INVENTORY</a>
        <div class="ms-auto">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">Back to Panel</a>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5 flex-shrink-0 animate-fade-in">
    <div class="row g-5">
        <!-- List New Product Form -->
        <div class="col-lg-4">
            <div class="glass-card p-4 border-0 shadow-lg">
                <h4 class="fw-bold mb-5"><i class="fas fa-plus-circle text-primary me-2"></i> List New Item</h4>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-pill text-center mb-4 small"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success bg-success text-white border-0 py-2 rounded-pill text-center mb-4 small"><?php echo $success; ?></div>
                <?php endif; ?>

                <form action="manage_products.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Title</label>
                        <input type="text" name="name" class="form-control" required placeholder="Product Title">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Price ($)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Stock</label>
                        <input type="number" name="stock" class="form-control" required value="1">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Category</label>
                        <select name="category" class="form-control bg-dark" required>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Books">Books</option>
                            <option value="Collectibles">Collectibles</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Condition</label>
                        <select name="condition" class="form-control bg-dark" required>
                            <option value="New">New</option>
                            <option value="Like New" selected>Like New</option>
                            <option value="Used">Used</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Image URL</label>
                        <input type="url" name="image_url" class="form-control" required placeholder="https://...">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Details</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe your treasure..." required></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">Broadcast Listing</button>
                </form>
            </div>
        </div>

        <!-- Inventory List -->
        <div class="col-lg-8">
            <div class="glass-card p-4 border-0 shadow-lg">
                <h4 class="fw-bold mb-5">Marketplace Inventory (<?php echo count($products); ?>)</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover border-0 align-middle mb-0">
                        <thead class="border-light opacity-25">
                            <tr>
                                <th class="py-4 border-0 bg-transparent">ID</th>
                                <th class="py-4 border-0 bg-transparent">Product</th>
                                <th class="py-4 border-0 bg-transparent">Category</th>
                                <th class="py-4 border-0 bg-transparent">Price</th>
                                <th class="py-4 border-0 bg-transparent text-center">In-Market</th>
                                <th class="py-4 border-0 bg-transparent text-center">Live Controls</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                                <tr class="border-light opacity-50">
                                    <td class="py-4 border-0 bg-transparent fw-bold text-primary">#<?php echo $product['id']; ?></td>
                                    <td class="py-4 border-0 bg-transparent">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="rounded-3 shadow-sm me-3" width="50" height="50" style="object-fit: cover;">
                                            <span class="text-white"><?php echo htmlspecialchars($product['name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-4 border-0 bg-transparent text-muted small"><?php echo $product['category']; ?></td>
                                    <td class="py-4 border-0 bg-transparent text-white fw-bold">$<?php echo number_format($product['price'], 2); ?></td>
                                    <td class="py-4 border-0 bg-transparent text-center">
                                        <span class="badge rounded-pill bg-opacity-25 px-3 <?php echo $product['stock'] > 0 ? 'bg-success text-success' : 'bg-danger text-danger'; ?>">
                                            <?php echo $product['stock']; ?>
                                        </span>
                                    </td>
                                    <td class="py-4 border-0 bg-transparent text-center">
                                        <a href="manage_products.php?delete=<?php echo $product['id']; ?>" class="btn btn-outline-danger btn-sm border-0 rounded-circle p-2 shadow-sm" onclick="return confirm('Archive this treasure from marketplace?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer mt-auto bg-transparent py-4 text-center border-top border-light border-opacity-10 text-muted small">
    &copy; <?php echo date("Y"); ?> Nexus Admin Terminal. Precision control over commerce.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
