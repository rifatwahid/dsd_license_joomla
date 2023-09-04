<?php 

if (isJoomla4()) {
    require __DIR__ . '/media_j4.php';
} else {
    require __DIR__ . '/media_j3.php';
}