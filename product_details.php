<?php
// product_details.php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if (!$product) {
        header("Location: products.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching product: " . $e->getMessage());
}
?>

<div class="container py-5 animate-fade-in">
    <div class="row g-5">
        <div class="col-md-6">
            <div class="glass-card p-2">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid rounded-4 shadow-lg w-100">
            </div>
        </div>
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <div class="p-4">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item"><a href="products.php" class="text-primary text-decoration-none small">Marketplace</a></li>
                        <li class="breadcrumb-item active text-white opacity-50 small"><?php echo htmlspecialchars($product['category']); ?></li>
                    </ol>
                </nav>
                <h1 class="fw-bold mb-3 display-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="d-flex align-items-center mb-4">
                    <span class="price-tag fs-1 me-4">$<?php echo number_format($product['price'], 2); ?></span>
                    <span class="badge bg-primary rounded-pill px-3 py-2"><?php echo htmlspecialchars($product['condition']); ?></span>
                </div>

                <div class="glass-card mb-4 p-4 border-0" style="background: rgba(255, 255, 255, 0.02);">
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i> Description</h5>
                    <p class="text-muted fs-5 lh-lg"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-6">
                        <p class="mb-1 text-muted small text-uppercase">Seller</p>
                        <p class="fw-bold fs-6 mb-0 text-white"><i class="fas fa-user-circle me-1 text-primary"></i> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1 text-muted small text-uppercase">Availability</p>
                        <p class="fw-bold fs-6 mb-0 <?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $product['stock'] > 0 ? htmlspecialchars($product['stock']) . " in stock" : "Out of stock"; ?>
                        </p>
                    </div>
                </div>

                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="input-group mb-4" style="max-width: 150px;">
                        <input type="number" name="quantity" class="form-control rounded-start-pill py-3 px-3 text-center" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        <span class="input-group-text bg-transparent border-primary-subtle text-muted fs-5 px-3">QTY</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fs-5 shadow-lg animation-pulse <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Add to Nexus Cart
                    </button>
                    <p class="text-center mt-3 small text-muted"><i class="fas fa-shield-alt text-primary me-1"></i> Secure Transaction Guarantee</p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
