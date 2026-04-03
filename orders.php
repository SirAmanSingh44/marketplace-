<?php
// orders.php
require_once 'includes/header.php';

// Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch User Orders
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}
?>

<div class="container py-5 animate-fade-in">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold mb-4 display-5">Your Nexus History</h1>
            <p class="text-muted fs-5">A chronicle of your marketplace discoveries and transactions.</p>
        </div>
    </div>

    <div class="row g-4">
        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="col-12">
                    <div class="glass-card p-4 border-0 shadow-lg mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1 text-white">Order Tracking #<?php echo $order['id']; ?></h5>
                                <p class="text-muted small mb-0">Marketplace Transaction Date: <?php echo date("M d, Y", strtotime($order['order_date'])); ?></p>
                            </div>
                            <div class="mt-3 mt-md-0 text-center text-md-end">
                                <span class="badge rounded-pill bg-opacity-25 px-4 py-2 fs-6 
                                    <?php 
                                        echo match($order['status']) {
                                            'Pending' => 'bg-warning text-warning',
                                            'Shipped' => 'bg-primary text-primary',
                                            'Delivered' => 'bg-success text-success',
                                            'Cancelled' => 'bg-danger text-danger',
                                            default => 'bg-secondary text-secondary'
                                        };
                                    ?>">
                                    <i class="fas fa-circle me-2 fs-6 small"></i> <?php echo $order['status']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-dark table-hover border-0 mb-4 align-middle">
                                <thead class="border-light opacity-25">
                                    <tr>
                                        <th class="py-3 border-0 bg-transparent">Treasure Item</th>
                                        <th class="py-3 border-0 bg-transparent text-center">Batch</th>
                                        <th class="py-3 border-0 bg-transparent text-end">Price Pair</th>
                                        <th class="py-3 border-0 bg-transparent text-end">Extended Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch Order Items for each order
                                    $itemStmt = $pdo->prepare("
                                        SELECT oi.*, p.name, p.image_url 
                                        FROM order_items oi 
                                        JOIN products p ON oi.product_id = p.id 
                                        WHERE oi.order_id = ?
                                    ");
                                    $itemStmt->execute([$order['id']]);
                                    $items = $itemStmt->fetchAll();
                                    foreach ($items as $item):
                                    ?>
                                        <tr class="border-light opacity-50">
                                            <td class="py-3 border-0 bg-transparent">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="rounded-3 shadow-sm me-3" width="50" height="50" style="object-fit: cover;">
                                                    <span class="text-white-50"><?php echo htmlspecialchars($item['name']); ?></span>
                                                </div>
                                            </td>
                                            <td class="py-3 border-0 bg-transparent text-center text-white"><?php echo $item['quantity']; ?></td>
                                            <td class="py-3 border-0 bg-transparent text-end text-muted">$<?php echo number_format($item['price'], 2); ?></td>
                                            <td class="py-3 border-0 bg-transparent text-end fw-bold text-primary">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-4 border-top border-light border-opacity-25">
                            <h5 class="fw-bold mb-0 text-white opacity-75">Fulfilled Settlement</h5>
                            <h4 class="fw-bold mb-0 text-secondary">$<?php echo number_format($order['total_price'], 2); ?></h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 glass-card">
                <i class="fas fa-history fs-1 text-muted opacity-25 mb-4"></i>
                <h3 class="mb-4">No order history found.</h3>
                <p class="text-muted mb-5">Your marketplace journey is just beginning.</p>
                <a href="products.php" class="btn btn-primary rounded-pill px-5 btn-lg shadow-lg">Start Browsing</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
