// MENU
// ������� ��� ������������� ������� ����

var currentState;// = document.location.hash;;
function buildURL(anchor) {
    return document.location.toString().substr(0, document.location.toString().indexOf('#')) + 
    anchor.substr(1);
}

function selectmenu(type,data)
{
    //alert(111);
    if (data=='') {
        $('.menuitemcp').removeClass('menuitemsel');
        ;
        $('#'+type).addClass('menuitemsel');
        ;
        var html=$.ajax({
            url:type+'.php',
            data:data,
            async: false
        }).responseText;
        document.location.hash = '#'+type;
        currentState = document.location.hash;
        //alert(html);
        $('#maindiv').html(html);
    //closeedit();
    } else {
        window.location = data;
    }
}

function checkLocalState() {
    if (document.location.hash && document.location.hash != currentState) {
        currentState = document.location.hash;
        //alert(currentState.substr(1));
        selectmenu(currentState.substr(1),'');
    }
}


$(document).ready(function() {
    
    setInterval(checkLocalState, 500);
});

// ������� �������, ���� hotkeys.js
// ��� ������ ��������� JQuery. �����: ���� ����� ������

$(function(){
    // ������ ���������� ��� ������ ������, 
    // ������ ����������� ������������� ������:
    var keyCodes = {
            // Alphabet
            a:65, 
            b:66, 
            c:67, 
            d:68, 
            e:69,
            f:70, 
            g:71, 
            h:72, 
            i:73, 
            j:74,
            k:75, 
            l:76, 
            m:77, 
            n:78, 
            o:79,
            p:80, 
            q:81, 
            r:82, 
            s:83, 
            t:84,
            u:85, 
            v:86, 
            w:87, 
            x:88, 
            y:89, 
            z:90,
            // Numbers
            n0:48, 
            n1:49, 
            n2:50, 
            n3:51, 
            n4:52,
            n5:53, 
            n6:54, 
            n7:55, 
            n8:56, 
            n9:57,
            // Controls
            tab:  9, 
            enter:13, 
            shift:16, 
            backspace:8,
            ctrl:17, 
            alt  :18, 
            esc  :27, 
            space    :32,
            menu:93, 
            pause:19, 
            cmd  :91,
            insert  :45, 
            home:36, 
            pageup  :33,
            'delete':46, 
            end :35, 
            pagedown:34,
            // F*
            f1:112, 
            f2:113, 
            f3:114, 
            f4 :115, 
            f5 :116, 
            f6 :117,
            f7:118, 
            f8:119, 
            f9:120, 
            f10:121, 
            f11:122, 
            f12:123,
            // numpad
            np0: 96, 
            np1: 97, 
            np2: 98, 
            np3: 99, 
            np4:100,
            np5:101, 
            np6:102, 
            np7:103, 
            np8:104, 
            np9:105,
            npslash:11,
            npstar:106,
            nphyphen:109,
            npplus:107,
            npdot:110,
            // Lock
            capslock:20, 
            numlock:144, 
            scrolllock:145,
			
            // Symbols
            equals: 61, 
            hyphen   :109, 
            coma  :188, 
            dot:190,
            gravis:192, 
            backslash:220, 
            sbopen:219, 
            sbclose:221,
            slash :191, 
            semicolon: 59, 
            apostrophe : 222,
            // Arrows
            aleft:37, 
            aup:  38, 
            aright:39, 
            adown:40

    // ����� ��������, ��� �� ��� ���� ������
    };
	
    // ��������� ���������� ��������� 
    // (��������� �������� ��� �������� � ��������)
    var prompt = $("<div class='hotprompt'>-</div>");
    prompt.css("position", "absolute");
    prompt.css("padding", "1px 3px");
    prompt.css("font-size", "8px");
    prompt.css("background-color", "orange");
    prompt.css("color", "black");
    prompt.css("opacity", "0.8"); // ������ ���������������� �� ��������
    prompt.css("border", "1px solid black");
    // �������� � ������ �������, ������� ���� ��� ������:
    prompt.css("border-radius", "7px 7px 0px 7px"); 
    prompt.css("-moz-border-radius", "7px 7px 0px 7px");
	
    // �������� ��������� � ��������
    var showHotPrompts = function(){
        if($(".hotprompt").length > 0) return ;
        // ��� ������� ������ ��� ������, � ��������� hotkey
        $("a[hotkey], input[hotkey]").each(function(a){
            p = prompt.clone(); // ��������� ���������, ������� �� �������
            p.html($(this).attr("hotkey")); //�������� � ��� ������ "Ctrl + .."
            p.insertAfter($(this)); 
            // ��������� ��������� ����� ����� ����� ������
            // ��������� � ���, ����� ������ ������ 
            // �������� �� ����� ������� ���� ������:
            p.css("left", $(this).position().left - p.width());
            p.css("top", $(this).position().top - p.height());
        });
    }
	
    // ������ ��� ���������
    var hideHotPrompts = function(){
        // ����� �� ������ ��������:
        $("a[hotkey], input[hotkey]").each(function(a){
            $(".hotprompt").remove();
        });
    }
    
    // ������ �������, ����������� ������ �� ������� ������������� ��������
    var in_array = function(needle, haystack){
        for (key in haystack)
            if (haystack[key] == needle) return true;
        return false;
    }
	
	
    // ���� ������ ������� �� ��������
    $("html").keydown(function(e){
        var lastGood = false;
        // �� ���������� ��� ������ � ������, � ������� ���� ������� hotkey
        $("a[hotkey], input[hotkey]").each(function(a){
            var hotkey = $(this).attr("hotkey"); 
            // �������� �������� (�������� Ctrl + E)
            var words = hotkey.split("+"); //��������� �������� ������
            // ��������� ������� � ���� ������� - ��� ���� �������:
            var key = words.pop().replace(/\s/,""); 
            // ����������� � � �������� ��� �������
            var syskeys = new Array();
            // ���������� � ������� ������� 
            // ��������� ���������� (Ctrl, Alt, Shift)
            for(var i in words) syskeys.push(words[i].replace(/\s+/g,""));
            if(keyCodes[key] != e.keyCode) return; 
            //��� ������� �� ������� - �����
            if(in_array('Ctrl', syskeys)    && !e.ctrlKey) return; 
            ////Ctrl �� ������� - �����
            if(in_array('Alt', syskeys)     && !e.altKey) return;  
            ////Alt �� ������� - �����
            if(in_array('Shift', syskeys)   && !e.shiftKey) return;
            ////Shift �� ������� - �����
            //���� �� �������� ��������� ���������� ������, 
            //�� ��������� ������ ���������:
            lastGood = $(this); 
            ////���������� ������ �������� ��������� ���������� ��������
        });
        //���� ���������� ��� ������� ���������� ������ 
        //(��� �����) ���� �������:
        if(lastGood){ 
            // �� ���� ��� �����, �� ��������:
            if(lastGood.attr("type") == 'submit')
                $(lastGood.context.form).submit();
            else{ // � ���� ������, �� �������
                var href = lastGood.attr("href");
                lastGood.click();
            }
            return false; // � ��������� ��������� �������� ��������
        }
		
        // � ��� ��� ������ �������:
        // ���� ������ ������� CTRL - �� ���������� ��������� ����� ������
        if(e.keyCode == keyCodes.ctrl){ 
            showHotMap();
            showHotPrompts(); //� ���������
        }
    });
	
    // �� ���������� ����� ������� - �������� ��������� � ����� ������
    $("html").keyup(function(e){
        if(e.keyCode == keyCodes.ctrl){ 
            hideHotPrompts();
            hideHotMap();
        }
    });

    // ���� ����-������ �������� - �������� ��� ��������� � ����� ������
    $("html").click(function(e){
        hideHotPrompts();
        hideHotMap();
    });
    
    // �������� ��������� ����� ������ 
    // (��� ����� ������� ������� �� ������� ������)
    var showHotMap = function(){
        if($(".hotsitemap").length > 0) return ;
        // ������� ���������� �������
        var hotmap = $("<div>");
        $("body").append(hotmap);
        hotmap.addClass("hotsitemap"); 
        hotmap.css('background-color', 'orange');
        hotmap.css('position', 'fixed'); //�� ����� �� �������� �� ���������
        hotmap.css('color', 'black');
        hotmap.css('top', '200px');      //������ ������ �� �������� ��������
        hotmap.css('padding', '20px');
        hotmap.css("border-radius", "10px"); //������ ��������, ��� � �����
        hotmap.css("-moz-border-radius", "10px");//���� � �������
        hotmap.append("<h3>������� �������</h3>");
        // � ��������� ��� ������ ��������� � ����������� �� ������
        $("a[hotkey]").each(function(){
            var hotkey = $(this).attr("hotkey");
            var value = $(this).html(); 
            // ����� ������ <a href=...>��� ����</a>
            var title = $(this).attr("title"); // <a title='��� ����'>...
            // ������ �� ���� ����� ���, ��� �������� 
            // (������ ����� �� ����� �������������)
            var display_text = value.length > title.length ? value : title;
            // � ���������� ���� ����� � ������� � �������:
            hotmap.append("<b>"+hotkey+"</b> "+display_text+"<br />"); 
        });
    }
    
    // ������ ��� �����
    var hideHotMap = function(){
        $(".hotsitemap").remove();
    }
});