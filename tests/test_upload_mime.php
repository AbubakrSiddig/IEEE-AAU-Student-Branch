<?php
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$files = [
    'uploads/media/1793016813_a8af8a63.webp',
    'uploads/media/1793015054_dab62506.jpg',
    'uploads/covers/1793014413_99d8715c.jpg',
    'uploads/magazines/1793014425_c80328a7.pdf'
];

foreach ($files as $f) {
    if (file_exists($f)) {
        $mime = finfo_file($finfo, $f);
        echo "$f => MIME: '$mime'\n";
    } else {
        echo "$f => NOT FOUND\n";
    }
}
finfo_close($finfo);
