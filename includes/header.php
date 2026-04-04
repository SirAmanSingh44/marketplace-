<?php
// includes/header.php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Market | Premium Second-Hand Platform</title>
    <meta name="description" content="Discover, buy, and sell second-hand treasures on Nexus Market. Electronics, fashion, collectibles, and more.">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php" style="background: linear-gradient(45deg, #6366f1, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">NEXUS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Marketplace</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown">Categories</a>
                    <ul class="dropdown-menu dropdown-menu-dark glass-card p-2">
                        <li><a class="dropdown-item" href="products.php?category=Electronics">Electronics</a></li>
                        <li><a class="dropdown-item" href="products.php?category=Clothing">Clothing</a></li>
                        <li><a class="dropdown-item" href="products.php?category=Books">Books</a></li>
                        <li><a class="dropdown-item" href="products.php?category=Collectibles">Collectibles</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> <span class="badge bg-primary rounded-pill">0</span></a></li>
                    <?php if ($_SESSION['is_admin']): ?>
                        <li class="nav-item"><a class="nav-link text-warning" href="admin/dashboard.php">Admin Panel</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <li class="nav-item"><span class="nav-link fw-bold text-primary">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary" href="register.php">Join Now</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
