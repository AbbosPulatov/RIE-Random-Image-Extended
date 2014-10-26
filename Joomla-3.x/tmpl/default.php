<?php
/**
 * @Copyright
 *
 * @package     RIE - Random Image Extended for Joomla! 3
 * @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 * @version     3-3 - 2014-06-04
 * @link        Project page {@link http://joomla-extensions.kubik-rubik.de/rie-random-image-extended}
 *
 * @license     GNU/GPL
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
echo '<!-- RIE - Random Image Extended - Kubik-Rubik Joomla! Extensions - Viktor Vogel -->';
?>
<div class="random_image_extended <?php echo $moduleclass_sfx ?>">
    <?php if ($image_rotator) : ?>
        <div id="slideshow-container">
            <?php if(empty($linkfolder)) : ?>
                <?php echo JHTML::_('image', $image->folder.'/'.$image->link, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
            <?php else : ?>
                <?php echo JHTML::_('image', $image->folder.'/thumbs/'.$image->link, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
            <?php endif; ?>
            <?php foreach($images as $image) : ?>
                <?php if(empty($linkfolder)) : ?>
                    <?php echo JHTML::_('image', $image->folder.'/'.$image->link, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
                <?php else : ?>
                    <?php echo JHTML::_('image', $image->folder.'/thumbs/'.$image->link, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <?php if ($lightbox AND (empty($link) AND empty($image->linkto))) : ?>
            <a href="<?php echo $image->folder.'/'.$image->link ?>" title="<?php echo $image->name; ?>" <?php echo $lightbox_attribute; ?>>
        <?php endif; ?>
        <?php if (!empty($link) OR !empty($image->linkto)) : ?>
            <?php if (!empty($image->linkto)) : ?>
                <a href="<?php echo $image->linkto; ?>" title="<?php echo $image->name; ?>"
            <?php elseif (!empty($link)) : ?>
                <a href="<?php echo $link; ?>" title="<?php echo $link; ?>"
            <?php endif; ?>
            <?php if($newwindow) : ?>
                target="_blank"
            <?php endif; ?>
            >
        <?php endif; ?>
        <?php if(empty($linkfolder)) : ?>
            <?php echo JHTML::_('image', $image->folder.'/'.$image->link, $image->name, array('width' => $image->width)); ?>
        <?php else : ?>
            <?php echo JHTML::_('image', $image->folder.'/thumbs/'.$image->link, $image->name, array('width' => $image->width)); ?>
        <?php endif; ?>
        <?php if ($lightbox OR !empty($link) OR !empty($image->linkto)) : ?>
            </a>
        <?php endif; ?>
        <?php if(!empty($caption)) : ?>
            <div class="random_image_extended <?php echo $moduleclass_sfx ?>"><em><?php echo $caption; ?></em>
            </div>
        <?php endif; ?>
        <?php if($bname) : ?>
            <div class="random_image_extended <?php echo $moduleclass_sfx ?>">
                <strong><?php echo $image->name; ?></strong></div>
        <?php endif; ?>
        <?php if($allpics AND $lightbox AND empty($link)) : ?>
            <?php foreach($images as $image) : ?>
                <a <?php echo $lightbox_attribute; ?> href="<?php echo $image->folder.'/'.$image->link; ?>"
                   title="<?php echo $image->name; ?>"></a>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if($copy) : ?>
        <div class="random_image_extended_small">
            <a href="http://joomla-extensions.kubik-rubik.de" title="Kubik-Rubik Joomla! Extensions by Viktor Vogel" target="_blank">
                Kubik-Rubik Joomla! Extensions
            </a>
        </div>
    <?php endif; ?>
</div>