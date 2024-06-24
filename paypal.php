<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عملية الدفع عبر PayPal</title>
    <!-- إضافة Bluma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">عملية الدفع عبر PayPal</h1>
            <?php
            // تضمين ملف config.php للحصول على معرف العميل المشفر
            include 'config.php';

            // استقبال معلومات المنتج من الرابط
            if(isset($_GET['product']) && isset($_GET['price'])  && isset($_GET['price'])){
                $productName = $_GET['product'];
                $balName = $_GET['bal'];
                $productPrice = $_GET['price'];

                // حساب السعر الإجمالي بعد إضافة الرسوم
                $totalPrice = calculateTotalPrice($productPrice);
            }

            // حساب السعر الإجمالي بعد إضافة الرسوم
            function calculateTotalPrice($price){
                // اضافة الرسوم (4.9% + $0.60)
                $feePercentage = 4.9 / 100;
                $fixedFee = 0.65;
                $totalPrice = $price + ($price * $feePercentage) + $fixedFee;
                return $totalPrice;
            }
            ?>

            <div class="columns">
                <div class="column is-half">
                    <table class="table is-bordered">
                        <tbody>
                            <tr>
                                <td>باقة 6 جيجا فور جي </td>
                               
                            </tr>
                  </table>
                   <table class="table is-bordered">
                        <tbody>
                            <tr>
                                <td>المنتج:</td>
                                <td><?php echo $productName; ?></td>
                            </tr>
                           <tr>
                                <td>الرصيد:</td>
                                <td><?php echo $balName; ?></td>
                            </tr>
                            <tr>
                                <td>السعر:</td>
                                <td>$<?php echo $productPrice; ?></td>
                            </tr>
                            <tr>
                                <td>الرسوم:</td>
                                <td>4.9% + $0.65</td>
                            </tr>
                            <tr>
                                <td>السعر الإجمالي:</td>
                                <td>$<?php echo number_format($totalPrice, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- إضافة PayPal Checkout SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientId; ?>"></script>

    <!-- تهيئة PayPal Checkout SDK -->
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        description: '<?php echo $productName; ?>',
                        amount: {
                            value: '<?php echo $totalPrice; ?>',
                            currency_code: 'USD'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('تم استكمال عملية الدفع بنجاح!');
                    window.location.href = 'https://example.com/success.php';
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
