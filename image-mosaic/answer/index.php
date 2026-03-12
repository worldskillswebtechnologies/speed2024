<?php

$source = imagecreatefromjpeg("original.jpg");
$sx = imagesx($source);
$sy = imagesy($source);
$image = imagecreatetruecolor($sx, $sy);

$cellSize = $_GET["cell_size"] ?? 50;
$cellXCount = ceil($sx / $cellSize);
$cellYCount = ceil($sy / $cellSize);

for($cx = 0; $cx < $cellXCount; $cx++) {
    for($cy = 0; $cy < $cellYCount; $cy++) {

        $sumRed = 0;
        $sumGreen = 0;
        $sumBlue = 0;
        $count = 0;

        $startX = $cx * $cellSize;
        $startY = $cy * $cellSize;
        $maxXCell = min($startX + $cellSize, $sx);
        $maxYCell = min($startY + $cellSize, $sy);
        for($x = $startX; $x < $maxXCell; $x++){
            for($y = $startY; $y < $maxYCell; $y++){

                $colorIndex = imagecolorat($source, $x, $y);
                $color = imagecolorsforindex($source, $colorIndex);
                $sumRed += $color["red"];
                $sumGreen += $color["green"];
                $sumBlue += $color["blue"];
                $count++;

            }
        }

        $aveRed = round($sumRed / $count);
        $aveGreen = round($sumGreen / $count);
        $aveBlue = round($sumBlue / $count);

        $fillColor = imagecolorallocate($image, $aveRed, $aveGreen, $aveBlue);
        imagefilledrectangle($image, $startX, $startY, $maxXCell, $maxYCell, $fillColor);

    }
}

header("Content-Type: image/jpeg");
imagejpeg($image);

imagedestroy($source);
imagedestroy($image);