<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class InstallAppModel extends AppModel{

	/**
	 * インストーラー実行時にT_Userが登録されているか確認する
	 * @return boolean
	 */
	function findUser(){
		App::import('Model','User');
		$user = new User();
		if(count($user->find()) > 0){
			return true;
		}
		return false;
	}
}