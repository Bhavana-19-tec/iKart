<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - IKART</title>
</head>
<body>
    <h2>Welcome, Admin</h2>
    <h3>Product List</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Image</th><th>Category</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <?php if (!empty($row['image_path'])) { ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="50">
                <?php } else { ?>
                    No Image
                <?php } ?>
            </td>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
        </tr>
        <?php } ?>
    </table>
    <br>
    <a href="admin_log_
