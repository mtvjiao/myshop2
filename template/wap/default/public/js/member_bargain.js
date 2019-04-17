$(function(){
    GetDataList(1);
});
var is_load = false;//防止重复加载
function GetDataList(page){
    if(page == undefined || page == "") page = 1;
    $("#page").val(page);//设置当前页
    $("#status").val(status);//保存当前状态
    if(is_load) return false;
    is_load = true;
    api("NsBargain.Bargain.myBargain",{"page_index" : page},function (res) {
        var data = res.data.list;
        $("#page_count").val(data['page_count']);//总页数
        if(page == 1){
            var datahtml = "";
        }else if(page > 1){
            var datahtml = $('#list_content').html();
        }
        var html = '';
        if(data['data'].length==0){
            html += '<p style="padding: 50px;background: #ffffff;text-align: center;">您当前还没有砍价哦</p>';
        }else{
            html += '<ul>';
            for(var i=0;i<data['data'].length;i++){
                var curr = data['data'][i];
                html += '<li>';
                html += '<header>';
                html += '<label>';
                html += '<span>发起了砍价</span>';
                if(curr.status == 1){
                    html += '<a href="#">待分享</a>';
                }else if(curr.status == 2){
                    html += '<a href="#">砍价成功</a>';
                }
                html += '</label>';
                html += '<time>' + timeStampTurnTime(curr.start_time) + '</time>';
                html += '</header>';
                html += '<article onclick="location.href=\'' + __URL(APPMAIN+"/goods/detail?goods_id=" + curr.goods_info.goods_id + "&bargain_id=" + curr.bargain_id) + '\'">';
                html += '<div class="goods-img">';
                html += '<img src="' + __IMG(curr.goods_info.pic.pic_cover_micro) + '"/>';
                html += '</div>';
                html += '<div class="goods-info">';
                html += '<label>' + curr.goods_info.goods_name + '</label>';
                html += '<span class="money">￥' + curr.goods_money +'</span>';
                html += '</div>';
                html += '</article>';
                html += '<footer>';
                if(curr.status == 1){
                    html += '<span>还差<strong>' + (curr.goods_money-curr.bargain_money) + '</strong>元，<time data-end-time="' + curr.end_time + '">剩余00:00:00</time></span>';
                    html += '<button class="btn-invitation-friend" data-launch-id="' + curr.launch_id + '">邀请好友砍价</button>';
                }else if(curr.status == 2){
                    html += '<ul>';
                    html += '<li>';
                    html += '<div>';
                    html += '<img src="' + __IMG(data['user_info']['user_headimg']) + '"/>';
                    html += '</div>';
                    html += '</li>';
                    html += '</ul>';
                    html += '<button class="btn-order-info" onclick="location.href=\'' + __URL(APPMAIN+'/Goods/bargainLaunch?launch_id=' + curr.launch_id)  + '\'">查看砍价详情</button>';
                }
                html += '</footer>';
                html += '</li>';
            }
            html += '</ul>';
            var user_img = WAPIMG+"/member_default.png";
            if(data['user_info']['user_headimg'] != ""){
                user_img = __IMG(data['user_info']['user_headimg']);
            }
            $(".user-info img").attr("src",user_img);
            $(".user-info h4").text(data['user_info'].user_name);
            $(".user-info").show();
        }
        $(".spelling-order-list").html(html);
        CountDown();
        $(".btn-invitation-friend").click(function(){
            location.href = __URL(APPMAIN + "/Goods/bargainLaunch?launch_id=" + $(this).attr("data-launch-id"));
        });
        is_load = false;
    })
}
function CountDown(){
    $(".spelling-order-list li time").each(function(){
        var time = $(this).attr("data-end-time");
        var obj = $(this);
        if(null != time && "" != time){
            var sys_second = (time-$("#ms_time").val());///1000;
            if(sys_second>1){
                sys_second -= 1;
                var day = Math.floor((sys_second / 3600) / 24);
                var hour = Math.floor((sys_second / 3600) % 24);
                var minute = Math.floor((sys_second / 60) % 60);
                var second = Math.floor(sys_second % 60);
                var s_hour = hour<10?"0"+hour:hour;
                var s_minute = minute<10?"0"+minute:minute;
                var s_second = second<10?"0"+second:second;
                var str = s_hour + ":" + s_minute + ":" + s_second;
                obj.text("剩余" + str);
            }else{
                obj.text("拼单已结束");
            }
            var timer = setInterval(function(){
                if (sys_second > 1) {
                    sys_second -= 1;
                    var day = Math.floor((sys_second / 3600) / 24);
                    var hour = Math.floor((sys_second / 3600) % 24);
                    var minute = Math.floor((sys_second / 60) % 60);
                    var second = Math.floor(sys_second % 60);
                    var s_hour = hour<10?"0"+hour:hour;
                    var s_minute = minute<10?"0"+minute:minute;
                    var s_second = second<10?"0"+second:second;
                    var str = s_hour + ":" + s_minute + ":" + s_second;
                    obj.text("剩余" + str);
                } else {
                    obj.text("拼单已结束");
                    clearInterval(timer);
                }
            }, 1000);
        }
    });
}
//滑动到底部加载
$(window).scroll(function(){
    var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
    var content_box_height = parseFloat($("#list_content").height());
    if(totalheight - content_box_height >= 80){
        if(!is_load){
            var page = parseInt($("#page").val()) + 1;//页数
            var total_page_count = $("#page_count").val(); // 总页数
            var status = $('#status').val();
            if(page > total_page_count){
                return false;
            }else{
                GetDataList(page);
            }
        }
    }
});