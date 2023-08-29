<?php
/**
* @version 1.0 smartSHOP BS4
* No longer used, therefore unchanged - disabled by checking for $old
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php if ($old) : ?>

<html>
	<head>
		<title><?php print $this->description; ?></title>
        <?php print $this->scripts_load?>
	</head>
	<body style = "padding: 0px; margin: 0px;">
		<a class = "video_full" id = "video" href = "<?php print $this->config->demo_product_live_path.'/'.$this->filename; ?>"></a>
		<script type="text/javascript">
            var liveurl = '<?php print JURI::root()?>'; var media = document.querySelector('#video');
			if(media){ media.matchMedia("(width: "+<?php print $this->config->video_product_width; ?>+")"); media.matchMedia("(height: "+ <?php print $this->config->video_product_height; ?>+")"); media.play();}
		</script>
	</body>
</html>



<?php if (count ($this->demofiles)){?>
    <div class="list_product_demo">
        <table>
            <?php foreach($this->demofiles as $demo){?>
                <tr>
                    <td class="descr"><?php print $demo->demo_descr?></td>
                    <td class="download"><a target="_blank" href="<?php print getPatchProductImage($demo->demo, '', 1);?>"><?php print _JSHOP_DOWNLOAD;?></a></td>
                </tr>
            <?php }?>
        </table>
    </div>
<?php } ?>

<?php endif; ?>
