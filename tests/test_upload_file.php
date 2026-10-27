<?php
$token = '58f53380734968eb03891ea6d1a9870d5c7af3532d21e28a82e93b33e441017e';
$url = "http://127.0.0.1/IEEE%20AAU%20SB%20PLATFORM/api.php?action=upload&folder=media&token=$token";

// Create a small 1x1 test image
$testImgPath = __DIR__ . '/test_sample.png';
$im = imagecreatetruecolor(10, 10);
$bg = imagecolorallocate($im, 0, 100, 200);
imagefilledrectangle($im, 0, 0, 10, 10, $bg);
imagepng($im, $testImgPath);
imagedestroy($im);

$cfile = new CURLFile($testImgPath, 'image/png', 'test_sample.png');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $cfile]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

@unlink($testImgPath);
