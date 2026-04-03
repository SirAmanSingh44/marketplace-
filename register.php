<?php
// register.php
require_once 'includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error creating account: " . $e->getMessage();
    }
}
?>

<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 glass-card p-5 animate-fade-in shadow-lg">
        <h2 class="fw-bold mb-4 text-center">Start Your Journey</h2>
        <p class="text-muted text-center mb-5">Create your account and become a part of Nexus community.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-pill text-center mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-4">
                <label class="form-label text-muted">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-primary-subtle text-primary"><i class="fas fa-user-circle"></i></span>
                    <input type="text" name="name" class="form-control border-start-0" placeholder="e.g. John Doe" required>
                </div>
            </div>
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
            <button type="submit" class="btn btn-primary w-100 py-3 mb-4 rounded-pill fs-5">Create Account</button>
        </form>

        <p class="text-center text-muted mb-0">Already a member? <a href="login.php" class="text-primary fw-bold text-decoration-none">Log In</a></p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
