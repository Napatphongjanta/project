<?php
include 'header.php';
include 'functions.php';

$conn = connectDB();


?>



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
