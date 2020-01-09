<?php

function weberino_is_new_post() {
	if($_GET) {
		if(array_key_exists('action', $_GET)){
			if ($_GET['action'] == 'edit') {
				return false;
			};
		}
	}
	return true;
}