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

class modRandomImageExtendedHelper extends JObject
{
    function getRandomImage($params, &$images)
    {
        $width = $params->get('width');
        $height = $params->get('height');
        $ratio = $params->get('ratio');
        $overwrite = $params->get('overwrite');
        $allpicsrandom = $params->get('allpicsrandom');
        $allpics = $params->get('allpics');

        if($allpicsrandom == 2 AND $allpics == 1)
        {
            $image = $images[0];
            unset($images[0]);
        }
        else
        {
            $i = count($images);
            $random = mt_rand(0, $i - 1);
            $image = $images[$random];
            unset($images[$random]);
        }

        if(!empty($allpicsrandom) AND $allpics == 1)
        {
            shuffle($images);
        }

        $size = getimagesize(JPATH_BASE.DS.$image->folder.DS.$image->name);
        $linkfolder = $params->get('linkfolder');

        if($ratio OR empty($width) OR empty($height))
        {
            if(empty($width))
            {
                $width = 200;
            }

            if($size[0] < $width)
            {
                $width = $size[0];
            }

            $coeff = $size[0] / $size[1];

            if(empty($height))
            {
                $height = (int)($width / $coeff);
            }
            else
            {
                $newheight = min($height, (int)($width / $coeff));

                if($newheight < $height)
                {
                    $height = $newheight;
                }
                else
                {
                    $width = $height * $coeff;
                }
            }
        }

        $image->width = $width;
        $image->height = $height;

        if($linkfolder)
        {
            if(!is_dir(JPATH_SITE.DS.$image->folder.DS.'thumbs'))
            {
                mkdir(JPATH_SITE.DS.$image->folder.DS.'thumbs', 0755);
                $fp = fopen(JPATH_SITE.DS.$image->folder.DS.'thumbs/index.html', "w");
                fclose($fp);
            }

            $filename = $image->folder.'/thumbs/'.$image->name;

            if(!file_exists($filename) OR $overwrite)
            {
                if($size['mime'] == 'image/gif')
                {
                    $image_original = ImageCreateFromGIF($image->folder.DS.$image->name);
                    $image_thumbnail = ImageCreateTrueColor($width, $height);
                    imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                    ImageGIF($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->name, 90);
                }
                elseif($size['mime'] == 'image/jpeg')
                {
                    $image_original = ImageCreateFromJPEG($image->folder.DS.$image->name);
                    $image_thumbnail = ImageCreateTrueColor($width, $height);
                    imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                    ImageJPEG($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->name, 90);
                }
                elseif($size['mime'] == 'image/png')
                {
                    $image_original = ImageCreateFromPNG($image->folder.DS.$image->name);
                    $image_thumbnail = ImageCreateTrueColor($width, $height);
                    imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                    ImagePNG($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->name);
                }

                imagedestroy($image_original);
                imagedestroy($image_thumbnail);
            }
        }

        return $image;
    }

    function getFolder($params)
    {
        $folder = $params->get('folder');

        $livesite = JURI::base();

        if(JString::strpos($folder, $livesite) === 0)
        {
            $folder = str_replace($livesite, '', $folder);
        }

        if(JString::strpos($folder, JPATH_SITE) === 0)
        {
            $folder = str_replace(JPATH_BASE, '', $folder);
        }

        $folder = str_replace('\\', DS, $folder);
        $folder = str_replace('/', DS, $folder);

        return $folder;
    }

    function getImages($params, $folder)
    {
        $types = explode(",", $params->get('type'));

        $files = array();
        $images = array();

        $dir = JPATH_BASE.DS.$folder;

        if(is_dir($dir))
        {
            if($handle = opendir($dir))
            {
                while(false !== ($file = readdir($handle)))
                {
                    if($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html')
                    {
                        $files[] = $file;
                    }
                }
            }
            closedir($handle);

            $i = 0;
            foreach($files as $img)
            {
                if(!is_dir($dir.DS.$img))
                {
                    foreach($types as $type)
                    {
                        $type = trim($type);
                        $muster = "@$type$@i";

                        if(preg_match($muster, $img))
                        {
                            $images[$i]->name = $img;
                            $images[$i]->folder = str_replace('\\', '/', $folder);
                            ++$i;
                            break;
                        }
                    }
                }
            }
        }

        return $images;
    }

    function getImagesSubfolder($params, $folder)
    {
        $images = array();
        $handle = opendir($folder);

        $types_param = explode(",", $params->get('type', 'jpg'));
        $types = array();

        foreach($types_param as $type)
        {
            $types[] = trim($type);
        }

        $i = 0;

        if($handle)
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != "." && $file != ".." && $file != "thumbs")
                {
                    $name = $folder."/".$file;

                    if(is_file($name))
                    {
                        if(in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $types, true))
                        {
                            $images[$i]->name = pathinfo($name, PATHINFO_BASENAME);
                            $images[$i]->folder = str_replace('\\', '/', pathinfo($name, PATHINFO_DIRNAME));
                            ++$i;
                        }
                    }
                    elseif(is_dir($name))
                    {
                        $ar = $this->getImagesSubfolder($params, $name);
                        foreach($ar as $value)
                        {
                            $images[$i] = $value;
                            ++$i;
                        }
                    }
                }
            }
        }

        closedir($handle);

        return $images;
    }

    function loadHeadData($lb_yes, $css)
    {
        $document = JFactory::getDocument();

        if($css)
        {
            $css = '.random_image_extended {text-align: center !important; margin: 10px 0 !important;}'."\n";
            $css .= '.random_image_extended_small {text-align: right !important; font-size: 0.85em !important; margin-top: 15px !important;}'."\n";
            $document->addStyleDeclaration($css);
        }
        else
        {
            $head = array();

            if($lb_yes == "slimbox")
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/slimbox/css/slimbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/slimbox/js/slimbox.js"></script>';
            }
            elseif($lb_yes == "milkbox")
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/milkbox/milkbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/milkbox/milkbox.js"></script>';
            }
            elseif($lb_yes == "shadowbox")
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/shadowbox/shadowbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/shadowbox/shadowbox.js"></script>';
                $head[] = '<script type="text/javascript">Shadowbox.init();</script>';
            }

            $head = "\n".implode("\n", $head)."\n";
            $document->addCustomTag($head);
        }
    }
}
