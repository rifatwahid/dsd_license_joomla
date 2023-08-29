<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

$params     = new Registry;
$dispatcher = Factory::getApplication();
$dispatcher->triggerEvent('onContentBeforeDisplay', array('com_media.file', &$this->_tmp_img, &$params, 0));
$isImage = (empty($this->_tmp_img->icon_32) && empty($this->_tmp_img->icon_32));
?>

<li class="imgOutline thumbnail height-80 width-80 center">
	<a class="img-preview" href="javascript:ImageManager.populateFields('<?php echo $this->escape($this->_tmp_img->path_relative); ?>')" title="<?php echo $this->escape($this->_tmp_img->name); ?>" >
		<div class="imgThumb">
			<div class="imgThumbInside">
				<?php 
					if ($isImage) {
						echo JHtml::_('image', $this->baseURL . '/' . $this->escape($this->_tmp_img->path_relative), JText::sprintf('COM_MEDIA_IMAGE_TITLE', $this->escape($this->_tmp_img->title), JHtml::_('number.bytes', $this->_tmp_img->size)), array('width' => $this->_tmp_img->width_60, 'height' => $this->_tmp_img->height_60));
					} else {
						echo JHtml::_('image', $this->_tmp_img->icon_32, $this->escape($this->_tmp_img->name), null, true, true) ? JHtml::_('image', $this->_tmp_img->icon_32, $this->escape($this->_tmp_img->title), null, true) : JHtml::_('image', 'media/con_info.png', $this->escape($this->_tmp_img->name), null, true);
					}
				?>
			</div>
		</div>
		<div class="imgDetails small">
			<?php echo JHtml::_('string.truncate', $this->escape($this->_tmp_img->name), 10, false); ?>
		</div>
	</a>
</li>
<?php
$dispatcher->triggerEvent('onContentAfterDisplay', array('com_media.file', &$this->_tmp_img, &$params, 0));

