<?php
/**
 * Goods.php
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 山西牛酷信息科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @author : niuteam
 * @date : 2015.1.17
 * @version : v1.0.0.0
 */

namespace addons\NsGoods\admin\controller;
use app\admin\controller\BaseController;

/**
 * 商品控制器
 */
class Goods extends BaseController
{
    public $addon_view_path;
	public function __construct()
	{
		parent::__construct();
		$this->addon_view_path = ADDON_DIR . '/NsGoods/template/';
	}
	
	/**
	 * 根据商品ID查询单个商品，然后进行编辑操作
	 *
	 * 2016年11月25日 09:42:40
	 *
	 */
	public function GoodsSelect()
	{
		$goods_detail = new GoodsService();
		$goods = $goods_detail->getGoodsDetail(request()->get('goodsId'));
		return $goods;
	}
	
	
	/**
	 * 添加商品
	 */
	public function addGoods()
	{
		return view($this->addon_view_path.$this->style . "Goods/editGoods.html");
	}
	
	/**
	 * 编辑商品
	 */
    public function editGoods($goods_id){
        
        return view($this->addon_view_path.$this->style . "Goods/editGoods.html");
    }
	
	/**
	 * 回收站商品恢复
	 */
	public function regainGoodsDeleted()
	{
		if (request()->isAjax()) {
			$goods_ids = request()->post('goods_ids');
			$goods = new GoodsService();
			$res = $goods->regainGoodsDeleted($goods_ids);
			return AjaxReturn($res);
		}
	}
}