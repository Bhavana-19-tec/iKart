<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db_connect.php';

// Delete product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_product_manage.php");
    exit();
}

// Add product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];  // This is 'category' in your DB
    $image_path = $_POST['image_path'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image_path, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiss", $name, $price, $stock, $image_path, $category);
    $stmt->execute();
    header("Location: admin_product_manage.php");
    exit();
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - IKART</title>
</head>
<body>
    <h2>Manage Products</h2>

    <h3>Add New Product</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Product Name" required><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br>
        <input type="number" name="stock" placeholder="Stock" required><br>
        <input type="text" name="image_path" placeholder="Image Path (e.g., images/paracetamol.jpg)" required><br>
        <input type="text" name="category" placeholder="Category (e.g., adult)" required><br>
        <input type="submit" value="Add Product">
    </form>

    <h3>All Products</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Image</th><th>Category</th><th>Action</th>
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
            <td>
                <a href="admin_product_manage.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a> | <a href="admin_logout.php">Logout</a>
</body>
</html>
