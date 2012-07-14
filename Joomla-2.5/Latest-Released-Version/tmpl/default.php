<?php
/**
 *  @Copyright
 *
 *  @package     Random Image Extended - RIE for Joomla 2.5
 *  @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version     Version: 2.5-2 - 07-Jun-2012
 *  @link        Project Site {@link http://joomla-extensions.kubik-rubik.de/rie-random-image-extended}
 *
 *  @license GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');
echo '<!-- RIE - Random Image Extended for Joomla 2.5 by Kubik-Rubik.de - Viktor Vogel -->';
?>
<div class="random_image_extended<?php echo $moduleclass_sfx ?>">
<?php if($lightbox AND empty($link)) : ?>
    <?php if ($lb_yes == "slimbox") : ?>
        <a href="<?php echo $image->folder.'/'.$image->name ?>" title="<?php echo substr($image->name, 0, -4); ?>" rel="lightbox.random">
    <?php elseif ($lb_yes == "milkbox") : ?>
        <a href="<?php echo $image->folder.'/'.$image->name ?>" title="<?php echo substr($image->name, 0, -4); ?>" data-milkbox="milkbox">
    <?php elseif ($lb_yes == "shadowbox") : ?>
        <a href="<?php echo $image->folder.'/'.$image->name ?>" title="<?php echo substr($image->name, 0, -4); ?>" rel="shadowbox[random]">
    <?php endif; ?>
<?php endif; ?>
<?php if($link) : ?>
    <a href="<?php echo $link; ?>" title="<?php echo $image->name; ?>"
    <?php if ($newwindow) : ?> target="_blank"<?php endif; ?>>
<?php endif; ?>
<?php if(empty($linkfolder)) : ?>
    <?php echo JHTML::_('image', $image->folder.'/'.$image->name, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
<?php else : ?>
    <?php echo JHTML::_('image', $image->folder.'/thumbs/'.$image->name, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
<?php endif; ?>
<?php if($link OR $lightbox) : ?>
    </a>
<?php endif; ?>
<?php if(!empty($caption)) : ?>
    <div class="random_image_extended<?php echo $moduleclass_sfx ?>"><em><?php echo $caption; ?></em></div>
<?php endif; ?>
<?php if($bname) : ?>
    <div class="random_image_extended<?php echo $moduleclass_sfx ?>"><strong><?php echo substr($image->name, 0, -4); ?></strong></div>
<?php endif; ?>
<?php if($allpics AND $lightbox AND empty($link)) : ?>
    <?php foreach($images as $image) : ?>
        <?php if($lb_yes == "slimbox") : ?>
            <a rel="lightbox.random" href="<?php echo $image->folder.'/'.$image->name; ?>" title="<?php echo substr($image->name, 0, -4); ?>"></a>
        <?php elseif($lb_yes == "milkbox") : ?>
            <a data-milkbox="milkbox" href="<?php echo $image->folder.'/'.$image->name; ?>" title="<?php echo substr($image->name, 0, -4); ?>"></a>
        <?php elseif($lb_yes == "shadowbox") : ?>
            <a rel="shadowbox[random]" href="<?php echo $image->folder.'/'.$image->name; ?>" title="<?php echo substr($image->name, 0, -4); ?>"></a>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if($copy) : ?>
    <div class="random_image_extended_small"><a href="http://joomla-extensions.kubik-rubik.de" title="Kubik-Rubik Joomla! Extensions" target="_blank">RIE - Random Image Extended</a></div>
<?php endif; ?>
</div>