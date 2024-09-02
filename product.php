<?php
include 'header.php';
include 'functions.php';

$conn = connectDB();

// ตัวแปรสำหรับจัดเก็บ action และ product_id ที่รับมาจาก GET request
$action = isset($_GET['action']) ? $_GET['action'] : '';
$product_id = isset($_GET['id']) ? $_GET['id'] : '';

// การจัดการการเพิ่มข้อมูลสินค้า
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $category_id = $_POST['category_id'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];

    $sql = "INSERT INTO products (product_name, product_description, category_id, unit_price, quantity_in_stock) 
            VALUES ('$product_name', '$product_description', $category_id, $unit_price, $quantity_in_stock)";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New product added successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// การจัดการการแก้ไขข้อมูลสินค้า
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($product_id)) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $category_id = $_POST['category_id'];
    $unit_price = $_POST['unit_price'];
    $quantity_in_stock = $_POST['quantity_in_stock'];

    $sql = "UPDATE products SET 
                product_name = '$product_name', 
                product_description = '$product_description', 
                category_id = $category_id, 
                unit_price = $unit_price, 
                quantity_in_stock = $quantity_in_stock 
            WHERE product_id = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Product updated successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// การจัดการการลบข้อมูลสินค้า
if ($action == 'delete' && !empty($product_id)) {
    $sql = "DELETE FROM products WHERE product_id = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Product deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// การดึงข้อมูลสินค้าสำหรับแก้ไข
if ($action == 'edit' && !empty($product_id)) {
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
}
?>

<div class="container mt-5">
    <h1>จัดการข้อมูลสินค้า</h1>

    <!-- ปุ่มเพิ่มสินค้า -->
    <a href="?action=add" class="btn btn-primary mb-3">เพิ่มสินค้า</a>

    <?php if ($action == 'add' || $action == 'edit') : ?>
        <!-- ฟอร์มสำหรับเพิ่มหรือแก้ไขสินค้า -->
        <h2><?php echo ($action == 'add') ? 'Add New Product' : 'Edit Product'; ?></h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="product_name" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo isset($product['product_name']) ? $product['product_name'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">รายละเอียดสินค้าn</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3"><?php echo isset($product['product_description']) ? $product['product_description'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">รหัสสินค้า</label>
                <input type="number" class="form-control" id="category_id" name="category_id" value="<?php echo isset($product['category_id']) ? $product['category_id'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="unit_price" class="form-label">ราคาสินค้า</label>
                <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="<?php echo isset($product['unit_price']) ? $product['unit_price'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity_in_stock" class="form-label">จำนวนสินค้า</label>
                <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" value="<?php echo isset($product['quantity_in_stock']) ? $product['quantity_in_stock'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-success"><?php echo ($action == 'add') ? 'Add Product' : 'Update Product'; ?></button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php endif; ?>

    <!-- ตารางแสดงข้อมูลสินค้า -->
    <h2 class="mt-5">ข้อมูลสินค้า</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ลำดับสินค้า</th>
                <th scope="col">ชื่อสินค้า</th>
                <th scope="col">รายละเอียดสินค้า</th>
                <th scope="col">รหัสสินค้า</th>
                <th scope="col">ราคาสินค้า</th>
                <th scope="col">จำนวนสินค้า</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) :
                while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <th scope="row"><?php echo $row['product_id']; ?></th>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['product_description']; ?></td>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><?php echo $row['unit_price']; ?></td>
                        <td><?php echo $row['quantity_in_stock']; ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $row['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?action=delete&id=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile;
            else : ?>
                <tr>
                    <td colspan="7" class="text-center">No products found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
include 'footer.php';
?>