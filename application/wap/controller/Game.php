<?php
/**
 * Member.php
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

namespace app\wap\controller;

use data\service\Promotion;

/**
 * 营销活动 小游戏
 *
 * @author Administrator
 *
 */
class Game extends BaseWap
{
	
	/**
	 * 刮刮乐
	 */
	public function index()
	{
		$promotion = new Promotion();
		$game_id = request()->get("gid", "");
		
		//活动信息
		$info = api('System.Promotion.checkGame', ['game_id' => $game_id]);
		$info = $info['data'];
		if(!is_array($info)) {
		    $redirect = __URL(__URL__ . "/wap/".$info);
			$this->redirect($redirect);
		}
		
		switch ($info['game_type']) {
			case 3:
				//刮刮乐类型游戏
				return $this->view($this->style . "game/scratch_ticket");
				break;
			case 8:
				//大转盘类型游戏
				$this->getRuleJson($info);
				return $this->view($this->style . "game/turntable");
				break;
			case 7:
				//砸金蛋类型游戏
				return $this->view($this->style . "game/smash_eggs");
				break;
		}	
	}
	
	/**
	 * 获取规则描述json
	 */
	public function getRuleJson($gameDetail)
	{
		$rule_arr = [];
		foreach ($gameDetail['rule'] as $key => $val) {
			$data['rule_id'] = $val['rule_id'];
			$data['rule_name'] = $val['rule_name'];
			
			if ($val['type'] == 1) {
				$data['rule_desc'] = '赠送' . trim($val['type_value']);
			} else if ($val['type'] == 2) {
				$data['rule_desc'] = trim($val['type_value']);
			} else if ($val['type'] == 3) {
				$data['rule_desc'] = trim($val['type_value']);
			} else if ($val['type'] == 4) {
				$data['rule_desc'] = trim($val['type_value']);
			}
			$rule_arr[] = $data;
		}
		$rule_arr[] = [
			'rule_id' => -1,
			'rule_name' => '谢谢参与',
			'rule_desc' => '',
		];
		$rule_json = json_encode($rule_arr);
		$this->assign('rule_json', $rule_json);
	}
	
}