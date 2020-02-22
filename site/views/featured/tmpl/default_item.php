<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bfdownloadmanager
 *
 * @copyright   Copyright (C) 2018 Jonathan Brain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
$info = $this->item->params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));
?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
<div class="system-unpublished">
	<?php endif; ?>

	<?php if ($params->get('show_title')) : ?>
        <h2 class="item-title" itemprop="headline">
			<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                <a href="<?php echo JRoute::_(BfdownloadmanagerHelperRoute::getDownloadRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>"
                   itemprop="url">
					<?php echo $this->escape($this->item->title); ?>
                </a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
        </h2>
	<?php endif; ?>

	<?php if ($this->item->state == 0) : ?>
        <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
	<?php endif; ?>
	<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
        <span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
	<?php endif; ?>
	<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate()) : ?>
        <span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
	<?php endif; ?>

	<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
	<?php endif; ?>

	<?php // Bfdownloadmanager is generated by bfdownloadmanager plugin event "onBfdownloadmanagerAfterTitle" ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
		|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam); ?>

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
		<?php if ($info == 0 && $params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
	<?php endif; ?>

	<?php // Bfdownloadmanager is generated by bfdownloadmanager plugin event "onBfdownloadmanagerBeforeDisplay" ?>
	<?php echo $this->item->event->beforeDisplayBfdownloadmanager; ?>

	<?php echo $this->item->introtext; ?>

	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<?php // Todo: for Joomla4 joomla.content.info_block.block can be changed to joomla.content.info_block ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(BfdownloadmanagerHelperRoute::getDownloadRoute($this->item->slug, $this->item->catid, $this->item->language));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
			$link->setVar('return', base64_encode(BfdownloadmanagerHelperRoute::getDownloadRoute($this->item->slug, $this->item->catid, $this->item->language)));
		endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

	<?php endif; ?>

	<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != $this->db->getNullDate())) : ?>
</div>
<?php endif; ?>

<?php // Bfdownloadmanager is generated by bfdownloadmanager plugin event "onBfdownloadmanagerAfterDisplay" ?>
<?php echo $this->item->event->afterDisplayBfdownloadmanager; ?>
