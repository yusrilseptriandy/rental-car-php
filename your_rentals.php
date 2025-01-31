<?php
include 'config.php'; // Sambungkan ke database
include 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT t.*, p.name, p.image 
        FROM transactions t 
        JOIN products p ON t.product_id = p.id 
        WHERE t.user_id = {$_SESSION['user_id']}";
$result = $conn->query($sql);
?>

<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">Your Rentals</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Car</th>
                    <th>Rental Dates</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <tr>
                            <td>
                                <img src="uploads/' . $row['image'] . '" width="100" class="me-3">
                                ' . $row['name'] . '
                            </td>
                            <td>' . $row['start_date'] . ' to ' . $row['end_date'] . '</td>
                            <td>Rp' . number_format($row['total_price'], 0) . '</td>
                            <td><span class="badge bg-' . ($row['status'] == 'completed' ? 'success' : 'warning') . '">' . ucfirst($row['status']) . '</span></td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center text-muted">No rentals found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'includes/footer.php'; 
?>