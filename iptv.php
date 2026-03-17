<?php
// استقبال رابط القناة
$url = $_GET['url'] ?? '';

if (empty($url)) {
    die('❌ الرجاء إرسال رابط القناة');
}

// التحقق من صحة الرابط
if (!preg_match('/^https?:\/\//', $url)) {
    die('❌ رابط غير صالح');
}

// جلب المحتوى
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$data = curl_exec($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// إرسال الرؤوس المناسبة
if ($content_type) {
    header("Content-Type: $content_type");
} else {
    header('Content-Type: application/vnd.apple.mpegurl');
}
header('Access-Control-Allow-Origin: *');

echo $data;
?>