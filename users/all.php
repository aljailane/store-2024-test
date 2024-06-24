<?php
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php'; // تحديد مسار ملف الاتصال بقاعدة البيانات

// استعلام SQL لاسترداد أحدث 10 أعضاء مسجلين
$sql = "SELECT * FROM Orders ORDER BY date DESC LIMIT 10";

// تنفيذ الاستعلام
$db = new SourceDb();
$conn = $db->connect();
$stmt = $conn->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Members</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Latest Members</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?php echo $orders['order_id']; ?></td>
                            <td><?php echo $orders['order_date']; ?></td>
                            <td><?php echo $orders['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
