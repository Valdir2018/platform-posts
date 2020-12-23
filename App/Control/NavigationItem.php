<?php

namespace App\Control;
use App\Model\Login;
use Components\Session\Session;


class NavigationItem {
	public function menu() 
	{
		$session_usr_logged = Session::getValue("id_level");
		$load_menu_item = new Login();
		$resultado = $load_menu_item->itemNavbar($session_usr_logged);
		return $resultado;
	}
}