<?php
/*
 * LMT/Backstage/Pages/Add_Separator.php
 * LHS Math Club Website
 *
 * xsrf_token
 *
 * Adds a separator to the end of the page list
 */

$path_to_lmt_root = '../../';
require_once $path_to_lmt_root . '../lib/lmt-functions.php';
restrict_access('A');

do_add_separator();





function do_add_separator() {
	if ($_GET['xsrf_token'] != $_SESSION['xsrf_token'])
		trigger_error('XSRF code incorrect', E_USER_ERROR);
	
	$row = lmt_query('SELECT MAX(order_num + 1) AS new_order FROM pages', true);
	$new_order = $row['new_order'];
	
	lmt_query('INSERT INTO pages (name, content, order_num) VALUES ("", "", "'
		. mysql_real_escape_string($new_order) . '")');
	
	header('Location: List');
}

?>