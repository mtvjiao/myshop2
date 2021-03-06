<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:43:"template/admin\Order\orderRefundAction.html";i:1552613507;}*/ ?>
<link rel="stylesheet" href="/template/admin/public/css/order_refunds.css">
<div class="modal fade hide" id="confirmRefund" tabindex="-1" aria-labelledby="确认退款" aria-hidden="true" data-backdrop="static" style="width: 650px; overflow: overlay;top:50%;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>确认退款</h3>
			</div>
			<div class="modal-body">
			
				<div class="refunds-block">
					<label>退款金额：</label>
					<input type="text" id="refund_money_input" class="input-common" placeholder="请填写退款金额">
					<span class="operating-hint">请输入退款金额</span>
				</div>
				<div class="refunds-block">
					<label>退款方式：</label>
					<select id="refund_way_select" class="select-common"></select>
				</div>
				<div class="refunds-block">
					<label class="w130">买家申请退款金额：</label>
					<span id="apply_money">0.00元</span>
				</div>
				<div class="refunds-block">
					<label class="w130">买家实际付款金额：</label>
					<span id="pay_money">0.00元</span>
				</div>
				<div class="refunds-block" style="display:none;">
					<label class="w130">自动退还余额：</label>
					<span id="balance_refund">0.00元</span>
				</div>
				
			</div>
			<div class="modal-footer">
				<!-- 温馨提示 -->
				<div class="refunds-block js-not-configured-prompt"></div>
				<input type="hidden" id="confirm_order_id">
				<input type="hidden" id="confirm_order_goods_id">
				<input type="hidden" id="refund_require_money">
				<button class="btn-common js-confirm-refund-ok disabled">确认</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" tabindex="-1" aria-labelledby="退款操作提醒" aria-hidden="true" data-backdrop="static" id="refundOperationReminder" style="width:600px;top: 50%;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>退款操作提醒</h3>
			</div>
			<div class="modal-body">
				<div class="js-confirmation"></div>
				
				<label>备注：</label>
				<textarea rows="4" placeholder="退款备注，最多可输入200个字符。(如果不填，系统将会自动添加默认退款备注，格式为：订单编号:201710180000031，退款方式为:[微信支付]，退款金额:0.00元，退款余额:0.00元)" id="refund-remark"></textarea>
				<div class="refunds-block js-confirmation-prompt"></div>
				<div style="clear: both;"></div>
			</div>
			<div class="modal-footer">
				<!-- 温馨提示 -->
				<button class="btn-common disabled" id="countdown_refund_confirm">确定</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="refuseRefund" style="width:300px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>拒绝退款</h3>
			</div>
			<div class="modal-body">
				<p>您可以拒绝本次退款或者永久拒绝</p>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="refuse_order_id" />
				<input type="hidden" id="refuse_order_goods_id" />
				<button class="btn-common" onclick="refuseRefundType(1)">拒绝本次</button>
				<button class="btn" onclick="refuseRefundType(2)">永久拒绝</button>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="agreeRefund" style="width:300px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>同意退款</h3>
			</div>
			<div class="modal-body">
				<p>确定要同意退款吗？</p>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="agreee_order_id" />
				<input type="hidden" id="agree_order_goods_id" />
				<button class="btn-common" onclick="agreeRefund()">同意</button>
				<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="confirm_receipt" style="width:400px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>确认收货</h3>
			</div>
			<div class="modal-body">
				<div style="height: 35px;line-height: 35px;">
					物流公司：<span id="logistics_company"></span>
				</div>
				<div style="height: 35px;line-height: 35px;">
					物流单号：<span id="logistics_number"></span>
				</div>
				<div style="height: 35px;line-height: 35px;">
					是否入库：
					<label for="no" style="display: inline-block;font-weight: normal;" >否</label>
					<input type="radio" name="isStorage" id="no" style="margin-top: -2px;" checked>
					<label for="yes" style="display: inline-block;font-weight: normal;margin-left: 15px;">是</label><input type="radio" name="isStorage" id="yes" style="margin-top: -2px;margin-left: 5px;">
				</div>
				<div id="storage_num" style="display: none;">
					入库件数：<input type="number">
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" id="hide_order_id" />
				<input type="hidden" id="hide_order_goods_id" />
				<input type="hidden" id="hide_receipt_goods_id" />
				<input type="hidden" id="hide_receipt_sku_id">
				<button class="btn" onclick="confirm_receipt()">同意</button>
				<button class="btn" onclick="cancel_receipt()">取消</button>
			</div>
		</div>
	</div>
