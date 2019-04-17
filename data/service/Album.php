<?php
/**
 * Album.php
 *
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 山西牛酷信息科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @author : niuteam
 * @date : 2015.4.24
 * @version : v1.0.0.0
 */

namespace data\service;

/**
 * 相册以及图片业务层
 */
use data\service\BaseService as BaseService;
use data\model\AlbumClassModel as AlbumClassModel;
use data\model\AlbumPictureModel as AlbumPictureModel;
use data\model\NsGoodsModel;
use data\model\NsGoodsDeletedModel;
use data\service\Upload\QiNiu;
use think\Cache;

class Album extends BaseService
{
	
	public $album_class;
	
	public $album_picture;
	
	function __construct()
	{
		parent::__construct();
		$this->album_class = new AlbumClassModel();
		$this->album_picture = new AlbumPictureModel();
	}
	
	/**
	 * 获取相册列表
	 * @param number $page_index
	 * @param number $page_size
	 * @param string $condition
	 * @param string $order
	 * @param string $field
	 */
	public function getAlbumClassList($page_index = 1, $page_size = 0, $condition = '', $order = '', $field = '*')
	{
		$cache = Cache::tag("album")->get("getAlbumClassList" . json_encode([ $page_index, $page_size, $condition, $order, $field ]));
		if (!empty($cache)) {
			return $cache;
		}
		$album_class = new AlbumClassModel();
		$list = $album_class->pageQuery($page_index, $page_size, $condition, $order, $field);
		if (!empty($list['data'])) {
			foreach ($list['data'] as $k => $v) {
				// 查询相册图片数量
				$count = $this->getAlbumPictureCount($v['album_id']);
				$list['data'][ $k ]['pic_count'] = $count;
				// 查询相册背景图片
				$album_picture = new AlbumPictureModel();
				$pic_cover = "";
				if ($v["album_cover"]) {
					$pic_info = $album_picture->getInfo([
						'album_id' => $v['album_id'],
						"pic_id" => $v["album_cover"]
					], 'pic_cover,upload_type,domain');
					if (!empty($pic_info)) {
						$pic_cover = $pic_info["pic_cover"];
					}
					$list['data'][ $k ]['pic_info'] = $pic_info;
					$list['data'][ $k ]["pic_album_cover"] = $pic_cover;
				}
			}
		}
		Cache::tag("album")->set("getAlbumClassList" . json_encode([ $page_index, $page_size, $condition, $order, $field ]), $list);
		return $list;
	}
	
	/**
	 * 查询相册图片数
	 * @param unknown $album_id
	 */
	private function getAlbumPictureCount($album_id)
	{
		$album_picture = new AlbumPictureModel();
		$count = $album_picture->where('album_id=' . $album_id)->count();
		return $count;
	}
	
	/**
	 * 创建相册
	 * @param unknown $aclass_name
	 * @param unknown $aclass_sort
	 * @param number $pid
	 * @param string $aclass_cover
	 * @param number $is_default
	 * @param number $instance_id
	 */
	public function addAlbumClass($aclass_name, $aclass_sort, $pid = 0, $aclass_cover = '', $is_default = 0, $instance_id = 1)
	{
		Cache::clear('album');
		$album_class = new AlbumClassModel();
		$data = array(
			'album_name' => $aclass_name,
			'sort' => $aclass_sort,
			'album_cover' => $aclass_cover,
			'is_default' => $is_default,
			'shop_id' => $instance_id,
			'create_time' => time(),
			'pid' => $pid
		);
		$retval = $album_class->save($data);
		if ($retval == 1) {
			$data['album_id'] = $album_class->album_id;
			hook("albumSaveSuccess", $data);
			return $album_class->album_id;
		} else {
			return $retval;
		}
	}
	
	/**
	 * 编辑相册
	 * @param unknown $aclass_name
	 * @param unknown $aclass_sort
	 * @param number $pid
	 * @param string $aclass_cover
	 * @param number $is_default
	 */
	public function updateAlbumClass($aclass_id, $aclass_name, $aclass_sort, $pid = 0, $aclass_cover = '', $is_default = 0)
	{
		Cache::clear('album');
		$album_class = new AlbumClassModel();
		$data = array(
			'album_name' => $aclass_name,
			'sort' => $aclass_sort,
			"album_cover" => $aclass_cover
		);
		$retval = $album_class->save($data, [
			'album_id' => $aclass_id
		]);
		$data['album_id'] = $aclass_id;
		hook("albumSaveSuccess", $data);
		return $retval;
		// TODO Auto-generated method stub
	}
	
