var dojojs = (function(w, d, $, settings) {
    var settings = $.extend({
        testing: false
    }, settings);

    var initialCode = "/*Insert your functions here*/\n\
    \n\
function sum(a, b) {\n\
  return a + b;\n\
}";

    var utCode = "/*Insert your tests here*/\n\nQUnit.test(\"Unit Test\", function(assert) {\n\
      \n\
  assert.equal( sum(2,2), 5);\n\
  assert.equal( sum(2,2), 4, \"2+2 should be 4\" );\n\
  assert.equal( sum(3,6), 9);\n\
});\n";

    var utEditor = CodeMirror(d.getElementById('unittest'), {
        lineNumbers: true,
        keyMap: 'pcDefault',
	    value: '',
	    mode: 'javascript',
	    onChange: evaluate
    });

    var editor = d.getElementById('editor');
    var myCodeMirror = CodeMirror(editor, {
        lineNumbers: true,
        keyMap: 'pcDefault',
	    value: '',
	    mode: 'javascript',
	    onChange: evaluate
    });

    function getCodeForSave() {
        return utEditor.getValue() + '/*RAGABOOM*/' + myCodeMirror.getValue();
    }
    
    function save() {
        $.post('save.php', {
            code: getCodeForSave(),
            filename: location.search.substr(3),
            overwrite: $('#chkSameFile').is(':checked')
        }, function(data) {
            location.search = "f=" + data.filename;
        });
    }

    //redefining CTRL+S
    CodeMirror.commands.save = save;

    function cb(a) {
        var result = $('#result');
        var title = a.message || "";
        result.append("<div class='result " + a.result + "'><div>Expected result was " + a.expected + "</div><span> Your test " + title.bold() + " returned " + a.actual + "</span><span>" + (a.source || '') + "</span>" + '</div>');
    }

    function evaluate(which) {
        which = typeof which !== "undefined" ? which : "both";
        
        var result = $('#result');
        var utCode = utEditor.getValue(),
            myCode = myCodeMirror.getValue();
            
        if(utCode && myCode) {
            utCode += "\nQUnit.start();";
            myCode += ";";
            
            var evalCode;
            
            if(which == "test") {
                evalCode = utCode;
            }
            else {
                evalCode = utCode + myCode;
            }
            
            result.empty();
            try {
                (new Function(evalCode))();
            } catch(e) { }
        }
    }

    function bindings() {
        $('.btnSave').click(save);

        $('.btnNew').click(newTest);

        $('.btnSwitchView').click(switchView);
    }
    
    function newTest() {
        if(settings.testing)
            location = "/qunit/";
        else
            location = "/";
    }

    var defView = getCookie('defView');
    if(defView == undefined) defView = true;

    var ed  = $('#editor'),
        res = $('#result'),
        ut  = $('#unittest');

    function switchView() {

        if(defView) {
            //view 2
            ed.toggleClass('v1 v2');
            res.toggleClass('v1 v2');
            ut.toggleClass('v1 v2');

        } else {
            //view 1
            ed.toggleClass('v1 v2');
            res.toggleClass('v1 v2');
            ut.toggleClass('v1 v2');
        }

        utEditor.refresh();
        myCodeMirror.refresh();

        defView = !defView;
        setCookie('defView', defView, 90);
    }

    function getCookie(c_name) {
        var i, x, y, ARRcookies = d.cookie.split(";");

        for (i = 0; i < ARRcookies.length; i++) {
          x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
          y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
          x = x.replace(/^\s+|\s+$/g,"");

          if (x == c_name) {
            return unescape(y);
          }
        }
    }

    function setCookie(c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = escape(value) + ((exdays==null) ? "" : "; expires=" + exdate.toUTCString());
        d.cookie = c_name + "=" + c_value;
    }

    function loadDefaults() {
        if(defView == 'false') {
            switchView();
        }
    }

    return {
        a: myCodeMirror,
        initialCode: initialCode,
        utCode: utCode,
        utEditor: utEditor,
        utFuncs: myCodeMirror,
        bindings: bindings,
        cb: cb,
        setCookie: setCookie,
        getCookie: getCookie,
        loadDefaults: loadDefaults
    };

})(window, document, jQuery, dojo_settings);
