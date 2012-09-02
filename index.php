<?php
$filename = $_GET['f'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<link rel="shortcut icon" href="favicon.ico"/>
<title>{dojoJS} Make and share your unit tests</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="qunit-1.9.0.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="CodeMirror-2.32/lib/codemirror.css">
<link href="estilo.css" type="text/css" rel="stylesheet" />
</head>
<body>

<div class="container">
    <div id="header">
        <div class="logo">dojoJS</div>
        
        <div class="controls">
            <input type="button" class="btn btnNew" value="New test"/>
            <input type="button" class="btn btnSave" value="Save test"/>
            <button class="btnSwitchView" style="display:none">Switch view</button>
            <span title="Saves your test with the same name instead of generating a new version">
                <input type="checkbox" id="chkSameFile"/>
                <label for="chkSameFile">Keep the version name</label>
            </span>
        </div>
    </div>
    <div id="editor"></div>
    <div id="unittest"></div>
    <div id="result"></div>
    
    
    <div class="theModal"></div>
    <div class="popup">
        <div class="popup-content">
            <div class="popup-titlebar">Some info</div>
            
            <div class="popup-body">
                All your work will be lost. Are you sure you want to create a new test?
            </div>
            
            <div class="popup-controls">
                <input type="button" class="btn" value="Cancel"/>
                <input type="button" class="btn" value="Okay"/>
            </div>
        </div>
    </div>
    
    
</div>

<script src="jquery-1.7.min.js"></script>
<script src="CodeMirror-2.32/lib/codemirror-min.js"></script>
<script src="CodeMirror-2.32/mode/javascript/javascript.js"></script>
<script src="qunit-1.9.0.js"></script>
<script src="dojojs.js"></script>

<script>
<?
$dir = 'sources/';
$content = "";

if($filename != '' && file_exists($dir . $filename)) {
    $fp = fopen($dir . $filename, 'r') or die('erro open');
    $content = fread($fp, filesize($dir . $filename));
    
    $content = str_replace('"', '\"', $content);
    $content = str_replace("'", "\'", $content);
    $content = str_replace("\n", '\\n', $content);
    fclose($fp);
}
?>

var content = "<?=$content?>";
var utContent = "";
var sep = '/*RAGABOOM*/';

if(content) {
    utContent = content.substr(0, content.indexOf(sep));
    content = content.substr(content.indexOf(sep) + sep.length, content.length);
} else {
    content = dojojs.initialCode;
    utContent = dojojs.utCode;
}

dojojs.utEditor.setValue(utContent);
dojojs.utFuncs.setValue(content);
dojojs.utFuncs.focus();

$(document).ready(function() {
    dojojs.loadDefaults();
    dojojs.bindings();
    QUnit.log(dojojs.cb);
});

</script>

</body>
</html>
