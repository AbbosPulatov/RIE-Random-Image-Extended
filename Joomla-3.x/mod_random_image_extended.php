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

require_once dirname(__FILE__).'/helper.php';

$width = $params->get('width');
$height = $params->get('height');
$subfolder = $params->get('subfolder');
$lightbox = $params->get('lightbox');
$lb_yes = $params->get('lb_yes');
$linkfolder = $params->get('linkfolder');
$caption = $params->get('caption');
$bname = $params->get('bname');
$allpics = $params->get('allpics');
$link = $params->get('link');
$newwindow = $params->get('newwindow');
$copy = $params->get('copy');
$image_rotator = $params->get('image_rotator');
$image_rotator_duration = $params->get('image_rotator_duration');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$information_file = $params->get('information_file');

$start = new ModRandomImageExtendedHelper();

$folder = $start->getFolder($params);

if($subfolder)
{
    $images = $start->getImagesSubfolder($params, $folder);
}
else
{
    $images = $start->getImages($params, $folder);
}

$start->loadHeadData($lb_yes, 1);

if(!count($images))
{
    require JModuleHelper::getLayoutPath('mod_random_image_extended', 'noimages');
}
else
{
    if(!empty($information_file))
    {
        $links_info = $start->getFileInfo($folder);
    }
    else
    {
        $links_info = false;
    }

    $image = $start->getRandomImage($params, $images, $width, $height, $image_rotator, $links_info);

    if($lightbox)
    {
        $start->loadHeadData($lb_yes, 2);
        $lightbox_attribute = $start->lightboxAttribute($lb_yes);
    }

    if($image_rotator)
    {
        $start->loadHeadData($lb_yes, 3, $width, $height, $image_rotator_duration);
    }

    require JModuleHelper::getLayoutPath('mod_random_image_extended');
}
