<?php 
include 'config.php';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="text-center mb-4 ">Available Cars</h3>
        </div>
        
        <?php
        $sql = "SELECT * FROM products WHERE status = 'available'";
        $result = $conn->query($sql);
        
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-lg border-light rounded-3 overflow-hidden">
                <img src="uploads/<?= $row['image'] ?>" class="card-img-top product-image" alt="<?= $row['name'] ?>" style="height: 220px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title text-dark"><?= $row['name'] ?></h5>
                    <p class="card-text text-muted"><?= $row['description'] ?></p>
                    <span class="h5 text-success">Rp <?= number_format($row['price'], 0) ?>/day</span>
                    <div class="mt-3">
                        <a href="rent.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-check me-2"></i>Rent Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        } else {
            echo '<div class="col-12 text-center"><div class="alert alert-info">No cars available at the moment.</div></div>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: scale(1.05);
}

.product-image {
    transition: transform 0.3s;
}

.product-image:hover {
    transform: scale(1.1);
}
</style>