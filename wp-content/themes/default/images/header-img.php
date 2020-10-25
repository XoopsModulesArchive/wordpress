<?php

$img = 'kubrickheader.jpg';

// If we don't have image processing support, redirect.
if (!function_exists('imagecreatefromjpeg')) {
    die(header('Location: kubrickheader.jpg'));
}

// Assign and validate the color values
$default = false;
$vars = ['upper' => ['r1', 'g1', 'b1'], 'lower' => ['r2', 'g2', 'b2']];
foreach ($vars as $var => $subvars) {
    if (isset($_GET[$var])) {
        foreach ($subvars as $index => $subvar) {
            $length = mb_strlen($_GET[$var]) / 3;

            $v = mb_substr($_GET[$var], $index * $length, $length);

            if (1 == $length) {
                $v = '' . $v . $v;
            }

            $$subvar = hexdec($v);

            if ($$subvar < 0 || $$subvar > 255) {
                $default = true;
            }
        }
    } else {
        $default = true;
    }
}

if ($default) {
    [$r1, $g1, $b1, $r2, $g2, $b2] = [105, 174, 231, 65, 128, 182];
}

// Create the image
$im = imagecreatefromjpeg($img);

// Get the background color, define the rectangle height
$white = imagecolorat($im, 15, 15);
$h = 182;

// Define the boundaries of the rounded edges ( y => array ( x1, x2 ) )
$corners = [
    0 => [25, 734],
    1 => [23, 736],
    2 => [22, 737],
    3 => [21, 738],
    4 => [21, 738],
    177 => [21, 738],
    178 => [21, 738],
    179 => [22, 737],
    180 => [23, 736],
    181 => [25, 734],
];

// Blank out the blue thing
for ($i = 0; $i < $h; $i++) {
    $x1 = 19;

    $x2 = 740;

    imageline($im, $x1, 18 + $i, $x2, 18 + $i, $white);
}

// Draw a new color thing
for ($i = 0; $i < $h; $i++) {
    $x1 = 20;

    $x2 = 739;

    $r = (0 != $r2 - $r1) ? $r1 + ($r2 - $r1) * ($i / $h) : $r1;

    $g = (0 != $g2 - $g1) ? $g1 + ($g2 - $g1) * ($i / $h) : $g1;

    $b = (0 != $b2 - $b1) ? $b1 + ($b2 - $b1) * ($i / $h) : $b1;

    $color = imagecolorallocate($im, $r, $g, $b);

    if (array_key_exists($i, $corners)) {
        imageline($im, $x1, 18 + $i, $x2, 18 + $i, $white);

        [$x1, $x2] = $corners[$i];
    }

    imageline($im, $x1, 18 + $i, $x2, 18 + $i, $color);
}

//die;
header('Content-Type: image/jpeg');
imagejpeg($im, '', 92);
imagedestroy($im);
