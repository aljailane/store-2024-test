<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">طلباتي</h1>
        <!-- PHP code to display user's orders -->
        <?php
        // استدعاء SourceDb.php للاتصال بقاعدة البيانات
        require_once 'SourceDb.php';

        // إنشاء اتصال بقاعدة البيانات
        $database = new SourceDb();
        $db = $database->getConnection();

        if ($db) {
            try {
                // استعلام SQL لاسترداد طلبات المستخدم الحالية مع تفاصيل الخدمات
                $query = "SELECT orders.*, services.name AS service_name, services.price AS service_price, services.description AS service_description FROM orders INNER JOIN services ON orders.service_id = services.id WHERE orders.user_id = :user_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id); // يجب تعيين هوية المستخدم هنا
                $stmt->execute();
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // عرض كل طلب كبطاقة باستخدام Bootstrap مع تفاصيل الخدمة وتفاصيل الطلب
                foreach ($orders as $order) {
                    echo "<div class='card mb-3'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>رقم الطلب: " . $order['id'] . "</h5>";
                    echo "<p class='card-text'>اسم الخدمة: " . $order['service_name'] . "</p>";
                    echo "<p class='card-text'>سعر الخدمة: " . $order['service_price'] . "</p>";
                    echo "<p class='card-text'>وصف الخدمة: " . $order['service_description'] . "</p>";
                    echo "<p class='card-text'>تاريخ الطلب: " . $order['date'] . "</p>";
                    echo "<p class='card-text'>كود الطلب: " . $order['code'] . "</p>";
                    // يمكنك عرض المزيد من تفاصيل الطلب هنا مثل حالة الطلب
                    echo "</div>";
                    echo "</div>";
                }
            } catch (PDOException $exception) {
                // عرض رسالة الخطأ في حالة حدوث خطأ أثناء استعلام قاعدة البيانات
                echo "<div class='alert alert-danger' role='alert'>خطأ: " . $exception->getMessage() . "</div>";
            }
        } else {
            // عرض رسالة خطأ في حالة عدم الاتصال بقاعدة البيانات
            echo "<div class='alert alert-danger' role='alert'>خطأ في الاتصال بقاعدة البيانات.</div>";
        }
        ?>
    </div>
</body>
</html>
