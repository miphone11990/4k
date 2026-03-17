<?php
// =======================
// PROXY PHP - الإصدار الذهبي
// =======================

// جلب رابط القناة
$url = $_GET['url'] ?? '';

if (empty($url)) {
    die('❌ الرجاء إرسال رابط القناة ( ?url= )');
}

// التحقق من الرابط
if (!preg_match('/^https?:\/\//', $url)) {
    die('❌ رابط غير صالح - تأكد من كتابته كاملاً');
}

// إعدادات cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    CURLOPT_REFERER => 'https://www.google.com/',
    CURLOPT_TIMEOUT => 30
]);

// تنفيذ الطلب
$data = curl_exec($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// فحص الأخطاء
if ($data === false) {
    die('❌ فشل الاتصال بالرابط');
}

if ($http_code != 200) {
    die("❌ الرابط أعاد كود خطأ: $http_code");
}

// إرسال الرؤوس الصحيحة
if ($content_type) {
    header("Content-Type: $content_type");
} else {
    header('Content-Type: application/vnd.apple.mpegurl');
}
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: *');

// إخراج المحتوى
echo $data;
?>