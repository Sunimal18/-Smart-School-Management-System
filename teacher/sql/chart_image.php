<?php
function generateChartImage($reportData) {
    $labels = [];
    $values = [];

    foreach ($reportData as $item) {
        $labels[] = $item['subject'];
        $values[] = $item['A'];
    }

    $width = 800;
    $height = 400;
    $barWidth = 40;

    $image = imagecreatetruecolor($width, $height);
    $white = imagecolorallocate($image, 255,255,255);
    $black = imagecolorallocate($image, 0,0,0);
    $blue = imagecolorallocate($image, 52, 152, 219);

    imagefill($image, 0, 0, $white);

    $max = max($values) ?: 1;
    $x = 60;

    foreach ($values as $i => $val) {
        $barHeight = ($val / $max) * 300;
        imagefilledrectangle($image, $x, $height - $barHeight - 50, $x + $barWidth, $height - 50, $blue);
        imagestring($image, 3, $x + 5, $height - 40, $val, $black);
        imagestringup($image, 3, $x + 5, $height - 60, substr($labels[$i], 0, 12), $black);
        $x += $barWidth + 30;
    }

    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();
    imagedestroy($image);

    return 'data:image/png;base64,' . base64_encode($imageData);
}
