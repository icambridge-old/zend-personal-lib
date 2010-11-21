<?php

class Zend_View_Helper_Greet extends Zend_View_Helper_Abstract {
		
	public function greet(){

		$auth = Zend_Auth::getInstance();
		
		$string = 'Hello ';
		
		if ( $auth->hasIdentity() ){
			// html in invalid place?
			$user = $auth->getIdentity();
			$string .= $this->view->escape( $user->display_name );
			$string .= ' | <a href="'.$this->view->url( array('controller' => 'user','action' => 'logout'), null, true ).'">Logout</a>';
		} else {
			$string .= 'Guest';
			$string .= ' | <a href="'.$this->view->url( array('controller' => 'user','action' => 'login'), null, true ).'">Login</a>';
		}
		
		return $string;
	}
	
}

?>