	/**
	 * 改变相册排序
	 * @param unknown $aclass_id
	 * @param unknown $aclass_sort
	 */
	public function ModifyAlbumSort($aclass_id, $aclass_sort)
	{
		Cache::clear('album');
		$album_class = new AlbumClassModel();
		$data = array();
		
		$retval = $album_class->save($data, [
			'aclass_id' => $aclass_id
		]);
		return $retval;
		// TODO Auto-generated method stub
	}
	
	/**
	 * 改变相册上级
	 * @param unknown $aclass_id
	 * @param unknown $pid
	 */
	public function ModifyAlbumPid($aclass_id, $pid)
	{
		Cache::clear('album');
		$data = array();
		
		$res = $this->album_class->save($data, [
			'aclass_id' => $aclass_id
		]);
		return $res;
	}
	
	/**
	 * 删除相册
	 * @param unknown $aclass_id
	 */
	public function deleteAlbumClass($aclass_id_array)
	{
		Cache::clear('album');
		$this->album_class->startTrans();
		try {
			$shop_id = $this->instance_id;
			$condition = array(
				'shop_id' => $shop_id,
				'album_id' => array(
					'in',
					$aclass_id_array
				)
			);
			$album_info = $this->album_class->getInfo([
				"is_default" => 1
			], "album_id");
			$album_id = $album_info["album_id"];
			// 删除所选相册
			$res = $this->album_class->destroy($condition);
			// 将被删除相册下的图片转移到默认
			$this->album_picture->save([
				"album_id" => $album_id
			], $condition);
			$this->album_class->commit();
			if ($res == 1) {
				hook("albumDeleteSuccess", $aclass_id_array);
				return SUCCESS;
			} else {
				return DELETE_FAIL;
			}
		} catch (\Exception $e) {
			$this->album_class->rollback();
			return $e->getMessage();
		}
		
		return 0;
	}
	
	/**
	 * 获取相册图片列表
	 * @param number $page_index
	 * @param number $page_size
	 * @param string $condition
	 * @param string $order
	 * @param string $field
	 */
	public function getPictureList($page_index = 1, $page_size = 0, $condition = '', $order = " upload_time desc", $field = '*')
	{
		$cache = Cache::tag("album")->get("getPictureList" . json_encode([ $page_index, $page_size, $condition, $order, $field ]));
		if (!empty($cache)) {
			return $cache;
		}
		$list = $this->album_picture->pageQuery($page_index, $page_size, $condition, $order, $field);
		Cache::tag("album")->set("getPictureList" . json_encode([ $page_index, $page_size, $condition, $order, $field ]), $list);
		return $list;
	}
	
