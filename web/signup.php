<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الاشتراك</title>
    <!-- Bluma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Google Fonts - Rubik -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik&display=swap">
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
    <script src="https://unpkg.com/htmx.org@1.6.1"></script>
    <script>
        // Function to get the user's IP address
        async function getUserIP() {
            const response = await fetch('https://api.ipify.org?format=json');
            const data = await response.json();
            document.getElementById('ip').value = data.ip;
        }
        // Call the function on page load
        window.onload = function() {
            getUserIP();
            // Set the user agent
            document.getElementById('user_agent').value = navigator.userAgent;
        };
    </script>
</head>
<body>
    <form id="signup-form" hx-post="psu.php" hx-target="#result" hx-swap="innerHTML" class="section">
        <h2 class="title is-2">تسجيل الاشتراك</h2>
          <div id="result"></div>
        <div class="field">
            <label class="label" for="name">الاسم:</label>
            <div class="control">
                <input class="input" type="text" id="name" name="name" required>
            </div>
        </div>

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
            <label class="label" for="pincode">الرمز الشخصي:</label>
            <div class="control">
                <input class="input" type="text" id="pincode" name="pincode" required>
            </div>
        </div>

        <input type="hidden" id="ip" name="ip">
        <input type="hidden" id="user_agent" name="user_agent">

        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit">تسجيل الاشتراك</button>
            </div>
        </div>
    </form>
</body>
</html>

