<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"C:\xampp\htdocs\thinkphp\public/../application/demo\view\hello\hello.html";i:1485161558;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎使用</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>

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
               var data = $(this).attr('data');        //获取按钮的属性值
               var type = {
                   name: data
               }
               var type_data = JSON.stringify(type);    //把属性值打包
//               alert(type_data);
               //这还用注释的么？为了保险还是注释吧，ajax发送属性
               $.ajax({
                   type:"POST",
                   url: "<?php echo url('demo/hello/reload'); ?>",
                   data: type,
                   success:function(data){
                       window.location.href=data;
                   }
               })
           });
       })
    </script>
</html>


