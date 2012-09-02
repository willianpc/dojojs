<?php
header('Content-type: application/json');
$code = $_POST['code'];
$existingFile = $_POST['filename'];
$dir = "sources/";
$sameName = $_POST['overwrite'];

if($existingFile != '' && file_exists($dir . $existingFile) && $sameName == 'true') {
    $filename = $existingFile;
} else {
    $filename = genHash(8);
}

$fo = fopen($dir . $filename, 'w+');
fwrite($fo, $code);
fclose($fo);

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

?>
{"filename" : "<?=$filename?>"}
