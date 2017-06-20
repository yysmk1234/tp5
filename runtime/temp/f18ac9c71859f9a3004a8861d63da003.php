<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"C:\xampp\htdocs\tp5/application/demo\view\hello\count.html";i:1495780167;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>排序</title>
    <link href="/tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/tp5/public/static/layer/skin/default/layer.css">
    <script src="/tp5/public/static/dist/js/jquery.min.js"></script>
    <script src="/tp5/public/static/dist/js/bootstrap.js"></script>
    <script src="/tp5/public/static/layer/layer.js"></script>
    <script src="/tp5/public/static/layer/layer_m.js"></script>
    <script src="/tp5/public/static/cookie/jquery.cookie.js"></script>
    <style>
        .count{
            cursor: pointer;
            width: 100px;
        }
        .count:hover{
            background: #C4C9DF;
        }
        .clomn_w{
            width: 50px;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">文件设置<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url('demo/hello/setattr'); ?>">添加关键字段</a></li>
                        <li><a href="<?php echo url('demo/hello/testerandgame'); ?>">添加被试</a></li>
                        <li><a href="<?php echo url('demo/hello/upload'); ?>">文件上传</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo url('demo/hello/project'); ?>">排序计算</a></li>
                <li><a href="<?php echo url('demo/hello/projectcount'); ?>">数据计算</a></li>
            </ul>
        </div>

    </div>
</nav>
<div class="" style="margin-top: 50px;padding-top: 40px">

    <table class="table table-condensed" style="margin-top: 20px" id="c_data">
        <tr>
            <td>game</td>
            <td>emoi</td>
            <td>scl</td>
            <td>High alpha</td>
            <td>gamma</td>
            <td class="count" data="emoi_">emoi_</td>
            <td class="count" data="scl_">scl_</td>
            <td class="count" data="higt_a_">high_alpha_</td>
            <td class="count" data="gamma_">gamma_</td>
            <td>emoi_sd</td>
            <td>scl_sd</td>
            <td>high_alpha_sd</td>
            <td>gamma_sd</td>
            <td class="count" data="emoi_sd_">emoi_sd_</td>
            <td class="count" data="scl_sd_">scl_sd_</td>
            <td class="count" data="high_sd_">high_alpha_sd_</td>
            <td class="count" data="gamma_sd_">gamma_sd_</td>
            <td>emoi_y</td>
            <td>scl_y</td>
            <td>high_y</td>
            <td>gamma_y</td>
            <td class="count" data="emoi_y_">emoi_y_</td>
            <td class="count" data="scl_y_">scl_y_</td>
            <td class="count" data="high_y_">high_y_</td>
            <td class="count" data="gamma_y_">gamma_y_</td>
        </tr>
        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $data['game']; ?></td>
            <td title="<?php echo $data['emoi']; ?>"><?php echo round($data['emoi'],4) ?></td>
            <td title="<?php echo $data['scl']; ?>"><?php printf("%.4e\n", $data['scl']) ?></td>
            <td title="<?php echo $data['High_alpha']; ?>"><?php echo round($data['High_alpha'],4) ?></td>
            <td title="<?php echo $data['gamma']; ?>"><?php echo round($data['gamma'],4) ?></td>
            <td><?php echo $data['emoi_']; ?></td>
            <td><?php echo $data['scl_']; ?></td>
            <td><?php echo $data['Higt_a_']; ?></td>
            <td><?php echo $data['gamma_']; ?></td>
            <td title="<?php echo $data['emoi_sd']; ?>"><?php echo round($data['emoi_sd'],4) ?></td>
            <td title="<?php echo $data['scl_sd']; ?>"><?php printf("%.4e\n", $data['scl_sd']) ?></td>
            <td title="<?php echo $data['high_sd']; ?>"><?php echo round($data['high_sd'],4) ?></td>
            <td title="<?php echo $data['gamma_sd']; ?>"><?php echo round($data['gamma_sd'],4) ?></td>
            <td><?php echo $data['emoi_sd_']; ?></td>
            <td><?php echo $data['scl_sd_']; ?></td>
            <td><?php echo $data['high_sd_']; ?></td>
            <td><?php echo $data['gamma_sd_']; ?></td>
            <td title="<?php echo $data['emoi_y']; ?>"><?php echo round($data['emoi_y'],4) ?></td>
            <td title="<?php echo $data['scl_y']; ?>"><?php echo round($data['scl_y'],4) ?></td>
            <td title="<?php echo $data['high_y']; ?>"><?php echo round($data['high_y'],4) ?></td>
            <td title="<?php echo $data['gamma_y']; ?>"><?php echo round($data['gamma_y'],4) ?></td>
            <td><?php echo $data['emoi_y_']; ?></td>
            <td><?php echo $data['scl_y_']; ?></td>
            <td><?php echo $data['high_y_']; ?></td>
            <td><?php echo $data['gamma_y_']; ?></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </table>

</div>
</body>
<script>
    $(document).on("click",".count",function () {
        var data = {
            type:JSON.stringify($(this).attr('data')),
            project_id:JSON.stringify($.cookie("project_id"))
        }
        $('#c_data').empty();
        var df_str ="<tr>"+
            "<td>"+"game"+"</td>"+
            "<td>"+"emoi"+"</td>"+
            "<td>"+"scl"+"</td>"+
            "<td>"+"High_alpha"+"</td>"+
            "<td>"+"gamma"+"</td>"+
            "<td class='count' data='emoi_'>"+"emio_"+"</td>"+
            "<td class='count' data='scl_'>"+"scl_"+"</td>"+
            "<td class='count' data='Higt_a_'>"+"High_alpha_"+"</td>"+
            "<td class='count' data='gamma_'>"+"gamma_"+"</td>"+
            "<td>"+"emoi_sd"+"</td>"+
            "<td>"+"scl_sd"+"</td>"+
            "<td>"+"high_alpha_sd"+"</td>"+
            "<td>"+"gamma_sd"+"</td>"+
            "<td class='count' data='emoi_sd_'>"+"emoi_sd_"+"</td>"+
            "<td class='count' data='scl_sd_'>"+"scl_sd_"+"</td>"+
            "<td class='count' data='high_sd_'>"+"high_alpha_sd_"+"</td>"+
            "<td class='count' data='gamma_sd_'>"+"gamma_sd_"+"</td>"+
            "<td>"+"emoi_y"+"</td>"+
            "<td>"+"scl_y"+"</td>"+
            "<td>"+"high_alpha_y"+"</td>"+
            "<td>"+"gamma_y"+"</td>"+
            "<td class='count' data='emoi_y_'>"+"emoi_y_"+"</td>"+
            "<td class='count' data='scl_y_'>"+"scl_y_"+"</td>"+
            "<td class='count' data='high_y_'>"+"high_alpha_y_"+"</td>"+
            "<td class='count' data='gamma_y_'>"+"gamma_y_"+"</td>"+
            "</tr>" ;
        $('#c_data').append(df_str);
        $.ajax({
            url:"<?php echo url('count/index/count'); ?>",
            data:data,
            type:"POST",
            dataType:"JSON",
            success:function (data) {
//                    console.log(data);
                for(var x in data){
//                        console.log(data[x]);
//                    var data_emoi = Number(data[x].emoi);
                    var str = "<tr>"+
                        "<td>"+data[x].game+"</td>"+
                        "<td title="+data[x].emoi+">"+Number(data[x].emoi).toFixed(4)+"</td>"+
                        "<td title="+data[x].scl+">"+Number(data[x].scl).toExponential(4)+"</td>"+
                        "<td title="+data[x].High_alpha+">"+Number(data[x].High_alpha).toFixed(4)+"</td>"+
                        "<td title="+data[x].gamma+">"+Number(data[x].gamma).toFixed(4)+"</td>"+
                        "<td>"+data[x].emoi_+"</td>"+
                        "<td>"+data[x].scl_+"</td>"+
                        "<td>"+data[x].Higt_a_+"</td>"+
                        "<td>"+data[x].gamma_+"</td>"+
                        "<td title="+data[x].emoi_sd+">"+Number(data[x].emoi_sd).toFixed(4)+"</td>"+
                        "<td title="+data[x].scl_sd+">"+Number(data[x].scl_sd).toExponential(4)+"</td>"+
                        "<td title="+data[x].high_sd+">"+Number(data[x].high_sd).toFixed(4)+"</td>"+
                        "<td title="+data[x].gamma_sd+">"+Number(data[x].gamma_sd).toFixed(4)+"</td>"+
                        "<td>"+data[x].emoi_sd_+"</td>"+
                        "<td>"+data[x].scl_sd_+"</td>"+
                        "<td>"+data[x].high_sd_+"</td>"+
                        "<td>"+data[x].gamma_sd_+"</td>"+
                        "<td title="+data[x].emoi_y+">"+Number(data[x].emoi_y).toFixed(4)+"</td>"+
                        "<td title="+data[x].scl_y+">"+Number(data[x].scl_y).toFixed(4)+"</td>"+
                        "<td title="+data[x].high_y+">"+Number(data[x].high_y).toFixed(4)+"</td>"+
                        "<td title="+data[x].gamma_y+">"+Number(data[x].gamma_y).toFixed(4)+"</td>"+
                        "<td>"+data[x].emoi_y_+"</td>"+
                        "<td>"+data[x].scl_y_+"</td>"+
                        "<td>"+data[x].high_y_+"</td>"+
                        "<td>"+data[x].gamma_y_+"</td>"+

                        "</tr>";
                    $('#c_data').append(str);
                }
            }
        });
    });




</script>
</html>