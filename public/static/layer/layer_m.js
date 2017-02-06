/**
 * Created by BP01 on 2017/1/19.
 */
var tips = {
    error: function(content){
      layer.msg(
          content,
          {icon:5}
      );
    },
    success:function(content){
        layer.msg(
            content,
            {icon:1}
        )
    }
}