<?php
/**
 *  @Copyright
 *
 *  @package     Random Image Extended - RIE
 *  @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version     2.5-4 - 2013-08-04
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
echo '<!-- RIE - Random Image Extended - Kubik-Rubik Joomla! Extensions -->';
?>
<div class="random_image_extended<?php echo $moduleclass_sfx ?>">
    <p>
        <?php echo JText::_('MOD_RANDOM_IMAGE_EXTENDED_NOIMAGES'); ?>
    </p>
<?php if($copy) : ?>
    <div class="random_image_extended_small"><a href="http://joomla-extensions.kubik-rubik.de" title="Kubik-Rubik Joomla! Extensions" target="_blank">RIE - Random Image Extended</a></div>
<?php endif; ?>
</div>