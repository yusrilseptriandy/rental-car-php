<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $transaction_id = $_POST['transaction_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE transactions SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $transaction_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Transaction status updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating transaction status: " . $conn->error . "</div>";
    }

    $stmt->close();
}

$sql = "SELECT transactions.id, transactions.user_id, products.name AS product_name, transactions.start_date, transactions.end_date, transactions.total_price, transactions.status, transactions.created_at, transactions.email, transactions.phone_number, transactions.name 
        FROM transactions 
        JOIN products ON transactions.product_id = products.id";

$result = $conn->query($sql);
?>

<div class="container py-5">
    <h3 class="text-center mb-4">Manage Transactions</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Product Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>"; 
                        echo "<td>" . date('Y-m-d', strtotime($row['start_date'])) . "</td>";
                        echo "<td>" . date('Y-m-d', strtotime($row['end_date'])) . "</td>";
                        echo "<td>Rp " . number_format($row['total_price'], 2) . "</td>";
                        echo "<td><span class='badge " . getStatusClass($row['status']) . "'>" . ucfirst($row['status']) . "</span></td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='transaction_id' value='" . $row['id'] . "'>
                                    <select name='status' required class='form-select'>
                                        <option value='pending'" . ($row['status'] == 'pending' ? ' selected' : '') . ">Pending</option>
                                        <option value='completed'" . ($row['status'] == 'completed' ? ' selected' : '') . ">Completed</option>
                                        <option value='cancelled'" . ($row['status'] == 'cancelled' ? ' selected' : '') . ">Cancelled</option>
                                    </select>
                                    <button type='submit' name='update_status' class='btn btn-primary btn-sm mt-2'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No transactions found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close(); 

function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'completed':
            return 'bg-success text-white';
        case 'cancelled':
            return 'bg-danger text-white';
        default:
            return '';
    }
}
?>