<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class banners{
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'banners';


	public static function getList() {
		$list = [];
		$where = [];

		if (!empty($_GET['q'])) {
			$where[] = "link LIKE '%".utils::makeSearchable($_GET['q'])."%'";
		}

		$c = CMS::$db->select('COUNT(id)', self::$tbl, $where);
		self::$items_amount = $c;
		
		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->selectAll('*', self::$tbl, $where, 'ordering', $start_from, self::$pp);
		}

		return $list;
	}
	
	public static function add() {
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

		$data['link'] = utils::safeEcho( $_POST['link'], true);
		$data['title'] = utils::safeEcho( $_POST['title'], true);
		$data['img'] = '';

		if(isset($_FILES['img']) && $_FILES['img']['error'] == 0 && $_FILES['img']['size']  > 0){
			$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
			$data['img'] = utils::upload(time().'.'.$ext, $_FILES['img']['tmp_name'], '../upload/banners/', ['jpg', 'jpeg', 'png']);
		}
		
		if(empty($data['img']))		$response['errors'][] = 'banners_add_err_img_empty';
		

		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'Banner '.$data_id.' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}
		

		return $response;
	}

	
	public static function edit($id) {
		$response = ['success' => false, 'message' => 'update_err', 'errors' => []];

		$data['link'] = utils::safeEcho($_POST['link'], true);
		$data['title'] = utils::safeEcho($_POST['title'], true);
		$data['img'] = utils::safeEcho($_POST['img'], true);


		if(isset($_FILES['img']) && $_FILES['img']['error'] == 0 && $_FILES['img']['size']  > 0){
			$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
			$data['img'] = utils::upload(time().'.'.$ext, $_FILES['img']['tmp_name'], '../upload/banners/', ['jpg', 'jpeg', 'png']);
		}

		if(empty($response['errors'])){

			$updated = CMS::$db->mod(self::$tbl.'#'.$id, $data);

			$response['success'] = true;
			$response['message'] = 'update_suc';

			if ($updated) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $id,
					'action' => 'edit',
					'descr' => 'Banner'.$id.' edited by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);
			}
		}

		return $response;
	}


	public static function delete($id){
		
		$img = '../upload/banners/'.CMS::$db->get("SELECT img FROM `".self::$tbl."` WHERE id = :id LIMIT 1", [':id'=>$id]);

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {
			
			if(file_exists($img)) unlink($img);
			
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Banner deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}

	public static function get($id){
		return CMS::$db->getRow('SELECT * FROM `'.self::$tbl.'` WHERE `id`=:id', [':id'=>$id]);
	}
	
	
	public static function ajax_sort($items, $cal_start){

		$cal_start = abs((int)$cal_start);

		foreach($items as $k=>$item){
			$item = abs((int)$item);

			$sort = CMS::$db->exec('UPDATE `'.self::$tbl.'` SET ordering = :ordering WHERE id = :id', [':ordering'=>($cal_start + $k + 1), ':id' => $item]);
		
			if($sort){
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $item,
					'action' => 'ajax_sort',
					'descr' => 'Banner sorted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);
			}
		
		}
	}
	
	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}

}

?>