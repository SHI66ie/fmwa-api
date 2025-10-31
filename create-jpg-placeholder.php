<?php
// Create a JPG placeholder image for directors

// Create a 300x300 image
$width = 300;
$height = 300;
$image = imagecreatetruecolor($width, $height);

// Define colors
$bg_start = imagecolorallocate($image, 1, 58, 4);    // #013a04
$bg_end = imagecolorallocate($image, 2, 90, 6);      // #025a06
$white_transparent = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 220, 220, 220);

// Create gradient background
for ($y = 0; $y < $height; $y++) {
    $ratio = $y / $height;
    $r = (1 - $ratio) * 1 + $ratio * 2;
    $g = (1 - $ratio) * 58 + $ratio * 90;
    $b = (1 - $ratio) * 4 + $ratio * 6;
    $color = imagecolorallocate($image, $r, $g, $b);
    imageline($image, 0, $y, $width, $y, $color);
}

// Draw person silhouette
// Head (circle)
$head_x = 150;
$head_y = 100;
$head_radius = 40;
imagefilledellipse($image, $head_x, $head_y, $head_radius * 2, $head_radius * 2, $white_transparent);

// Body (ellipse)
$body_x = 150;
$body_y = 200;
$body_width = 120;
$body_height = 160;
imagefilledellipse($image, $body_x, $body_y, $body_width, $body_height, $white_transparent);

// Add text (if font is available)
$font_size = 5;
$text1 = "DIRECTOR";
$text2 = "Photo Coming Soon";

// Calculate text position
$text1_width = imagefontwidth($font_size) * strlen($text1);
$text2_width = imagefontwidth($font_size) * strlen($text2);

imagestring($image, $font_size, ($width - $text1_width) / 2, 260, $text1, $text_color);
imagestring($image, 3, ($width - imagefontwidth(3) * strlen($text2)) / 2, 280, $text2, $text_color);

// Save as JPG
$filename = 'images/directors/placeholder-director.jpg';
imagejpeg($image, $filename, 85);
imagedestroy($image);

echo "Created placeholder image: {$filename}\n";
?>
