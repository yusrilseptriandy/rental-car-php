<?php 

include 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'ADMIN') {
    header("Location: login.php");
    exit();
}
include 'includes/header.php';
?>

<h3>Admin Dashboard</h3>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="list-group">
            <a href="?page=users" class="list-group-item list-group-item-action">Manage Users</a>
            <a href="?page=products" class="list-group-item list-group-item-action">Manage Cars</a>
            <a href="?page=transactions" class="list-group-item list-group-item-action">Manage Transactions</a>
           
        </div>
    </div>
    
    <div class="col-md-9">
        <?php
        $page = $_GET['page'] ?? 'users';
        
        switch($page) {
            case 'users':
                include 'admin/users/user.php'; // Perbaiki path
                break;
            case 'products':
                include 'admin/products/products.php'; // Perbaiki path
                break;
            case 'transactions':
                include 'admin/transactions/transactions.php'; // Perbaiki path
                break;
        }
        
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>