	/**
	 * 图片增加
	 * @param unknown $pic_name
	 *            名称
	 * @param unknown $pic_tag
	 *            标签
	 * @param unknown $aclass_id
	 *            相册ID
	 * @param unknown $pic_cover
	 *            图片路径
	 * @param unknown $pic_size
	 *            大小
	 * @param unknown $pic_spec
	 *            规格
	 * @param unknown $pic_cover_big
	 * @param unknown $pic_size_big
	 * @param unknown $pic_spec_big
	 * @param unknown $pic_cover_mid
	 * @param unknown $pic_size_mid
	 * @param unknown $pic_spec_mid
	 * @param unknown $pic_cover_small
	 * @param unknown $pic_size_small
	 * @param unknown $pic_spec_small
	 * @param unknown $pic_cover_micro
	 * @param unknown $pic_size_micro
	 * @param unknown $pic_spec_micro
	 * @param unknown $instance_id
	 */
	public function addPicture($pic_name, $pic_tag, $aclass_id, $pic_cover, $pic_size, $pic_spec, $pic_cover_big, $pic_size_big, $pic_spec_big, $pic_cover_mid, $pic_size_mid, $pic_spec_mid, $pic_cover_small, $pic_size_small, $pic_spec_small, $pic_cover_micro, $pic_size_micro, $pic_spec_micro, $instance_id = 0, $upload_type, $domain, $bucket)
	{
		Cache::clear('album');
		// TODO Auto-generated method stub
		$data = array(
			'shop_id' => $instance_id,
			'album_id' => $aclass_id,
			'is_wide' => "0",
			'pic_name' => $pic_name,
			'pic_tag' => $pic_tag,
			'pic_cover' => $pic_cover,
			'pic_size' => $pic_size,
			'pic_spec' => $pic_spec,
			'pic_cover_big' => $pic_cover_big,
			'pic_size_big' => $pic_size_big,
			'pic_spec_big' => $pic_spec_big,
			'pic_cover_mid' => $pic_cover_mid,
			'pic_size_mid' => $pic_size_mid,
			'pic_spec_mid' => $pic_spec_mid,
			'pic_cover_small' => $pic_cover_small,
			'pic_size_small' => $pic_size_small,
			'pic_spec_small' => $pic_spec_small,
			'pic_cover_micro' => $pic_cover_micro,
			'pic_size_micro' => $pic_size_micro,
			'pic_spec_micro' => $pic_spec_micro,
			'upload_time' => time(),
			"upload_type" => $upload_type,
			"domain" => $domain,
			"bucket" => $bucket
		);
		$res = $this->album_picture->save($data);
		if ($res == 1) {
			return $this->album_picture->pic_id;
		} else {
			return $res;
		}
	}
	
	/**
	 * 图片删除
	 * @param unknown $pic_id
	 */
	public function deletePicture($pic_id_array)
	{
		Cache::clear('album');
		// TODO Auto-generated method stub
		$shop_id = $this->instance_id;
		$pic_array = explode(',', $pic_id_array);
		$res = 1;
		if (!empty($pic_array)) {
			$user_img_array = $this->getGoodsAlbumUsePictureQuery([
				"shop_id" => $shop_id
			]);
			
			// 判断当前图片是否在商品中使用过
			foreach ($pic_array as $pic_id) {
				$retval = in_array($pic_id, $user_img_array);
				if (!$retval) {
					$condition = array(
						'shop_id' => $shop_id,
						'pic_id' => $pic_id
					);
					// 得到当前图片的信息
					$picture_obj = $this->album_picture->get($pic_id);
					if (!empty($picture_obj)) {
						$pic_cover = $picture_obj["pic_cover"];
						removeImageFile($pic_cover);
						$pic_cover_big = $picture_obj["pic_cover_big"];
						removeImageFile($pic_cover_big);
						$pic_cover_mid = $picture_obj["pic_cover_mid"];
						removeImageFile($pic_cover_mid);
						$pic_cover_small = $picture_obj["pic_cover_small"];
						removeImageFile($pic_cover_small);
						$pic_cover_micro = $picture_obj["pic_cover_micro"];
						removeImageFile($pic_cover_micro);
						//判断上传类型是七牛
						if ($picture_obj["upload_type"] == 2) {
							$qiniu = new QiNiu();
							$qiniu->deleteQiNiuImage($pic_cover, $picture_obj["domain"]);
							$qiniu->deleteQiNiuImage($pic_cover_big, $picture_obj["domain"]);
							$qiniu->deleteQiNiuImage($pic_cover_mid, $picture_obj["domain"]);
							$qiniu->deleteQiNiuImage($pic_cover_small, $picture_obj["domain"]);
							$qiniu->deleteQiNiuImage($pic_cover_micro, $picture_obj["domain"]);
						}
					}
					$result = $this->album_picture->destroy($condition);
					if (!$result > 0) {
						$res = -1;
					}
				} else {
					$res = -1;
				}
			}
		} else {
			$res = -1;
		}
		if ($res == 1) {
			return SUCCESS;
		} else {
			return DELETE_FAIL;
		}
	}
	
	/**
	 * 获取相册详情
	 * @param unknown $album_id
	 */
	public function getAlbumClassDetail($album_id)
	{
		$cache = Cache::tag("album")->get("getAlbumClassDetail" . $album_id);
		if (!empty($cache)) {
			return $cache;
		}
		// TODO Auto-generated method stub
		$res = $this->album_class->get($album_id);
		Cache::tag("album")->set("getAlbumClassDetail" . $album_id, $res);
		return $res;
		// TODO Auto-generated method stub
	}
	
