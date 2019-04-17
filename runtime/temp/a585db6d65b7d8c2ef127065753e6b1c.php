<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:40:"template/admin\Block\product_pop_up.html";i:1553827288;}*/ ?>
<style>
    .product-pop-up .layui-layer-content{overflow: initial;}
    .product-pop-up .layui-form{padding: 10px;}
    #product_source_value{display: none;}
    .product_diy{
        display: none;
        margin: 0;
    }

    .diy-search-wrap{
        float: right;
    }
    .diy-search-wrap>*{
        display: inline-block;
    }
    .product-pop-up .layui-form.layui-table-view{padding: 0;}

    .product-pop-up .layui-form.layui-table-view th span{
        display: inline-block;
        width: 100%;
    }

    .product-pop-up .layui-form.layui-table-view th .product-info{
        width:70%;
    }

    .product-pop-up .layui-form.layui-table-view th .product-price,.product-pop-up .layui-form.layui-table-view th .product-stock{
        width: 15%;
        padding: 0 10px;
    }

    .product-pop-up tr td:first-child{vertical-align: top;}

    .diy-search-product span{
        display: inline-block;
        text-overflow: ellipsis;
        vertical-align: middle;
        overflow: hidden;
    }

    .diy-search-product .product-name{
        width: 70%;
    }

    .diy-search-product .product-price,.diy-search-product .product-stock{
        width:15%;
        padding: 0 10px;
    }

    .sku-search-wrap{padding: 5px 0;}
    .sku-search-wrap span{
        display: inline-block;
        text-overflow: ellipsis;
        vertical-align: middle;
        overflow: hidden;
        padding: 0 10px;
    }

    .sku-search-wrap .sku-name{
        width: 68.2%;
    }

    .sku-search-wrap .sale-price,.sku-search-wrap .sku-stock{
        width:15%;
    }

    .product-pop-up .layui-badge{
        display: none;
    }
    .layui-tab-title{
        height: 30px;
    }
    .layui-tab-title li{
        font-size: 12px;
        line-height: 30px;
    }
    .layui-tab-title .layui-this:after{
        height: 31px;
    }
