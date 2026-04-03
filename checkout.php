<?php
// checkout.php
require_once 'includes/header.php';

// Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = '';

// Fetch Cart to Calculate Grand Total
try {
    $cartStmt = $pdo->prepare("
        SELECT c.quantity, p.id as product_id, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $cartStmt->execute([$user_id]);
    $cart_items = $cartStmt->fetchAll();
    
    if (count($cart_items) == 0 && !isset($_POST['place_order'])) {
        header("Location: cart.php");
        exit;
    }

    $grand_total = 0;
    foreach($cart_items as $item) {
        $grand_total += ($item['price'] * $item['quantity']);
    }
} catch (PDOException $e) {
    die("Error pre-calculating total: " . $e->getMessage());
}

// Order Finalization
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    try {
        $pdo->beginTransaction();

        // 1. Create Order record
        $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Pending')");
        $orderStmt->execute([$user_id, $grand_total]);
        $order_id = $pdo->lastInsertId();

        // 2. Transcribe items to order_items & deduct stock
        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($cart_items as $item) {
            $itemStmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
            $stockStmt->execute([$item['quantity'], $item['product_id']]);
        }

        // 3. Purge Cart
        $purgeStmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $purgeStmt->execute([$user_id]);

        $pdo->commit();
        $success = true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Payment fulfillment failed: " . $e->getMessage();
    }
}
?>

<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-7 glass-card p-5 animate-fade-in shadow-lg">
        <?php if ($success): ?>
            <div class="text-center py-5">
                <div class="p-3 bg-success bg-opacity-25 rounded-circle d-inline-flex mb-5 shadow-sm">
                    <i class="fas fa-check-circle text-success fs-1"></i>
                </div>
                <h2 class="fw-bold mb-3">Order Confirmed!</h2>
                <p class="text-muted fs-5 mb-5 px-5 opacity-75">Your payment was secured successfully. Our sellers are preparing your treasures for shipment.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php" class="btn btn-primary rounded-pill px-5 btn-lg shadow-lg">Back to Market</a>
                    <a href="orders.php" class="btn btn-outline-light rounded-pill px-5 btn-lg shadow-sm">Order History</a>
                </div>
            </div>
        <?php else: ?>
            <h2 class="fw-bold mb-4"><i class="fas fa-credit-card text-primary me-2"></i> Finalize Your Order</h2>
            <p class="text-muted mb-5">By placing this order, you agree to Nexus Market's Terms of Service and Privacy Policy.</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-pill text-center mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="glass-card mb-5 p-4 border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.02);">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Payable Amount</span>
                    <span class="fs-4 fw-bold text-primary">$<?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-0 border-top border-light border-opacity-25 pt-3 small text-muted">
                    <span>Payment Method</span>
                    <span class="fw-bold text-white"><i class="fab fa-cc-visa me-1 fs-6"></i> Wallet/Card</span>
                </div>
            </div>

            <form action="checkout.php" method="POST">
                <div class="mb-5">
                    <h5 class="fw-bold mb-4 opacity-75">Shipping Address</h5>
                    <div class="mb-4">
                        <textarea class="form-control rounded-4 py-3" placeholder="Street Address, City, ZIP Code" rows="3" required></textarea>
                    </div>
                </div>
                <button type="submit" name="place_order" class="btn btn-primary btn-lg w-100 rounded-pill py-3 fs-5 shadow-lg shadow-primary-50">
                    Pay Now & Place Order <i class="fas fa-lock ms-2 fs-6 opacity-75"></i>
                </button>
            </form>
            <p class="text-center mt-4 small text-muted opacity-75">100% money-back guarantee for lost or damaged shipments.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
