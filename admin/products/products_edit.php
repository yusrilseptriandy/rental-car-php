<?php
include __DIR__ . '/../../config.php';
include __DIR__ . '/../../includes/header.php';

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product = null;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Product not found!";
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid product ID!";
    header("Location: ?page=products");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    
    $image = $product['image']; 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/";
        $newImage = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $newImage;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (!empty($product['image'])) {
                $oldImagePath = $target_dir . $product['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image = $newImage; 
        } else {
            $_SESSION['error'] = "Error uploading image!";
            header("Location: products_edit.php?id=$id");
            exit();
        }
    }
    
    // Update data produk
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sdsssi", $name, $price, $description, $image, $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
        header("Location: http://rental-mobil.test:8080/admin_dashboard.php?page=products"); 
        exit();
    } else {
        $_SESSION['error'] = "Error updating product: " . $conn->error;
        header("Location: products_edit.php?id=$id");
        exit();
    }
}
?>

<!-- Edit Product Form -->
<div class="container">
    <h3>Edit Product</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <small>Current Image: <a href="/uploads/<?= $product['image'] ?>" target="_blank"><?= $product['image'] ?></a></small>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                <option value="rented" <?= $product['status'] == 'rented' ? 'selected' : '' ?>>Rented</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="http://rental-mobil.test:8080/admin_dashboard.php?page=products" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
