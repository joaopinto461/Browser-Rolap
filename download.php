<?
$fileName = $_GET['file'];
$downloadFileName = $fileName;

if (file_exists($fileName)) {
    header('Content-Description: File Transfer');
    header('Content-Type: text/json');
    header('Content-Disposition: attachment; filename='.$downloadFileName);
    ob_clean();
    flush();
    readfile($fileName);
    exit;
}