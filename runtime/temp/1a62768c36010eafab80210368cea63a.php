<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\upload.html";i:1487671100;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文件上传</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
</head>
<body>
    <div class="container">
        <form id="form" style="padding: 50px 20px" enctype="multipart/form-data" method="post" action="<?php echo url('demo/hello/upfile'); ?>">
            <label style="padding-top: 20px">请输入测试名称：</label>
            <input type="text" class="form-control" name="test_name">

            <label style="padding-top: 20px">请输入测试次序：</label>
            <input type="text" class="form-control" name="status">

            <label style="padding-top: 20px">请选择被试：</label>
            <select name="tester">
                <?php if(is_array($tester) || $tester instanceof \think\Collection || $tester instanceof \think\Paginator): $i = 0; $__LIST__ = $tester;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tester): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $tester['u_id']; ?>"><?php echo $tester['u_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br/>

            <label style="padding-top: 20px">请选择测试游戏：</label>
            <select name="game">
                <?php if(is_array($game) || $game instanceof \think\Collection || $game instanceof \think\Paginator): $i = 0; $__LIST__ = $game;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$game): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $game['g_id']; ?>"><?php echo $game['g_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br/>

            <label style="padding-top: 20px">请选择index文件：</label>
            <input type="file" name="index">
            <br />

            <label style="padding-top: 20px">请选择tags文件：</label>
            <input type="file" name="tags">
            <br />

            <button id="submit" class="btn btn-info" type="submit">提交</button>
            <button id="return" class="btn btn-info" type="button">返回</button>
        </form>

    </div>

</body>
    <script>
//        (function () {
//            $('button').click(function () {
//                var flag = $(this).attr('id');
////                console.log(flag);
//                if (flag=='return'){
//                    window.location.assign("<?php echo url('demo/hello/testerandgame'); ?>");
//                }else if (flag == 'submit'){
//                var data = new FormData(document.getElementById('form'));
////                console.log(data);
//                $.ajax({
//                    type:"POST",
//                    url:"<?php echo url('demo/hello/upfile'); ?>",
//                    data:data,
////                    dataType:"json",
//                    processData:false,
//                    contentType:false,
//                    success:function (data) {
//                        console.log(data);
//                    }
//                })
//                }
//            })
//        })()
    </script>
</html>