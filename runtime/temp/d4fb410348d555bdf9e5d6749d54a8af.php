<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"C:\xampp\htdocs\tp5/application/demo\view\hello\hello.html";i:1484721621;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎使用</title>
    <link href="/tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
    <script src="/tp5/public/static/dist/js/jquery.min.js"></script>
    <script src="/tp5/public/static/dist/js/bootstrap.js"></script>

</head>
<body>
    <h3 style="width: 40%;text-align: center;margin: 100px auto 0">友情提示：先要上传文件才可以进行排序功能</h3>
    <div class="container-fluid">
        <div class="row">
           <div class="" style="margin: 100px auto;width: 327px">
               <button class="btn btn-primary btn-lg" data="set">文件设置</button>
               <button class="btn btn-primary btn-lg col-md-offset-4" data="sort">进行排序</button>
           </div>
        </div>
    </div>
</body>
    <script>
       $(document).ready(function(){
           $('button').click(function(){
//              alert($(this).attr('data'));
               var data = $(this).attr('data');
               var type = {
                   name: data
               }
               var type_data = JSON.stringify(type);
               alert(type_data);
               $.ajax({
                   type:"POST",
                   url: "http://localhost/tp5/index.php/demo/hello/reload",
                   data: type,
                   success:function(data){
                       window.location.href=data;
                   }
               })
           });
       })
    </script>
</html>


