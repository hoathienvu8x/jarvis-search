<?php
@header('Content-Type:text/xml;charset=utf-8');
require_once dirname(__FILE__) . '/init.php';
?>
<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel>
	<responseout>
	<![CDATA[ <?php echo isset($_GET['question']) ? $_GET['question'] : '';?> ]]>
	</responseout>
</channel>