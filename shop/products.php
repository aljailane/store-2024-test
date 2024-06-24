<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// استعلام لجلب الأقسام
$sql_categories = "SELECT category_id, category_name FROM Categories";
$stmt_categories = $conn->prepare($sql_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ادارة العضو</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
        .gift-card { 
            margin: 5px;
            border: 2px solid #f3e6e6;
            border-radius: 20px;
            padding: 20px;
            background-color: #f3e6e6;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .gift-card:hover {
            transform: translateY(-5px);
        }
        .gift-card .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #b0281a;
        }
        .gift-card .card-text {
            font-size: 16px;
            color: #333;
        }
        .gift-card .btn-primary {
            background-color: #b0281a;
            border-color: #b0281a;
        }
        .gift-card .btn-primary:hover {
            background-color: #8e2016;
            border-color: #8e2016;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            loadProducts();

            $('#filter-category, #sort-order').change(function() {
                loadProducts();
            });

            $('#load-more').click(function() {
                let offset = $('#products-container .gift-card').length;
                loadProducts(offset);
            });

            function loadProducts(offset = 0) {
                let category = $('#filter-category').val();
                let sort = $('#sort-order').val();

                $.ajax({
                    url: 'load_products.php',
                    method: 'GET',
                    data: {
                        category: category,
                        sort: sort,
                        offset: offset
                    },
                    success: function(response) {
                        if (offset === 0) {
                            $('#products-container').html(response);
                        } else {
                            $('#products-container').append(response);
                        }
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div class="container mt-2">
        <h2 class="text-center">خدمات</h2>
        <div class="row mb-3">
            <div class="col-md-4">
                <select id="filter-category" class="form-control form-control-sm m-1">
                    <option value="">جميع الآقسام</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select id="sort-order" class="form-control form-control-sm m-1">
                    <option value="newest">الجديد اولا</option>
                    <option value="oldest">القديم اولا</option>
                    <option value="price-asc">السعر: من اقل الى اعلى</option>
                    <option value="price-desc">السعر: من اكبر الى اقل</option>
                </select>
            </div>
        </div>
        <div class="row" id="products-container"></div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button id="load-more" class="btn btn-primary">تحميل المزيد</button>
            </div>
        </div>
    </div>
</body>
</html>

