<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <!-- Bluma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Google Fonts - Rubik -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik&display=swap">
    <!-- htmx -->
    <script src="https://unpkg.com/htmx.org@1.6.1"></script>
    <style>
        /* تخصيص الخط */
        body, input, button {
            font-family: 'Rubik', sans-serif;
        }
        /* تخصيص توجيه النص */
        label, input, button {
            direction: rtl;
        }
        /* تخصيص هامش النموذج */
        form {
            margin: 0 auto;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <form id="login-form" hx-post="plogin.php" hx-target="#login-result" class="section">
        <h2 class="title is-2">تسجيل الدخول</h2>
        <div id="login-result"></div>
        <div class="field">
            <label class="label" for="username">اسم المستخدم:</label>
            <div class="control">
                <input class="input" type="text" id="username" name="username" required>
            </div>
        </div>

        <div class="field">
            <label class="label" for="password">كلمة المرور:</label>
            <div class="control">
                <input class="input" type="password" id="password" name="password" required>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit">تسجيل الدخول</button>
            </div>
        </div>
    </form>
</body>
</html>

