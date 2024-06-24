<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طلب جديد</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script>
        // Function to fetch and display service details
        function displayServiceDetails() {
            // Get the selected service ID
            var serviceId = document.getElementById('service').value;
            
            // Make an AJAX request to fetch service details
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Parse the response as JSON
                    var service = JSON.parse(this.responseText);
                    
                    // Display service details
                    document.getElementById('price').innerText = service.price;
                    document.getElementById('fee').innerText = service.fee;
                    document.getElementById('fee_paypal').innerText = service.fee_paypal; // Add this line
                    document.getElementById('description').innerText = service.description;
                }
            };
            xhttp.open("GET", "get_service_details.php?id=" + serviceId, true);
            xhttp.send();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">إضافة طلب جديد</h1>
        <form action="add_order_process.php" method="POST">
            <div class="mb-3">
                <label for="service" class="form-label">اختر الخدمة:</label>
                <select class="form-select" id="service" name="service" onchange="displayServiceDetails()" required>
                    <option selected disabled value="">اختر الخدمة</option>
                    <!-- PHP code to fetch services from the database -->
                    <?php
                    require_once 'SourceDb.php';
                    
                    // Establish database connection
                    $database = new SourceDb();
                    $db = $database->getConnection();
                    
                    // Fetch services from the database
                    if ($db) {
                        $query = "SELECT * FROM services WHERE status = 'active'";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        // Display each service as an option
                        foreach ($services as $service) {
                            echo "<option value='" . $service['id'] . "'>" . $service['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">السعر:</label>
                <p id="price"></p>
            </div>
            <div class="mb-3">
                <label for="fee" class="form-label">الرسوم:</label>
                <p id="fee"></p>
            </div>
            <div class="mb-3">
                <label for="fee_paypal" class="form-label">رسوم PayPal:</label> <!-- Add this block -->
                <p id="fee_paypal"></p>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">تفاصيل الخدمة:</label>
                <p id="description"></p>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">الرمز:</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <button type="submit" class="btn btn-primary">إرسال الطلب</button>
        </form>
    </div>
</body>
</html>

