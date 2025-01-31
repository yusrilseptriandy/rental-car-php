<?php 
include 'config.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/header.php';
?>

<h3>Welcome, <?= $_SESSION['name'] ?></h3>
<div class="row mt-4">
   
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Your Rentals</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Car</th>
                            <th>Rental Dates</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT t.*, p.name FROM transactions t 
                                JOIN products p ON t.product_id = p.id 
                                WHERE t.user_id = {$_SESSION['user_id']}";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['start_date'] ?> to <?= $row['end_date'] ?></td>
                            <td>Rp<?= number_format($row['total_price'],0) ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>