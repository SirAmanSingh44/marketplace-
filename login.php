<?php
// login.php
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred: " . $e->getMessage();
    }
}
?>

<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 glass-card p-5 animate-fade-in">
        <h2 class="fw-bold mb-4 text-center">Welcome Back</h2>
        <p class="text-muted text-center mb-5">Login to your account and explore the marketplace.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-pill text-center mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-4">
                <label class="form-label text-muted">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-primary-subtle text-primary"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" placeholder="your@email.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-primary-subtle text-primary"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted small" for="remember">Remember me</label>
                </div>
                <a href="#" class="text-primary small text-decoration-none">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 mb-4 rounded-pill fs-5">Login to Market</button>
        </form>

        <p class="text-center text-muted mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Join Now</a></p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
