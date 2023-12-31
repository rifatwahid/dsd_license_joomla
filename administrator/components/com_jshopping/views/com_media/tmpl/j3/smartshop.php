<?php 

defined('_JEXEC') or die;

$lang = JFactory::getLanguage();
$this->_path['template'][] = __DIR__;

if ($_REQUEST['author'] == 'smartshopimgsvideo') {
	$this->images = array_merge($this->get('images'), $this->get('videos'));
} else {
	$this->images = array_merge($this->get('images'), $this->get('documents'), $this->get('videos'));
}

JHtml::_('stylesheet', 'media/popup-imagelist.css', array('version' => 'auto', 'relative' => true));

if ($lang->isRtl())
{
	JHtml::_('stylesheet', 'media/popup-imagelist_rtl.css', array('version' => 'auto', 'relative' => true));
}

JFactory::getDocument()->addScriptDeclaration('var ImageManager = window.parent.ImageManager;');

if ($lang->isRtl())
{
	JFactory::getDocument()->addStyleDeclaration(
		'
			@media (max-width: 767px) {
				li.imgOutline.thumbnail.height-80.width-80.center {
					float: right;
				}
			}
		'
	);
}
else
{
	JFactory::getDocument()->addStyleDeclaration(
		'
			@media (max-width: 767px) {
				li.imgOutline.thumbnail.height-80.width-80.center {
					float: left;
				}
			}
		'
	);
}
?>
<?php if (count($this->images) > 0 || count($this->folders) > 0) : ?>
	<ul class="manager thumbnails thumbnails-media">
		<?php for ($i = 0, $n = count($this->folders); $i < $n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i = 0, $n = count($this->images); $i < $n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('files');
		endfor; ?>
	</ul>
<?php else : ?>
	<div id="media-noimages">
		<div class="alert alert-info"><?php echo JText::_('COM_MEDIA_NO_IMAGES_FOUND'); ?></div>
	</div>
<?php endif; ?>
