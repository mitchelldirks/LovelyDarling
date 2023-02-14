<?php
include 'function.php';
// if (!isset($_GET['bot'])) {
//     echo "Tambahkan <b>bot</b> terlebih dahulu. ?bot";
//     exit;
// }else{
//     $bot = $_GET['bot'];
// }
$bot = isset($_GET['bot']) ? $_GET['bot'] : null;
if (!isset($_GET['act'])) {
    echo "Masukan <b>act</b>. ?act";
    exit;
}else{
    $act = $_GET['act'];
}
$string = isset($_GET['text']) ? $_GET['text'] : "";
switch ($act) {
    case 'pengingat_makan':
    $jam = isset($_GET['jam']) ? (int)$_GET['jam'] : date('H');
    if ($jam == 12) {
        $additional = 'makan siang';
        $string = "Sudah jam ".$jam.", jangan lupa ".$additional." ya, sayang";
    }elseif ($jam <= 7) {
        $additional = 'sarapan';
        $string = "Sudah sarapan belum? jangan lupa sarapan ya, sayang";
    }elseif ($jam >= 18) {
        $string = "Sudah makan malam belum? jangan lupa makan malam ya, sayang";
    }else{
        $additional = 'makan';
        $string = "Sudah makan belum? jangan lupa makan ya, sayang";
    }
    break;
    case 'pengingat_tidur':

    break;

    default:
    echo "Masukan <b>act</b> yang benar. ?act";
    exit;
    break;
}
if (strlen($string) > 0) {
    $Sendtelegram = sendtelegram($bot,$act,$string);
    print_r($Sendtelegram);
}
?>