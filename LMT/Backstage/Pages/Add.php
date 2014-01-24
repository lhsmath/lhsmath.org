<?php
/*
 * LMT/Backstage/Pages/Add.php
 * LHS Math Club Website
 */

$path_to_lmt_root = '../../';
require_once $path_to_lmt_root . '../lib/lmt-functions.php';
restrict_access('A');

if (isSet($_POST['lmt_do_add_page']))
	do_add_page();
else
	show_form('');





function show_form($err) {
	global $body_onload, $use_rel_external_script;
	$body_onload = 'document.forms[\'lmtAddPage\'].name.focus();externalLinks();';
	$use_rel_external_script = true;
	
	lmt_page_header('Add Page');
	
	if ($err != '')
		$err = "\n        <div class=\"error\">$err</div><br />\n";
	
	$name = htmlentities($_POST['name']);
	$content = htmlentities($_POST['content']);
	
	echo <<<HEREDOC
      <h1>Add Page</h1>
      $err
      <form id="lmtAddPage" method="post" action="{$_SERVER['REQUEST_URI']}">
        <table class="spacious">
          <tr>
            <td>Title:</td>
            <td><input type="text" name="name" value="$name" size="25" maxlength="25" /></td>
          </tr><tr>
            <td>Content:&nbsp;</td>
            <td>
              <textarea name="content" rows="25" cols="80" class="code">$content</textarea>
              <div class="small">Please write XHTML-compliant code.<br />
              Links marked with rel=&quot;external&quot; open in a new window. Links are relative to /LMT.</div><br />
            </td>
          </tr><tr>
            <td></td>
            <td>
              <input type="hidden" name="xsrf_token" value="{$_SESSION['xsrf_token']}" />
              <input type="submit" name="lmt_do_add_page" value="Add Page" />
              &nbsp;&nbsp;<a href="List">Cancel</a><br /><br /><br />
            </td>
          </tr>
        </table>
      </form>
HEREDOC;
	lmt_backstage_footer('');
	die;
}





function do_add_page() {
	if ($_POST['xsrf_token'] != $_SESSION['xsrf_token'])
		trigger_error('XSRF code incorrect', E_USER_ERROR);
	
	$name = $_POST['name'];
	$content = $_POST['content'];
	
	if ($name == '')
		show_form('Please choose a name for the page');
	
	if (strlen($name) > 25)
		show_form('The page name may not be longer than 25 characters');
	
	if (strlen($content) > 20000)
		show_form('The content may not be longer than 20,000 characters');
	
	// ** VALIDATION COMPLETE ** \\
	
	$row = lmt_query('SELECT MAX(order_num + 1) AS new_order FROM pages', true);
	$new_order = $row['new_order'];
	
	lmt_query('INSERT INTO pages (name, content, order_num) VALUES ("'
		. mysql_real_escape_string($name)
		. '", "' . mysql_real_escape_string($content)
		. '", "' . mysql_real_escape_string($new_order) . '")');
	
	$row = lmt_query('SELECT page_id FROM pages WHERE order_num="'
		. mysql_real_escape_string($new_order) . '"', true);
	
	header('Location: View?ID=' . $row['page_id']);
}

?>