</style>
<div class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label sm">产品来源</label>
        <div class="layui-input-block">
            <input type="radio" name="product_source" value="product_category" title="分类" lay-filter="product_source" checked>
            <input type="radio" name="product_source" value="product_label" title="标签" lay-filter="product_source">
            <input type="radio" name="product_source" value="product_brand" title="品牌" lay-filter="product_source">
            <input type="radio" name="product_source" value="product_recommend" title="推荐" lay-filter="product_source">
            <input type="radio" name="product_source" value="product_diy" title="自定义" lay-filter="product_source">
        </div>
    </div>

    <div class="layui-form-item source-value-wrap">
        <label class="layui-form-label source_label sm">产品分类</label>
        <div class="layui-input-block">
            <select name="source_value" lay-verify="source_value" lay-filter="source_value" lay-search>
                <option value="">请选择</option>
            </select>

            <div id="product_source_value">

                <!--产品分类-->
                <?php if(is_array($product_category_list) || $product_category_list instanceof \think\Collection || $product_category_list instanceof \think\Paginator): if( count($product_category_list)==0 ) : echo "" ;else: foreach($product_category_list as $key=>$vo): ?>
                <option value="<?php echo $vo['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $vo['category_name']; ?>"><?php echo $vo['category_name']; ?></option>
                    <?php if(!(empty($vo['child_list']) || (($vo['child_list'] instanceof \think\Collection || $vo['child_list'] instanceof \think\Paginator ) && $vo['child_list']->isEmpty()))): if(is_array($vo['child_list']) || $vo['child_list'] instanceof \think\Collection || $vo['child_list'] instanceof \think\Paginator): if( count($vo['child_list'])==0 ) : echo "" ;else: foreach($vo['child_list'] as $key=>$child_second): ?>
                    <option value="<?php echo $child_second['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $child_second['category_name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_second['category_name']; ?></option>
                        <?php if(!(empty($child_second['child_list']) || (($child_second['child_list'] instanceof \think\Collection || $child_second['child_list'] instanceof \think\Paginator ) && $child_second['child_list']->isEmpty()))): if(is_array($child_second['child_list']) || $child_second['child_list'] instanceof \think\Collection || $child_second['child_list'] instanceof \think\Paginator): if( count($child_second['child_list'])==0 ) : echo "" ;else: foreach($child_second['child_list'] as $key=>$child_third): ?>
                        <option value="<?php echo $child_third['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $child_third['category_name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_third['category_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>

                <!--产品标签-->
                <?php if(is_array($label_list) || $label_list instanceof \think\Collection || $label_list instanceof \think\Paginator): if( count($label_list)==0 ) : echo "" ;else: foreach($label_list as $key=>$vo): ?>
                <option value="<?php echo $vo['group_id']; ?>" data-type="product_label"><?php echo $vo['group_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                <!--产品品牌-->
                <?php if(is_array($brand_list) || $brand_list instanceof \think\Collection || $brand_list instanceof \think\Paginator): if( count($brand_list)==0 ) : echo "" ;else: foreach($brand_list as $key=>$vo): ?>
                <option value="<?php echo $vo['brand_id']; ?>" data-type="product_brand"><?php echo $vo['brand_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>

                <!--产品推荐-->
                <?php if(is_array($recommend_list) || $recommend_list instanceof \think\Collection || $recommend_list instanceof \think\Paginator): if( count($recommend_list)==0 ) : echo "" ;else: foreach($recommend_list as $key=>$vo): ?>
                <option value="<?php echo $vo['recommend_id']; ?>" data-type="product_recommend"><?php echo $vo['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </div>

        </div>
    </div>

    <div class="layui-form-item page-size-wrap">
        <label class="layui-form-label sm">显示数量</label>
        <div class="layui-input-block">
            <input type="number" name="page_size" lay-verify="page_size" placeholder="请输入要显示多少个产品" autocomplete="off" class="layui-input" value="<?php echo $data['page_size']; ?>">
        </div>
    </div>

    <div class="layui-tab product_diy">
        <ul class="layui-tab-title">
            <li class="layui-this">自选产品</li>
            <li>已选产品<span class="layui-badge js-selected-product-count"></span></li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">

                <div class="diy-search-wrap">

                    <div class="layui-form-item">
                        <label class="layui-form-label sm">产品分类</label>
                        <div class="layui-input-block">
                            <select name="search_category" lay-filter="search_category" lay-search>
                                <option value="">请选择</option>
                                <?php if(is_array($product_category_list) || $product_category_list instanceof \think\Collection || $product_category_list instanceof \think\Paginator): if( count($product_category_list)==0 ) : echo "" ;else: foreach($product_category_list as $key=>$vo): ?>
                                <option value="<?php echo $vo['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $vo['category_name']; ?>"><?php echo $vo['category_name']; ?></option>
                                    <?php if(!(empty($vo['child_list']) || (($vo['child_list'] instanceof \think\Collection || $vo['child_list'] instanceof \think\Paginator ) && $vo['child_list']->isEmpty()))): if(is_array($vo['child_list']) || $vo['child_list'] instanceof \think\Collection || $vo['child_list'] instanceof \think\Paginator): if( count($vo['child_list'])==0 ) : echo "" ;else: foreach($vo['child_list'] as $key=>$child_second): ?>
                                    <option value="<?php echo $child_second['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $child_second['category_name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_second['category_name']; ?></option>
                                    <?php if(!(empty($child_second['child_list']) || (($child_second['child_list'] instanceof \think\Collection || $child_second['child_list'] instanceof \think\Paginator ) && $child_second['child_list']->isEmpty()))): if(is_array($child_second['child_list']) || $child_second['child_list'] instanceof \think\Collection || $child_second['child_list'] instanceof \think\Paginator): if( count($child_second['child_list'])==0 ) : echo "" ;else: foreach($child_second['child_list'] as $key=>$child_third): ?>
                                    <option value="<?php echo $child_third['category_id']; ?>" data-type="product_category" data-category-name="<?php echo $child_third['category_name']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_third['category_name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label sm">产品标签</label>
                        <div class="layui-input-block">
                            <select name="search_label" lay-search>
                                <option value="">请选择</option>
                                <?php if(is_array($label_list) || $label_list instanceof \think\Collection || $label_list instanceof \think\Paginator): if( count($label_list)==0 ) : echo "" ;else: foreach($label_list as $key=>$vo): ?>
                                <option value="<?php echo $vo['group_id']; ?>" data-type="product_label"><?php echo $vo['group_name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label sm">品牌</label>
                        <div class="layui-input-block">
                            <select name="search_brand" lay-search>
                                <option value="">请选择</option>
                                <?php if(is_array($brand_list) || $brand_list instanceof \think\Collection || $brand_list instanceof \think\Paginator): if( count($brand_list)==0 ) : echo "" ;else: foreach($brand_list as $key=>$vo): ?>
                                <option value="<?php echo $vo['brand_id']; ?>" data-type="product_brand"><?php echo $vo['brand_name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item" style="margin-bottom: 0;">
                        <label class="layui-form-label sm">产品名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="search_product_name" placeholder="请输入板块名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item" style="margin-bottom: 0;">
                        <label class="layui-form-label sm">产品价格</label>
                        <div class="layui-input-block">
                            <input type="number" name="price_min" placeholder="￥" autocomplete="off" class="layui-input" style="display: inline-block;width: 32%;">
                            <span>-</span>
                            <input type="number" name="price_max" placeholder="￥" autocomplete="off" class="layui-input" style="display: inline-block;width: 32%;">
                        </div>
                    </div>
                    
                    <!--<div class="layui-form-item" style="margin-bottom: 0;">-->
                        <!--<div class="layui-input-block">-->
                            <button class="layui-btn" lay-submit lay-filter="search_product" type="button" style="margin-left:0;">搜索</button>
                            <button type="reset" lay-submit lay-filter="search_product_reset" class="layui-btn layui-btn-primary">重置条件</button>
                        <!--</div>-->
                    <!--</div>-->

                </div>

                <div style="clear: both"></div>

                <table id="diy_search_product_table" lay-filter="diy_search_product_table"></table>

            </div>
            <div class="layui-tab-item">
                <table id="selected_product_table" lay-filter="selected_product_table"></table>
            </div>
        </div>
    </div>

    <div class="layui-form-item" style="margin: 0;">
        <div class="layui-input-block">
            <?php if($data && $data['product_source'] == 'product_diy'): ?>
            <input type="hidden" value="<?php echo $data['source_value']['goods_id']; ?>" id="hidden_product_id" />
            <input type="hidden" value="<?php echo $data['source_value']['sku_id']; ?>" id="hidden_sku_id" />
            <?php else: ?>
            <input type="hidden" value="" id="hidden_product_id" />
            <input type="hidden" value="" id="hidden_sku_id" />
            <?php endif; ?>
            <button class="layui-btn" lay-submit lay-filter="product_save">确定</button>
        </div>
    </div>

</div>
<script>
    var form,table;
    var selected_diy_product_id = { goods_id : [], sku_id : [] };//已选中的产品id集合
    var diy_product_list = [];//产品列表
    var selected_diy_product_list = [];//展示已选中的产品列表

    layui.use(['form','table'], function(){
        form = layui.form;
        table = layui.table;
        form.render();

        //加载修改时的默认数据
        loadUpdateInitData();

        form.on('radio(product_source)', function(data){
            $(".source_label").text("产品" + $(data.elem).attr("title"));
            renderSelect(data.value);
        });

        //清空空格
        form.on('select(source_value)', function(data){
            var value = $.trim($('select[name="source_value"]').next().find('.layui-select-title input').val());
            $('select[name="source_value"]').next().find('.layui-select-title input').val(value);
        });

        form.verify({
            source_value : function(value, item){
                if($("input[name='product_source']:checked").val() != "product_diy" && value == ""){
                    return '请选择' + $(".source_label").text();
                }
            },
            page_size : function (value,item) {
                if($("input[name='product_source']:checked").val() != "product_diy") {
                    if (!/^\d+$/.test(value)) {
                        return '请输入大于1的整数';
                    }
                }
            }

        });

        //清空空格
        form.on('select(search_category)', function(data){
            var value = $.trim($('select[name="search_category"]').next().find('.layui-select-title input').val());
            $('select[name="search_category"]').next().find('.layui-select-title input').val(value);
        });

        //搜索产品
        form.on("submit(search_product)",function (data) {
            table.reload("diy_search_product_table",{
                where : {
                    search_text : data.field.search_product_name,
                    category_id : data.field.search_category,
                    label_id : data.field.search_label,
                    brand_id : data.field.search_brand,
                    price_min : data.field.price_min,
                    price_max : data.field.price_max
                }
            });
        });

        //重置搜索条件
        form.on("submit(search_product_reset)",function (data) {
            $("select[name='search_category']").val("");
            $("select[name='search_label']").val("");
            $("select[name='search_brand']").val("");
            $("input[name='search_product_name']").val("");
            $("input[name='price_min']").val("");
            $("input[name='price_max']").val("");
            form.render();
        });

        form.on('submit(product_save)', function(data){

            var obj = {
                product_source : data.field.product_source,
                source_value : data.field.source_value,
                page_size : data.field.page_size,
            };
            if(data.field.product_source == "product_diy"){
                if(selected_diy_product_id.goods_id.length == 0 && selected_diy_product_id.sku_id.length == 0){
                    layer.msg("请选择产品");
                    return;
                }

                if(selected_diy_product_id.sku_id.length>1000){
                    layer.msg("最多选择999个产品");
                    return;
                }

                selected_diy_product_id.goods_id = selected_diy_product_id.goods_id.toString();
                selected_diy_product_id.sku_id = selected_diy_product_id.sku_id.toString();
                obj.source_value = selected_diy_product_id;
            }

            block.setData(obj);
            layer.close(block.layer_open_index);
            return false;
        });

    });

    //根据产品来源渲染下拉框数据
    function renderSelect(type){
        if(type != "product_diy") {
            $(".product_diy").hide();
            $(".page-size-wrap").show();
            $(".source-value-wrap").show();
            $("select[name='source_value']").find("option[data-type]").remove();
            $("select[name='source_value']").append($("#product_source_value option[data-type='" + type + "']").clone());

            //重新定位产品弹出框
            layer.style(block.layer_open_index, {
                width: '510px',
                left : (($(window).width()-510)/2) + "px",
                height : "237px",
                top : (($(window).height()-237)/2) + "px",
            });
            form.render();
        }else{
            $(".product_diy").show();
            $(".page-size-wrap").hide();
            $(".source-value-wrap").hide();

            //重新定位产品弹出框
            layer.style(block.layer_open_index, {
                width: '900px',
                left : (($(window).width()-900)/2) + "px",
                height : "710px",
                top : (($(window).height()-710)/2) + "px"
            });
            renderDiyProductTable();
        }
    }

    //渲染自定义产品表格
    function renderDiyProductTable(){
        table.render({
            elem: '#diy_search_product_table',
            height: 350,
            url: "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/block/goodsSelectList'); ?>",
            method : 'post',
            request: {
            	pageName: 'page_index',
                limitName: 'page_size' //每页数据量的参数名，默认：limit
            },
            response: { statusCode: 0 },
            renderResponse: function (data) {
                var dataTemp = {};
                dataTemp[this.response.countName] = data.total_count;
                dataTemp[this.response.dataName] = data.data;
                dataTemp[this.response.statusName] = '';
                dataTemp[this.response.msgName] = '';
                diy_product_list = data.data;
                return dataTemp;
            },
            cols: [[
                {
                    type : 'checkbox'
                },
                {
                    title : '<span class="product-info">产品信息</span><span class="product-price">价格</span><span class="product-stock">库存</span>',
                    width : "92%",
                    templet : function(d){

                        var h = '<div class="diy-search-product">';
                        h += '<span class="product-name">' + d.goods_name + '</span>';
                        h += '<span class="product-price">￥' + d.price + '</span>';
                        h += '<span class="product-stock">' + d.stock + '</span>';
                        h += '</div>';

                        return h;
                    }

                },
            ]],
            page: {
                batchOperation : "#batchOperation",
                layout: ['count', 'limit', 'prev', 'page', 'next'],
                limit: 10,
            },
            done : function(res, curr, count){
                //将已经选中的产品进行选中
                for(var i=0;i<selected_diy_product_id.goods_id.length;i++){
                    for (var j=0;j<diy_product_list.length;j++) {
                        if(diy_product_list[j].goods_id == parseInt(selected_diy_product_id.goods_id[i])){
                            $(".product-pop-up .layui-form.layui-table-view tr[data-index='" + diy_product_list[j].LAY_TABLE_INDEX + "'] td:first-child input").prop("checked",true);
                            break;
                        }
                    }
                }
 
                form.render();
                refreshSelectedDiyProduct();

                //处理产品分类下拉框的空格
                var v = $("select[name='search_category']").next().find(".layui-select-title input").val();
                $("select[name='search_category']").next().find(".layui-select-title input").val($.trim(v.replace(/&nbsp;/g,"")));
            }
        });

        table.on('checkbox(diy_search_product_table)', function(obj){
            var _this = "";
            if(obj.type == "all") _this = ".product-pop-up .sku-search-wrap input";
            else _this = ".product-pop-up .sku-search-wrap input[data-product-id='" + obj.data.goods_id + "']";

            $(_this).prop("checked", obj.checked);
            form.render();
            refreshSelectedDiyProduct();
        });

    }

    //渲染已选中的产品
    function renderSelectedDiyProductTable(){
        table.render({
            elem: '#selected_product_table',
            height: 478,
            data : selected_diy_product_list,
            cols: [[
                {
                    title : '<span class="product-info">产品信息</span><span class="product-price">价格</span><span class="product-stock">库存</span>',
                    width : "100%",
                    templet : function(d){

                        var h = '<div class="diy-search-product">';
                        h += '<span class="product-name">' + d.goods_name + '</span>';
                        h += '<span class="product-price">￥' + d.price + '</span>';
                        h += '<span class="product-stock">' + d.stock + '</span>';
                        h += '</div>';

                        return h;
                    }

                },
            ]]
        });

    }

    //sku产品切换事件
    $("body").on("click",".product-pop-up .sku-search-wrap .layui-form-checkbox",function () {
        var input = $(this).prev();
        var parent_index = input.attr("data-parent-index");
        var name = input.attr("name");

        //若选中一个sku产品，则选中父级
        $(".product-pop-up .layui-form.layui-table-view tr[data-index='" + parent_index + "'] td:first-child input").prop("checked",($("input[name='" + name + "']:checked").length>0));

        refreshSelectedDiyProduct();
    });

    //刷新自选的产品集合
    function refreshSelectedDiyProduct(){
        // 遍历产品
        $(".product-pop-up .layui-form.layui-table-view tr td:first-child input").each(function(){
            var index = $(this).parent().parent().parent().attr("data-index");
            if($(this).prop("checked")) {
                if ($.inArray(parseInt(diy_product_list[index].goods_id),selected_diy_product_id.goods_id) == -1) {
                    selected_diy_product_id.goods_id.push(diy_product_list[index].goods_id);
                }

                var is_push = true;//防止重复添加产品对象
                for(var i=0;i<selected_diy_product_list.length;i++){
                    if(selected_diy_product_list[i].goods_id == diy_product_list[index].goods_id){
                        is_push = false;
                        break;
                    }
                }

                if(is_push) selected_diy_product_list.push(diy_product_list[index]);

            }else{
                var product_id_index = $.inArray(parseInt(diy_product_list[index].goods_id),selected_diy_product_id.goods_id);
                if(product_id_index > -1) selected_diy_product_id.goods_id.splice(product_id_index, 1);

                //取消选中，移除产品
                for(var i=0;i<selected_diy_product_list.length;i++){
                    if(selected_diy_product_list[i].goods_id == diy_product_list[index].goods_id){
                        selected_diy_product_list.splice(product_id_index,1);
                        i = 0;
                    }
                }
            }

            var count = $(".product-pop-up .layui-form.layui-table-view tr td:first-child input").length;
            var selected_count = $(".product-pop-up .layui-form.layui-table-view tr td:first-child input:checked").length;

            //产品全部选中后，将最外层th中的复选框进行选中
            $(".product-pop-up .layui-form.layui-table-view tr th:first-child input").prop("checked",(count == selected_count));

        });
        // 遍历sku产品
//         $(".product-pop-up .layui-form.layui-table-view .sku-search-wrap input").each(function () {
//             var parent_index = $(this).attr("data-parent-index");
//             var index = $(this).attr("data-index");
//             if($(this).prop("checked")) {
//                 if ($.inArray(parseInt(diy_product_list[parent_index].sku_list[index].sku_id), selected_diy_product_id.sku_id) == -1) {
//                     selected_diy_product_id.sku_id.push(diy_product_list[parent_index].sku_list[index].sku_id);
//                 }

//                 for (var i = 0; i < selected_diy_product_list.length; i++) {
//                     if (selected_diy_product_list[i].sku_id == diy_product_list[parent_index].sku_list[index].sku_id) {
//                         selected_diy_product_list[i].sku_list = diy_product_list[parent_index].sku_list[index];
//                     }
//                 }
//             }else{
//                 var sku_id_index = $.inArray(parseInt(diy_product_list[parent_index].sku_list[index].sku_id), selected_diy_product_id.sku_id);
//                 if(sku_id_index > -1) {
//                     selected_diy_product_id.sku_id.splice(sku_id_index, 1);
//                 }
//             }
//         });
        if (selected_diy_product_id.goods_id.length > 0) {
            $(".js-selected-product-count").text(selected_diy_product_id.goods_id.length).show();
        } else {
            $(".js-selected-product-count").text("").hide();
        }

        form.render();
        renderSelectedDiyProductTable();
    }

    /**
     * 根据现有的产品id集合查询数据，用于修改时调用
     */
    function getProductList(){
        $.ajax({
            type : "post",
            url: "<?php echo __URL('http://127.0.0.1:8080/index.php/admin/block/goodsSelectList'); ?>",
            data : { product_id : $("#hidden_product_id").val(), page_size : 0 },
            success : function (res) {
                if(res.data){
                    selected_diy_product_list = res.data;
                    refreshSelectedDiyProduct();
                }
            }
        });
    }

    //刷新修改时的默认数据
    function loadUpdateInitData(){
        $("input[name='product_source'][value='<?php echo $data['product_source']; ?>']").prop("checked",true);//设置默认值
        $(".source_label").text("产品" + $("input[name='product_source']:checked").attr("title"));
        renderSelect($("input[name='product_source']:checked").val());
        $("select[name='source_value']").siblings("div.layui-form-select").find("dl dd[lay-value='<?php echo $data['source_value']; ?>']").click();//设置默认值
        if($("#hidden_sku_id").val()) {
            var sku_id = $("#hidden_sku_id").val().split(",");
            for (var i=0;i<sku_id.length;i++){
                selected_diy_product_id.sku_id.push(parseInt(sku_id[i]));
            }
        }
	
        if($("#hidden_product_id").val()) {
            var product_id = $("#hidden_product_id").val().split(",");
            for (var i=0;i<product_id.length;i++){
                selected_diy_product_id.goods_id.push(parseInt(product_id[i]));
            }
            getProductList();
        }

    }
    $('.layui-tab-title li').click(function() {
    	$('.layui-tab-title li').removeClass('layui-this');
    	$(this).addClass('layui-this');
    	var index = $(this).index();
    	$('.layui-tab-item').removeClass('layui-show');
    	$(this).parent().next().children().eq(index).addClass('layui-show');
    })
</script>