</div>
<script src="/template/admin/public/js/order/order_refunds.js"></script>
<script>
//refund_require_money 退款金额
function refundOperation(operation_type, order_id, order_goods_id,refund_require_money){
	if(operation_type == 'agree'){
		//同意退款
		showAgreeRefund(order_id, order_goods_id);
	}else if(operation_type == 'refuse'){
		//拒绝退款
		refuseRefund(order_id,order_goods_id);
	}else if(operation_type == 'confirm_receipt'){
		//确认收货
		orderGoodsConfirmRecieve(order_id,order_goods_id);
	}else if(operation_type == 'confirm_refund'){
		//确认退款
		confirmRefund(order_id,order_goods_id,refund_require_money);
	}
}

function showAgreeRefund(order_id, order_goods_id){
	$("#agreee_order_id").val(order_id);
	$("#agree_order_goods_id").val(order_goods_id);
	var left = ($(window).width()+$('#agreeRefund').outerWidth())/2;
	var top = ($(window).height()-$('#agreeRefund').outerHeight())/2;
	$("#agreeRefund").css({"left": left, "top" : top});
	$("#agreeRefund").modal("show");
}

// 拒绝退款展示
function refuseRefund(order_id,order_goods_id){
	$("#refuse_order_id").val(order_id);
	$("#refuse_order_goods_id").val(order_goods_id);
	var left = ($(window).width()+$('#refuseRefund').outerWidth())/2;
	var top = ($(window).height()-$('#refuseRefund').outerHeight())/2;
	$("#refuseRefund").css({"left": left, "top" : top});
	$("#refuseRefund").modal('show');
}

// 拒绝退款操作
function refuseRefundType(type){
	var order_id = $("#refuse_order_id").val();
	var order_goods_id = $("#refuse_order_goods_id").val();
	if(type == 1){
		refund_url = "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/order/ordergoodsrefuseonce'); ?>";
	} else{
		refund_url = "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/order/ordergoodsrefuseforever'); ?>";
	}
	$.ajax({
		type : "post",
		url : refund_url,
		data : {'order_id':order_id,"order_goods_id":order_goods_id},
		success : function(data) {
			if (data['code'] > 0) {
				showMessage('success', "已拒绝",window.location.reload());
			} else {
				showMessage('error', data["message"]);
			}
		}
	});
}

//确认收货
var isStorage = 0; //是否入库
$("#yes").click(function(){
	isStorage = 1;
	$("#storage_num").show();
});

$("#no").click(function(){
	isStorage = 0;
	$("#storage_num").hide();
});

var goodsNum = 0;
function orderGoodsConfirmRecieve(order_id,order_goods_id){
	$("#confirm_receipt").modal("show");
	$.ajax({
		type : "post",
		url : "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/order/getOrderGoodsDetialAjax'); ?>",
		data : {"order_goods_id":order_goods_id},
		success : function(data) {
			$("#logistics_company").text(data['refund_shipping_company']);
			$("#logistics_number").text(data['refund_shipping_code']);
			goodsNum = data['num'];
			$("#storage_num input").val(data['num']);
			$("#hide_receipt_goods_id").val(data['goods_id']);
			$("#hide_receipt_sku_id").val(data['sku_id']);
		}
	});
	$("#hide_order_id").val(order_id);
	$("#hide_order_goods_id").val(order_goods_id);
}

$("#storage_num input").change(function(){
	if($(this).val()<0){
		$(this).val(1);
	}else if($(this).val()>goodsNum){
		$(this).val(goodsNum);
	}
});

function confirm_receipt(){
	var order_id = $("#hide_order_id").val();
	var order_goods_id = $("#hide_order_goods_id").val();
	var storage_num = $("#storage_num input").val();
	var goods_id = $("#hide_receipt_goods_id").val();
	var sku_id = $("#hide_receipt_sku_id").val();
	$.ajax({
		type : "post",
		url : "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/order/ordergoodsconfirmrecieve'); ?>",
		data : { 'order_id':order_id, "order_goods_id":order_goods_id, "storage_num" : storage_num, "isStorage" : isStorage, "goods_id" : goods_id, "sku_id" : sku_id },
		success : function(data) {
			if (data['code'] > 0) {
				showMessage('success', data["message"],window.location.reload());
			} else {
				showMessage('error', data["message"]);
			}
		}
	});
}

function cancel_receipt(){
	$("#confirm_receipt").modal("hide");
	$("#hide_order_id").val('');
	$("#hide_order_goods_id").val('');
	$("#hide_receipt_sku_id").val('');
	$("#hide_receipt_goods_id").val('');
}
</script>