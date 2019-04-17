var lang_data = {};
$(document).ready(function(){
	lang(["member_abolish_successful"],function(res){
		lang_data = res;
	});
	
	//取消收藏
	$(".cancel").click(function(){
		var fav_id = $(this).children("input").val();
		var fav_type = "goods";
		var self = $(this);
		api("System.Member.cancelCollection",{"fav_id" : fav_id, "fav_type" : fav_type },function(res){
			self.parent().remove();
			show(lang_data.member_abolish_successful);
			location.href = __URL(SHOPMAIN+'/member/collection');
		})
	})
});

//分页
$('#myPager').pager({
	linkCreator: function(page, pager) {
		return __URL(SHOPMAIN + "/member/collection?page_index="+page);
	}
});