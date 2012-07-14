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

require_once dirname(__FILE__).DS.'helper.php';

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
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$start = new modRandomImageExtendedHelper;

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
    $image = $start->getRandomImage($params, $images);

    if($lightbox)
    {
        $start->loadHeadData($lb_yes, 0);
    }

    require JModuleHelper::getLayoutPath('mod_random_image_extended');
}