	/**
	 * 获取图片详情
	 * @param unknown $pic_id
	 */
	public function getAlbumDetail($pic_id)
	{
		$cache = Cache::tag("album")->get("getAlbumDetail" . $pic_id);
		if (!empty($cache)) {
			return $cache;
		}
		$res = $this->album_picture->get($pic_id);
		Cache::tag("album")->set("getAlbumDetail" . $pic_id, $res);
		return $res;
		// TODO Auto-generated method stub
	}
	
	/**
	 * 获取图片基础信息
	 * @param unknown $pic_id
	 */
	public function getAlbumDetailInfo($pic_id)
	{
		$cache = Cache::tag("album")->get("getAlbumDetailInfo" . $pic_id);
		if (!empty($cache)) {
			return $cache;
		}
		// TODO Auto-generated method stub
		$res = $this->album_picture->getInfo([ 'pic_id' => $pic_id ]);
		Cache::tag("album")->set("getAlbumDetailInfo" . $pic_id, $res);
		return $res;
	}
	
	/**
	 * 获取图片的商品使用信息
	 * @param string $condition
	 */
	public function getAlbumListUseInGoods($condition = '')
	{
		$res = $this->album_class->getQuery($condition, "*", "sort");
		return $res;
	}
	
	/**
	 * 图片替换
	 * @param unknown $pic_id
	 * @param unknown $pic_name
	 * @param unknown $pic_tag
	 * @param unknown $aclass_id
	 * @param unknown $pic_cover
	 * @param unknown $pic_size
	 * @param unknown $pic_spec
	 * @param unknown $pic_cover_big
	 * @param unknown $pic_size_big
	 * @param unknown $pic_spec_big
	 * @param unknown $pic_cover_mid
	 * @param unknown $pic_size_mid
	 * @param unknown $pic_spec_mid
	 * @param unknown $pic_cover_small
	 * @param unknown $pic_size_small
	 * @param unknown $pic_spec_small
	 * @param unknown $pic_cover_micro
	 * @param unknown $pic_size_micro
	 * @param unknown $pic_spec_micro
	 * @param number $instance_id
	 */
	public function ModifyAlbumPicture($pic_id, $pic_cover, $pic_size, $pic_spec, $pic_cover_big, $pic_size_big, $pic_spec_big, $pic_cover_mid, $pic_size_mid, $pic_spec_mid, $pic_cover_small, $pic_size_small, $pic_spec_small, $pic_cover_micro, $pic_size_micro, $pic_spec_micro, $instance_id, $upload_type, $domain, $bucket)
	{
		Cache::clear('album');
		$data = array(
			'pic_cover' => $pic_cover,
			'pic_size' => $pic_size,
			'pic_spec' => $pic_spec,
			'pic_cover_big' => $pic_cover_big,
			'pic_size_big' => $pic_size_big,
			'pic_spec_big' => $pic_spec_big,
			'pic_cover_mid' => $pic_cover_mid,
			'pic_size_mid' => $pic_size_mid,
			'pic_spec_mid' => $pic_spec_mid,
			'pic_cover_small' => $pic_cover_small,
			'pic_size_small' => $pic_size_small,
			'pic_spec_small' => $pic_spec_small,
			'pic_cover_micro' => $pic_cover_micro,
			'pic_size_micro' => $pic_size_micro,
			'pic_spec_micro' => $pic_spec_micro,
			'upload_time' => time(),
			'upload_type' => $upload_type,
			"domain" => $domain,
			"bucket" => $bucket
		);
		$res = $this->album_picture->save($data, [
			"pic_id" => $pic_id
		]);
		return $res;
	}
	
	/**
	 * 图片名称修改
	 * @param unknown $pic_id
	 * @param unknown $pic_name
	 */
	public function ModifyAlbumPictureName($pic_id, $pic_name)
	{
		Cache::clear('album');
		$data = array(
			'pic_name' => $pic_name
		);
		$res = $this->album_picture->save($data, [
			"pic_id" => $pic_id
		]);
		if ($res == 1) {
			return SUCCESS;
		} else {
			return UPDATA_FAIL;
		}
	}
	
