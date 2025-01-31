<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    $sql = "SELECT * FROM products WHERE id = $product_id AND status = 'available'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo '<div class="alert alert-danger text-center">Product not available.</div>';
        exit();
    }

    $product = $result->fetch_assoc();
} else {
    echo '<div class="alert alert-danger text-center">Invalid product ID.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);
    $interval = $start_date_obj->diff($end_date_obj);
    $total_days = $interval->days;

    $total_price = $total_days * $product['price'];

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, product_id, start_date, end_date, total_price, status, email, phone_number, name) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, ?)");
    $stmt->bind_param("iissdsss", $user_id, $product_id, $start_date, $end_date, $total_price, $email, $phone_number, $name);

    if ($stmt->execute()) {
        $conn->query("UPDATE products SET status = 'rented' WHERE id = $product_id");

        echo '<div class="alert alert-success text-center">Rental transaction successfully created!</div>';
        header("Location: /");  
        exit();
    } else {
        echo '<div class="alert alert-danger text-center">Error creating rental transaction.</div>';
    }
}
?>

<!-- Rental Form -->
<div class="container py-5">
    <h3 class="text-center mb-4">Rental Form</h3>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center text-dark"><?= $product['name'] ?></h5>
                    <p class="text-muted text-center">Rp <?= number_format($product['price'], 0) ?>/day</p>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Your Phone</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Rental</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
