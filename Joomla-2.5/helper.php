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

class ModRandomImageExtendedHelper extends JObject
{
    public function getRandomImage($params, &$images, $width, $height, $image_rotator, $links_info = false)
    {
        $ratio = $params->get('ratio');
        $linkfolder = $params->get('linkfolder');
        $overwrite = $params->get('overwrite');
        $allpicsrandom = $params->get('allpicsrandom');
        $allpics = $params->get('allpics');

        $this->setImageProperties($images, $links_info);

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

        // Calculate correct image size
        $this->calculateImageSize($image, $ratio, $width, $height);

        if($linkfolder)
        {
            $this->createThumbnails($image, $overwrite);
        }

        if($image_rotator)
        {
            foreach($images as &$image_tail)
            {
                $this->calculateImageSize($image_tail, $ratio, $width, $height);

                if($linkfolder)
                {
                    $this->createThumbnails($image_tail, $overwrite);
                }
            }
        }

        return $image;
    }

    public function getFolder($params)
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

    public function getImages($params, $folder)
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
                        if(preg_match('@'.trim($type).'$@', $img))
                        {
                            $images[$i] = new stdClass();
                            $images[$i]->link = $img;
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

    public function getImagesSubfolder($params, $folder)
    {
        $images = array();
        $handle = opendir($folder);

        $types_param = explode(',', $params->get('type', 'jpg'));
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
                if($file != '.' && $file != '..' && $file != 'thumbs')
                {
                    $name = $folder.'/'.$file;

                    if(is_file($name))
                    {
                        if(in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $types, true))
                        {
                            $images[$i] = new stdClass();
                            $images[$i]->link = pathinfo($name, PATHINFO_BASENAME);
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

    public function loadHeadData($lb_yes, $type, $width = false, $height = false, $image_rotator_duration = false)
    {
        $document = JFactory::getDocument();

        if($type == 1)
        {
            $css = '.random_image_extended {text-align: center; margin: 10px 0 !important;}'."\n";
            $css .= '.random_image_extended_small {text-align: right; font-size: 0.85em !important; margin-top: 15px !important;}'."\n";
            $document->addStyleDeclaration($css);
        }
        elseif($type == 2)
        {
            $head = array();

            if($lb_yes == 'slimbox')
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/slimbox/css/slimbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/slimbox/js/slimbox.js"></script>';
            }
            elseif($lb_yes == 'milkbox')
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/milkbox/milkbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/milkbox/milkbox.js"></script>';
            }
            elseif($lb_yes == 'shadowbox')
            {
                $head[] = '<link rel="stylesheet" href="modules/mod_random_image_extended/shadowbox/shadowbox.css" type="text/css" media="screen" />';
                $head[] = '<script type="text/javascript" src="modules/mod_random_image_extended/shadowbox/shadowbox.js"></script>';
                $head[] = '<script type="text/javascript">Shadowbox.init();</script>';
            }

            $head = "\n".implode("\n", $head)."\n";
            $document->addCustomTag($head);
        }
        elseif($type == 3)
        {
            $css = '#slideshow-container  {width: '.$width.'px; height: '.$height.'px; position: relative; overflow: hidden; text-align: left; margin: auto;}'."\n";
            $css .= '#slideshow-container img {display: inline-block; position: absolute; top: 0; left: 0; z-index: 1;}';
            $document->addStyleDeclaration($css);

            // Credit: David Walsh - http://davidwalsh.name/mootools-slideshow
            $image_rotator = 'window.addEvent(\'domready\',function() {
                        var showDuration = '.$image_rotator_duration.'000;
                        var container = $(\'slideshow-container\');
                        var images = container.getElements(\'img\');
                        var currentIndex = 0;
                        var interval;
                        images.each(function(img,i){
                            if(i > 0) {
                                img.set(\'opacity\',0);
                            }
                        });
                        var show = function() {
                            images[currentIndex].set(\'tween\', {duration: 1500}).fade(\'out\');
                            images[currentIndex = currentIndex < images.length - 1 ? currentIndex+1 : 0].set(\'tween\', {duration: 1500}).fade(\'in\');
                        };
                        window.addEvent(\'load\',function(){
                            interval = show.periodical(showDuration);
                        });
                    });
                ';

            $document->addScriptDeclaration($image_rotator, 'text/javascript');
        }
    }

    private function calculateImageSize(&$image, $ratio, $width, $height)
    {
        $size = getimagesize(JPATH_BASE.DS.$image->folder.DS.$image->link);

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
    }

    private function createThumbnails($image, $overwrite)
    {
        if(!is_dir(JPATH_SITE.DS.$image->folder.DS.'thumbs'))
        {
            mkdir(JPATH_SITE.DS.$image->folder.DS.'thumbs', 0755);
            $fp = fopen(JPATH_SITE.DS.$image->folder.DS.'thumbs/index.html', 'w');
            fclose($fp);
        }

        $filename = $image->folder.'/thumbs/'.$image->link;

        if(!file_exists($filename) OR $overwrite)
        {
            $size = getimagesize(JPATH_BASE.DS.$image->folder.DS.$image->link);

            if($size['mime'] == 'image/gif')
            {
                $image_original = ImageCreateFromGIF($image->folder.DS.$image->link);
                $image_thumbnail = ImageCreateTrueColor($image->width, $image->height);
                imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $image->width, $image->height, $size[0], $size[1]);
                ImageGIF($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->link, 90);
            }
            elseif($size['mime'] == 'image/jpeg')
            {
                $image_original = ImageCreateFromJPEG($image->folder.DS.$image->link);
                $image_thumbnail = ImageCreateTrueColor($image->width, $image->height);
                imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $image->width, $image->height, $size[0], $size[1]);
                ImageJPEG($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->link, 90);
            }
            elseif($size['mime'] == 'image/png')
            {
                $image_original = ImageCreateFromPNG($image->folder.DS.$image->link);
                $image_thumbnail = ImageCreateTrueColor($image->width, $image->height);
                imagecopyresampled($image_thumbnail, $image_original, 0, 0, 0, 0, $image->width, $image->height, $size[0], $size[1]);
                ImagePNG($image_thumbnail, $image->folder.DS.'thumbs'.DS.$image->link);
            }

            imagedestroy($image_original);
            imagedestroy($image_thumbnail);
        }
    }

    /**
     * Loads information from a text file which has to be uploaded in the image folder with the name info.txt
     *
     * The entries in the info.txt have this structure:
     *
     * IMAGENAME.EXT|TITLE|LINK
     *
     * Each line one entry!
     *
     * @param type $folder
     * @return type
     */
    public function getFileInfo($folder)
    {
        $links_info = false;
        $links_txtfile = $folder.'/info.txt';

        if(file_exists($links_txtfile))
        {
            $links_file = file($links_txtfile);
            $count = 0;

            foreach($links_file as $value)
            {
                $links_line = explode('|', $value);

                if(empty($links_line[2]))
                {
                    $links_line[2] = '';
                }

                $links_info[$links_line[0]] = array($links_line[1], $links_line[2]);
                $count++;
            }
        }

        return $links_info;
    }

    /**
     * Sets the properties of the images from the text file if provided
     * Private function - called in the function getRandomImage
     *
     * @param array $images
     * @param array $link_info
     */
    private function setImageProperties(&$images, $link_info)
    {
        if(!empty($link_info))
        {
            $images_info = array();

            foreach($link_info as $image_name => $image_info)
            {
                foreach($images as $key => $image)
                {
                    if($image_name == $image->link)
                    {
                        $image->name = $image_info[0];
                        $image->linkto = $image_info[1];

                        $images_info[] = $image;

                        // Unset this image in the images array to avoid unnecessary loops
                        unset($images[$key]);
                        break;
                    }
                }
            }

            if(!empty($image_info))
            {
                $images = array_merge($images_info, $images);
            }
        }

        // Now we have to set the needed properties for images with no entry in the text file or if the text file is not used at all
        foreach($images as &$image)
        {
            // If name is not set, then we have to generate it from the image name - remove extensions of the file name
            if(empty($image->name))
            {
                $image->name = substr($image->link, 0, -4);
            }

            // Linkto property is set to an empty string
            if(!isset($image->linkto))
            {
                $image->linkto = '';
            }
        }
    }

}
