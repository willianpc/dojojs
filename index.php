<?php
$filename = isset($_GET['f']) ? $_GET['f'] : "";
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon.ico"/>
    <title>dojoJS - Create and share your unit tests</title>
    <meta charset="utf8">
    <link href="//code.jquery.com/qunit/qunit-1.13.0.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="CodeMirror-2.32/lib/codemirror.css">
    <link href="estilo.css" type="text/css" rel="stylesheet" />

    <!-- GOOGLE ANALYTICS -->
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-11163941-5']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
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
                    <input type="checkbox" id="chkSameFile" checked/>
                    <label for="chkSameFile">Keep the version name</label>
                </span>
            </div>
            
            <div class="github-logo">
                <a href="https://github.com/chambs/dojojs" title="Fork me on Github" target="_blank">
                    <img src="github_logo_mini.png"/>
                </a>
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

    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="CodeMirror-2.32/lib/codemirror-min.js"></script>
    <script src="CodeMirror-2.32/mode/javascript/javascript.js"></script>
    <script src="//code.jquery.com/qunit/qunit-1.13.0.js"></script>
    <script>
        dojo_settings = <?php echo file_get_contents("config.json"); ?>;
    </script>
    <script src="dojojs.js"></script>
    
    <script>
    <?php
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
