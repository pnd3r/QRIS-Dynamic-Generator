<?php
require_once 'phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qris = trim($_POST['qris']);
    $qty = trim($_POST['qty']);
    
    $yn = 'n';
    $tax = '';

    $qris = substr($qris, 0, -4);
    $step1 = str_replace("010211", "010212", $qris);
    $step2 = explode("5802ID", $step1);
    $uang = "54" . sprintf("%02d", strlen($qty)) . $qty;

    if (empty($tax)) {
        $uang .= "5802ID";
    } else {
        $uang .= $tax . "5802ID";
    }

    $fix = trim($step2[0]) . $uang . trim($step2[1]);
    $fix .= ConvertCRC16($fix);

    $tempDir = 'temp/';
    $fileName = 'qris_code.png';
    $filePath = $tempDir . $fileName;

    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    QRcode::png($fix, $filePath, QR_ECLEVEL_L, 6);

    echo "<img src='$filePath?" . time() . "' alt='Generated QR Code' class='img-fluid'>";
}

function ConvertCRC16($str) {
    $crc = 0xFFFF;
    $strlen = strlen($str);
    for ($c = 0; $c < $strlen; $c++) {
        $crc ^= ord($str[$c]) << 8;
        for ($i = 0; $i < 8; $i++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ 0x1021;
            } else {
                $crc = $crc << 1;
            }
        }
    }
    $hex = $crc & 0xFFFF;
    $hex = strtoupper(dechex($hex));
    if (strlen($hex) == 3) {
        $hex = "0" . $hex;
    }
    return $hex;
}
