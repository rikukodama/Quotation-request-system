<?php
/**
 * @copyright ICZ Corporation (http://www.icz.co.jp/)
 * @license See the LICENCE file
 * @author <matcha@icz.co.jp>
 * @version $Id$
 */
class InstallAppController extends AppController {

	function beforeFilter() {
		//説明文の文言
		$this->set('usernavi', Configure::read('UserNavigate'));
		if($this->action === 'add'){
			if($this->Install->findUser() && file_exists(APP.'config'.DS.'finish')){
				$this->redirect('/');
			}
		}else{
			//インストールファイルがある場合
			if(file_exists(APP.'config'.DS.'finish')){
				$this->redirect('/');
			}
		}
	}
}