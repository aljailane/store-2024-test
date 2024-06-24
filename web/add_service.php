<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة خدمة جديدة</title>
    <!-- Bluma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Google Fonts - Rubik -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik&display=swap">
    <!-- HTMX JS -->
    <script src="https://unpkg.com/htmx.org@1.6.1"></script>
    <style>
        /* تخصيص الخط */
        body, h1, label {
            font-family: 'Rubik', sans-serif;
        }
        /* تخصيص توجيه النص */
        body {
            direction: rtl;
        }
        /* تخصيص هامش النموذج */
        .container {
            margin: 50px auto;
            max-width: 600px;
        }
        /* تخصيص الحاوية المميزة */
        .custom-container {
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title is-2">إضافة خدمة جديدة</h1>
        <form hx-post="add_service_process.php" hx-target="#result" hx-swap="outerHTML">
            <div class="field">
                <label class="label" for="name">اسم الخدمة:</label>
                <div class="control">
                    <input class="input" type="text" id="name" name="name" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="price">السعر:</label>
                <div class="control">
                    <input class="input" type="number" step="0.01" id="price" name="price" required>
                </div>
            </div>

            <div class="field">
                <label class="label" for="description">الوصف:</label>
                <div class="control">
                    <textarea class="textarea" id="description" name="description" required></textarea>
                </div>
            </div>

            <div class="field">
                <label class="label" for="fee">رسوم إضافية:</label>
                <div class="control">
                    <input class="input" type="number" step="0.01" id="fee" name="fee">
                </div>
            </div>

            <div class="field">
                <label class="label" for="fee_paypal">رسوم PayPal إضافية:</label>
                <div class="control">
                    <input class="input" type="number" step="0.01" id="fee_paypal" name="fee_paypal">
                </div>
            </div>

            <div class="field">
                <label class="label" for="status">حالة الخدمة:</label>
                <div class="control">
                    <div class="select">
                        <select id="status" name="status">
                            <option value="active">نشطة</option>
                            <option value="inactive">غير نشطة</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit">إضافة الخدمة</button>
                </div>
            </div>
        </form>
        <!-- نتيجة المعالجة ستظهر هنا -->
        <div id="result"></div>
    </div>
</body>
</html>

