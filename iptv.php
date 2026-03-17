<?php
// =======================
// PROXY PHP - نسخة محسنة
// =======================

$url = $_GET['url'] ?? '';

if (empty($url)) {
    die('❌ الرجاء إرسال رابط القناة ( ?url= )');
}

if (!preg_match('/^https?:\/\//i', $url)) {
    die('❌ رابط غير صالح - تأكد من كتابته كاملاً');
}

// السماح بـ OPTIONS request سريعًا (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: *');
    exit;
}

// إعدادات cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => true, // تفعيل التحقق الأمني
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    CURLOPT_REFERER => 'https://www.google.com/',
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HEADER => true
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($response === false) {
    die('❌ فشل الاتصال بالرابط');
}

if ($http_code != 200) {
    die("❌ الرابط أعاد كود خطأ: $http_code");
}

// إرسال الرؤوس
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: *');

if ($content_type) {
    header("Content-Type: $content_type");
}

// إخراج المحتوى
echo $response;
?>
