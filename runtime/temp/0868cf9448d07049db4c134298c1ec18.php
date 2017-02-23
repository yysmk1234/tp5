<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:67:"C:\xampp\htdocs\tp5\public/../application/demo\view\hello\sort.html";i:1487835061;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>排序</title>
    <link href="../../../../public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../public/static/layer/skin/default/layer.css">
    <script src="../../../../public/static/dist/js/jquery.min.js"></script>
    <script src="../../../../public/static/dist/js/bootstrap.js"></script>
    <script src="../../../../public/static/layer/layer.js"></script>
    <script src="../../../../public/static/layer/layer_m.js"></script>
    <script src="../../../../public/static/cookie/jquery.cookie.js"></script>
</head>
<body>
    <div class="container" style="margin-top: 50px;padding-top: 40px">
        <label style="margin-top: 20px">组名:<?php echo $group_n; ?></label>
        <br />

        <button class="btn btn-info add">添加数据</button>
        <table class="table table-condensed" style="margin-top: 20px">
            <tr>
                <td>emoi</td>
                <td>scl</td>
                <td>High alpha</td>
                <td>gamma</td>
                <td>tag</td>
            </tr>
            <?php if(count($data)==0): ?>
            <tr><td style="border: none ;width: 200px">暂时没有数据</td></tr>
            <?php else: if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $data['emoi']; ?></td>
                <td><?php echo $data['scl']; ?></td>
                <td><?php echo $data['High_alpha']; ?></td>
                <td><?php echo $data['gamma']; ?></td>
                <td><?php echo $data['tag']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </table>
    </div>

    <div class="container show_data" style="border: 1px solid #333;display: none;border-radius: 10px">

        <form>
            <label>选择筛选条件</label>
            <br />
            <label style="padding-top: 20px">请选择性别：</label>
            <select name="sex">
                <?php if(is_array($sex) || $sex instanceof \think\Collection || $sex instanceof \think\Paginator): $i = 0; $__LIST__ = $sex;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sex): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $sex['id']; ?>"><?php echo $sex['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择年龄：</label>
            <select name="age_group">
                <?php if(is_array($age_group) || $age_group instanceof \think\Collection || $age_group instanceof \think\Paginator): $i = 0; $__LIST__ = $age_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$age): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $age['id']; ?>"><?php echo $age['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择游戏经历：</label>
            <select name="game_experience">
                <?php if(is_array($game_experience) || $game_experience instanceof \think\Collection || $game_experience instanceof \think\Paginator): $i = 0; $__LIST__ = $game_experience;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$exp): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $exp['id']; ?>"><?php echo $exp['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px">请选择游戏年限：</label>
            <select name="game_year">
                <?php if(is_array($game_year) || $game_year instanceof \think\Collection || $game_year instanceof \think\Paginator): $i = 0; $__LIST__ = $game_year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $year['id']; ?>"><?php echo $year['value']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />

            <label style="padding-top: 20px;padding-bottom: 20px">请选择测试顺序：</label>
            <select name="status">
                <?php if(is_array($status) || $status instanceof \think\Collection || $status instanceof \think\Paginator): $i = 0; $__LIST__ = $status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$statu): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $statu['status_']; ?>"><?php echo $statu['status_']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />



            <button class="btn btn-info choose" type="button">筛选</button>
            <button class="btn btn-info add_data" type="button">添加</button>
        </form>
        <table class="table table-condensed" style="margin-top: 20px" id="c_data">
            <tr>
                <td>#</td>
                <td>emoi</td>
                <td>scl</td>
                <td>High alpha</td>
                <td>gamma</td>
                <td>tag</td>
            </tr>

        </table>
    </div>

</body>
<script>
    (function () {
        $('.btn-info,.add').click(function () {
            $('.show_data').show();
        });
        $('.choose').click(function () {
            var data = $('form').serialize();
            $('#c_data').empty();
            var df_str ="<tr>"+
                "<td>"+"#"+"</td>"+
                "<td>"+"emoi"+"</td>"+
                "<td>"+"scl"+"</td>"+
                "<td>"+"High alpha"+"</td>"+
                "<td>"+"gamma"+"</td>"+
                "<td>"+"tag"+"</td>"+
                "</tr>" ;
            $('#c_data').append(df_str);
            $.ajax({
                url:"<?php echo url('demo/hello/sortdata'); ?>",
                type:"POST",
                data:data,
                dataType:"json",
                success:function (data) {
//                    console.log(data);
                    for(var x in data){
                        console.log(data[x]);
                        var str = "<tr>"+
                            "<td>"+"<input type='checkbox' name='test_id' value="+data[x].id+">"+"</td>"+
                            "<td>"+data[x].emoi+"</td>"+
                            "<td>"+data[x].scl+"</td>"+
                            "<td>"+data[x].High_alpha+"</td>"+
                            "<td>"+data[x].gamma+"</td>"+
                            "<td>"+data[x].tag+"</td>"+
                            "</tr>";
                    $('#c_data').append(str);
                    }
                }
            });
        });
        $('.add_data').click(function () {
            var arr_data = [];
            var i = 0;
            $('#c_data').find("input[type='checkbox']:checked").each(function () {
                var test_id = $(this).val();
                arr_data['name'+i] = test_id;
//                console.log(test_id);
                i++;
//               alert($.cookie("group_name"));
            });
            console.log(arr_data);
//            var re_data = JSON.stringify(arr_data);
            $.ajax({
                url:"<?php echo url('demo/hello/adddata'); ?>",
                type:"POST",
                data: arr_data,
//                dataType:"json",
                success:function (data) {
                    console.log(data);
                }
            });
//            alert(arr_data);
//            alert(data);
        });
    })()
</script>
</html>