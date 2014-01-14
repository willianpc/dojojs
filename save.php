<?php
header('Content-type: application/json');

//If no message was posted, die with error
if(!isset($_POST['code'])) {
    http_response_code(400);
    die();
}

$code = $_POST['code'];
$filename = isset($_POST['filename']) ? $_POST['filename'] : "";
$dir = "sources";
$sameName = isset($_POST['overwrite']) ? $_POST['overwrite'] : "";

if(!file_exists($dir))
    mkdir($dir);

$fullpath = "$dir/$filename";

if($filename == '' || !file_exists($fullpath) || $sameName == 'false') {
    $filename = genHash(8);
    $fullpath = "$dir/$filename";
}
    
file_put_contents($fullpath, $code);

function genHash($n) {
    $buffer = "";

    for($i=0; $i < $n; $i++) {
        $low = round(rand(0, 1));
        $letter = chr( rand(65, 90) );
        
        if($low == 0) {
            $letter = strtolower($letter);
        }
        $buffer .= $letter;
    }
    return $buffer;
}

echo json_encode(array("filename" => $filename));

?>
