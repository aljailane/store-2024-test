<?php
class SourceDb {
    private $host = 'localhost'; // استبدل بالهوست المناسب لقاعدة بياناتك
    private $db_name = 'pay_pay'; // استبدل باسم قاعدة البيانات
    private $username = 'pay_pay'; // استبدل باسم المستخدم لقاعدة البيانات
    private $password = 'vM30+N798Fsm7gOj'; // استبدل بكلمة مرور قاعدة البيانات
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>

