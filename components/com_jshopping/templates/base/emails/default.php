<?php 
defined('_JEXEC') or die('Restricted access');

$emailBody = $this->emailBod;
$emailSubject = $this->emailSubject;
$order = $this->order;
?>

<html>
	<title></title>
	<head>
		<style type="text/css">
			html {
				font-family: Tahoma;
				line-height: 100%;
			}

			body, td {
				font-size: 12px;
				font-family: Tahoma;
			}

			td.bg_gray, tr.bg_gray td {
				background-color: #CCCCCC;
			}

			table {
				border-collapse: collapse;
				border: 0;
			}

			td {
				padding-left: 3px;
				padding-right: 3px;
				padding-top: 0px;
				padding-bottom: 0px;
			}

			tr.bold td {
				font-weight: bold;
			}

			tr.vertical td {
				vertical-align: top;
				padding-bottom: 10px;
			}

			h3 {
				font-size: 14px;
				margin: 2px;
			}

			.jshop_cart_attribute {
				padding-top: 5px;
				font-size: 11px;
			}

			.taxinfo {
				font-size: 11px;
			}
		</style>
	</head>
	
	<body>
		<?php print $emailBody; ?>
	</body>
</html>