<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>jQuery+php实现ajax文件上传-跨域移动文件</title>
<script type="text/javascript" src="http://shoptest.lin3615.net/public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="jquery.form.js"></script>
</head>
<body>
<div id="main">
   <div class="demo">
   		<div class="btn">
            <span>上传文件</span>
            <input id="fileupload" type="file" name="mypic">
        </div>
        <div class="progress">
    		<span class="bar"></span><span class="percent">0%</span >
		</div>
        <div class="files"></div>
        <div id="showimg"></div>
   </div>
</div>

<script type="text/javascript">
$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	$("#fileupload").wrap("<form id='myupload' action='http://www.upload.com/action.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
				files.html("<b>"+data.name+"("+data.size+"k)</b>");
				var img = data.pic;
				showimg.html("<img src='"+img+"'>");
				btn.html("添加附件");
			},
			error:function(xhr){
				btn.html("上传失败");
				bar.width('0')
				files.html(xhr.responseText);
			}
		});
	});
	
});
</script>
</body>
</html>