	/**
	 * 更改图片所在相册
	 * @param unknown $pic_id
	 * @param unknown $album_id
	 */
	public function ModifyAlbumPictureClass($pic_id, $album_id)
	{
		Cache::clear('album');
		$data = array(
			'album_id' => $album_id
		);
		$condition["pic_id"] = [
			"in",
			$pic_id
		];
		$res = $this->album_picture->save($data, $condition);
		if ($res > 0) {
			return SUCCESS;
		} else {
			return UPDATA_FAIL;
		}
	}
	
	/**
	 * 设此图片为本相册的封面
	 * @param unknown $pic_id
	 * @param unknown $album_id
	 */
	public function ModifyAlbumClassCover($pic_id, $album_id)
	{
		Cache::clear('album');
		$data = array(
			'album_cover' => $pic_id
		);
		$res = $this->album_class->save($data, [
			"album_id" => $album_id
		]);
		if ($res == 1) {
			return SUCCESS;
		} else {
			return UPDATA_FAIL;
		}
	}
	
	/**
	 * 判断图片是否已经被使用
	 * return true = 已被使用 false = 未使用
	 */
	public function checkPictureIsUse($shop_id, $pic_id)
	{
		// 1.判断商品图片是否已经使用
		$goods = new NsGoodsModel();
		$res = $goods->where(" FIND_IN_SET('" . $pic_id . "', img_id_array) and shop_id = " . $shop_id)->count();
		if ($res > 0) {
			return true;
		} else {
			return false;
		}
		// 2.判断商品sku图片是否已经使用
	}
	
	/**
	 * 获取相册图片详情
	 * @param array $condition
	 */
	public function getAlubmPictureDetail($condition)
	{
		$cache = Cache::tag("album")->get("getAlubmPictureDetail" . json_encode($condition));
		if (!empty($cache)) {
			return $cache;
		}
		$res = $this->album_picture->getInfo($condition, "*");
		Cache::tag("album")->set("getAlubmPictureDetail" . json_encode($condition), $res);
		return $res;
	}
	
	/**
	 * 获取商品应用图像列表
	 * @param array $condition
	 */
	public function getGoodsAlbumUsePictureQuery($condition)
	{
		// TODO Auto-generated method stub
		$goods = new NsGoodsModel();
		$goods_query = $goods->getQuery($condition, "img_id_array", "");
		$goods_deleted = new NsGoodsDeletedModel();
		$goods_deleted_query = $goods_deleted->getQuery($condition, "img_id_array", "");
		$img_array = array();
		foreach ($goods_query as $k => $v) {
			if (trim($v["img_id_array"]) != "") {
				$tmp_array = explode(",", trim($v["img_id_array"]));
				$img_array = array_merge($img_array, $tmp_array);
			}
		}
		foreach ($goods_deleted_query as $k => $v) {
			if (trim($v["img_id_array"]) != "") {
				$tmp_array = explode(",", trim($v["img_id_array"]));
				$img_array = array_merge($img_array, $tmp_array);
			}
		}
		$img_array = array_unique($img_array);
		return $img_array;
	}
	
	/**
	 * 获取默认相册图片信息
	 */
	public function getDefaultAlbumDetail()
	{
		$cache = Cache::tag("album")->get("getDefaultAlbumDetail");
		if (!empty($cache)) {
			return $cache;
		}
		$album_info = $this->album_class->getInfo([
			"shop_id" => $this->instance_id,
			"is_default" => 1
		]);
		
		//如果默认相册为空建立个相册
		if (empty($album_info)) {
			
			$data = array(
				'shop_id' => $this->instance_id,
				'pid' => 0,
				'album_name' => '默认相册',
				'is_default' => 1,
				'sort' => 0
			);
			
			$this->album_class->save($data);
			
			$album_info = $this->album_class->getInfo([
				"shop_id" => $this->instance_id,
				"is_default" => 1
			]);
		}
		
		Cache::tag("album")->set("getDefaultAlbumDetail", $album_info);
		return $album_info;
	}
}