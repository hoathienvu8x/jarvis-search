<style> a{text-decoration:none;color:#333;}ul,li {list-style:none;}</style>
<div style="min-width:300px;max-width:450px;margin:20px auto 0;background-color:#eee;padding:10px;">
	<h2 style="padding: 0 0 10px;margin: 0;font-weight: normal;">Wikipedia</h2>
	<div style="width:96%;background-color:#fff;padding:2%;border-bottom:1px solid #eee;min-height:30px;font-size: 16px;font-family: arial,sans-serif;line-height: 25px;"><div id="loading" style="text-align:center;display:none;"><img src="loading.gif" /></div><div id="response"></div></div>
	<input type="text" name="question" id="question" value="" placeholder="Question ?" style="width:100%;padding:2%;border:none;outline:none;outline-color: transparent;font-size: 16px;color: brown;font-family: arial,sans-serif;" />
</div>
<script>
var serialize = function(obj, prefix) {
	var str = [], p;
	for(p in obj) {
		if (obj.hasOwnProperty(p)) {
			var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
			str.push((v !== null && typeof v === "object") ? serialize(v, k) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
		}
	}
	return str.join("&");
};
var parseXml = function(xmlStr) {
    if (typeof window.DOMParser != "undefined") {
        var _parseXml = function(xmlStr) {
            return ( new window.DOMParser() ).parseFromString(xmlStr, "text/xml");
        };
    } else if (typeof window.ActiveXObject != "undefined" && new window.ActiveXObject("Microsoft.XMLDOM")) {
       var _parseXml = function(xmlStr) {
            var xmlDoc = new window.ActiveXObject("Microsoft.XMLDOM");
            xmlDoc.async = "false";
            xmlDoc.loadXML(xmlStr);
            return xmlDoc;
        };
    } else {
        throw new Error("No XML parser found");
    }
    return _parseXml(xmlStr);
}
function ajax(url, method, data, success) {
    var params = typeof data == 'string' ? data : serialize(data);
    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	if (method.toLowerCase() == 'get') {
		xhr.open('GET', url + (url.indexOf('?') != -1 ? '&' : '?')+params);
		params = null;
	} else {
		xhr.open('POST', url);
	}
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { 
			return success(xhr.responseText);
		}
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}
var ip = document.getElementById('question');
if (ip) {
	ip.onkeydown = function(e) {
		var code = e.which || e.keycode
		if (code == 13) {
			var t = this.value;
			document.getElementById('loading').style.display = 'block';
			document.getElementById('response').innerHTML = '';
			ajax('ajax.php','get',{question : t}, function(json) {
				document.getElementById('loading').style.display = 'none';
				try {
					var obj = JSON.parse(json);
					if (obj.status == 'success') {
						document.getElementById('response').innerHTML = obj.data.defined+'<hr /><div>'+obj.data.document+'</div>';
					} else {
						document.getElementById('response').innerHTML = obj.msg;
					}
				} catch(ex) {
					console.log(ex.message);
				}
			});
		}
	};
}
</script>