<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"C:\xampp\htdocs\tp5/application/demo\view\hello\setattr.html";i:1492308363;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>设置必要的选择字段</title>
    <link href="/tp5/public/static/dist/css/bootstrap.css" rel="stylesheet">
    <link href="/tp5/public/static/layer/skin/default/layer.css">
    <script src="/tp5/public/static/dist/js/jquery.min.js"></script>
    <script src="/tp5/public/static/dist/js/bootstrap.js"></script>
    <script src="/tp5/public/static/layer/layer.js"></script>
    <script src="/tp5/public/static/layer/layer_m.js"></script>
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

            </ul>
        </div>

    </div>
</nav>
        <div class="container" style="margin-top: 100px">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#game_type" role="tab" data-toggle="tab">游戏类型</a></li>
                <li role="presentation"><a href="#terminal_type" role="tab" data-toggle="tab">终端类型</a></li>
                <li role="presentation"><a href="#sex" role="tab" data-toggle="tab">性别</a></li>
                <li role="presentation"><a href="#age_group" role="tab" data-toggle="tab">年龄</a></li>
                <li role="presentation"><a href="#game_experience" role="tab" data-toggle="tab">游戏经历</a></li>
                <li role="presentation"><a href="#game_year" role="tab" data-toggle="tab">游戏年限</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" style="padding-top: 20px">
                <div role="tabpanel" class="tab-pane active" id="game_type">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($game_type) || $game_type instanceof \think\Collection || $game_type instanceof \think\Paginator): $i = 0; $__LIST__ = $game_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="game_t">请输入游戏类型：</label>
                            <input type="text" class="form-control" id="game_t" placeholder="例如：Android" name="game_type">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>

                </div>
                <div role="tabpanel" class="tab-pane" id="terminal_type">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($terminal_type) || $terminal_type instanceof \think\Collection || $terminal_type instanceof \think\Paginator): $i = 0; $__LIST__ = $terminal_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="terminal_t">请输入终端类型：</label>
                            <input type="text" class="form-control" id="terminal_t" placeholder="例如：PC" name="terminal_type">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="sex">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($sex) || $sex instanceof \think\Collection || $sex instanceof \think\Paginator): $i = 0; $__LIST__ = $sex;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="sex_t">请输入性别类型：</label>
                            <input type="text" class="form-control" id="sex_t" placeholder="例如：男" name="sex">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="age_group">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($age_group) || $age_group instanceof \think\Collection || $age_group instanceof \think\Paginator): $i = 0; $__LIST__ = $age_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="age_t">请输入年龄范围：</label>
                            <input type="text" class="form-control" id="age_t" placeholder="例如：15~20" name="age_group">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="game_experience">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($game_experience) || $game_experience instanceof \think\Collection || $game_experience instanceof \think\Paginator): $i = 0; $__LIST__ = $game_experience;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="game_e">请输入游戏经历范围：</label>
                            <input type="text" class="form-control" id="game_e" placeholder="例如：1~2年" name="game_experience">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="game_year">
                    <form role="form">
                        <div class="form-group">
                            <?php if(is_array($game_year) || $game_year instanceof \think\Collection || $game_year instanceof \think\Paginator): $i = 0; $__LIST__ = $game_year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$name): $mod = ($i % 2 );++$i;?>
                            <label>
                                <input type="checkbox" data="<?php echo $name['id']; ?>"><?php echo $name['value']; ?>
                            </label>
                            <br>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <label for="game_y">请输入游戏年限：</label>
                            <input type="text" class="form-control" id="game_y" placeholder="例如：1~2年" name="game_year">
                        </div>
                        <button type="button" class="btn btn-primary">提交</button>
                        <button type="button" class="btn btn-danger">删除</button>
                    </form>
                </div>

            </div>
        <div class="row" style="margin-top: 20px">
            <h4>请设置完基本参数后请点击下一步进行文件上传</h4>
        </div>
        <button class="btn btn-info" style="margin-top: 20px">下一步</button>
        </div>
<!--<div style="position: fixed;width: 100%;height: 100%;background: #333;opacity: 0.4;z-index: 9999;top: 0;left: 0;"></div>-->
</body>
<script>
    (function(){
            $('.btn-primary').click(function(){
              var val =   $(this).parents('form').find('input[type="text"]').val();               //获取输入框里的值
                var data = $(this).parents('form').serialize();

               if (val == ''){
                   tips.error("请输入数据(～￣▽￣)～");
               }else {
                   $.ajax({
                       type:"POST",
                       url: "<?php echo url('demo/hello/addattr'); ?>",
                       data: data,
                       dataType:"json",
                       success:function(data){
                           console.log(data.sta);    //sta的值若为1则添加成功，若为2，则数据有重复，添加失败，若为3则直接失败
                           if (data.sta == 1){
                               tips.success("提交成功o(￣▽￣)d");
                               setTimeout(function(){
                                   window.location.reload();
                               },3000)
                           }else if (data.sta == 2){
                               tips.error("数据重复(＞﹏＜)");
                           }

                       },
                       error:function(){
                           tips.error("提交失败(＞﹏＜)");
                       }
                   })
               }

            });
        $('.btn-danger').click(function(){
            $(this).parents('form').find('input[type="checkbox"]:checked').each(function(){
                //   console.log(this);
                //alert($(this).attr('data'));
                var id_ = $(this).attr('data');
                var data = {
                    id:id_
                }
                console.log(JSON.stringify(data));
                var data_  = JSON.stringify(data);
                con
                $.ajax({
                    type:"POST",
                    url: "<?php echo url('demo/hello/deleteattr'); ?>" ,
                    data: data,
                    success:function(data){
                        tips.success(data);
                        setTimeout(function(){
                            window.location.reload();
                        },3000)
                    },
                    error:function(){
                        tips.error("删除失败(＞﹏＜)");
                    }

                });


            })
        })

        $('.btn-info').click(function () {
            window.location.assign("<?php echo url('demo/hello/testerandgame'); ?>");
        })


    })()
</script>
</html>