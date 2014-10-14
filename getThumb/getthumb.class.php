<?php

/**
 * Getthumb API
 *
 * This file contains a class for image manipulating.
 *
 * @package Getthumb
 * @version 0.7.2
 * @license LGPL
 * @author Gábor László <gopher@vipmail.hu>
 */

require_once dirname(__FILE__).'/getthumb_exception.class.php';

/**
 * A class for image manipulating
 */
class Getthumb {
	
	/**
	 * Source image path
	 * @var string
	 */
	private $src;

	/**
	 * Source image 'getimagesize' info
	 * @var array
	 */
	private $srcSize;

	/**
	 * Source image MIME type
	 * @var string
	 */
	private $srcMime;

	/**
	 * Source image type (jpeg, png, gif)
	 * @var string
	 */	
	private $srcType;
	/**
	 * Source rectangle X position
	 * @var int
	 */
	private $srcX;
	/**
	 * Source rectangle Y position
	 * @var int
	 */
	private $srcY;
	
	/**
	 * Width of source rectangle 
	 * @var int
	 */
	private $srcW;

	/**
	 * Height of source rectangle 
	 * @var int
	 */
	private $srcH;
	
	/**
	 * Path to destination file (cache file)
	 * @var int
	 */
	private $dstPath;

	/**
	 * Destination rectangle X position
	 * @var int
	 */
	private $dstX;

	/**
	 * Destination rectangle Y position
	 * @var int
	 */
	private $dstY;

	/**
	 * Width of destination rectangle
	 * @var int
	 */
	private $dstW;

	/**
	 * Height of destination rectangle
	 * @var int
	 */
	private $dstH;

	/**
	 * Destination image
	 * @var resource
	 */	
	private $dstImage;
	
	/**
	 * Configuration
	 * @var array
	 */
	private $config;

	/**
	 * Sets up the configuration with default values
	 */
	public function __construct()
	{
		$this->config = array(
			'cache_dir'      => false,
			'no_cache'       => true,
			'width'          => 0,
			'height'         => 0,
			'crop'           => false,
			'quality'        => 90,
			'keep_ratio'     => false,
			'overlay'        => false,
			'overlay_x'      => false,
			'overlay_y'      => false,
			'overlay_align'  => false,
			'overlay_valign' => false,
			'time'           => 0,
			'brightness'     => false,
			'contrast'       => false,
			'output'         => false,
			'src_x'          => false,
			'src_y'          => false,
			'src_w'          => false,
			'src_h'          => false
		);		
	}
	
	/**
	 * Returns with the generated thumbnail
	 *
	 * Initialize the image manipulating, checks the cache for
	 * thumbnail if 'no_cache' is false. If the thumbnail not exists
	 * or 'no_cache', generate it.
	 *
	 * @param string Path to the source image
	 * @param array Configuration
	 * @param bool Send to output or return with it?
	 * @return string Result image file data
	 */
	public function get($src, $config = false)
	{
		if (!$this->init($src, $config))
		{
			return false;
		}
				
		if (file_exists($this->dstPath) and !$this->config['no_cache'])
		{
			if (!$this->config['output'])
			{
				ob_start();
				readfile($this->dstPath);
				$ret = ob_get_contents();
				ob_end_clean();
		
				return $ret;
			}
		
			$this->imageHeaders('jpeg');
			readfile($this->dstPath);
		
			return true;
		}
		
		$ret = $this->generate();
		
		if ($this->config['output'])
		{
			$this->imageHeaders('jpeg');
			echo $ret;
			return true;
		}
				
		return $ret;
	}	

	/**
	 * Initialize the image manipulation
	 *
	 * Checks the src, width or height parameters and the source image's existance.
	 * Gets the source image size and mime type, then validate thats.
	 *
	 * @param string Path to the source image
	 * @param array Configuration
	 * @return bool Initalization is success or failed?
	 */
	public function init($src, $config = false)
	{

		// Check the source
		if (!$src)
			throw new Error("Please specify the src parameter!", 1);

		if (!file_exists($src))
			throw new Error("Source file is not exists.", 2);
		
		$this->src = $src;
		if (is_array($config)) {
			$this->config = array_merge($this->config, $config);
		}
		if (!$this->config['cache_dir'] and !$this->config['no_cache']) {
			$this->config['cache_dir'] = $this->getTempDir();
		}

		// Check the source MIME type
		$this->srcSize = getimagesize($src);

		if (!$this->srcSize)
			throw new Error("Source file is not a valid image file.", 3);

		$this->srcMime = $this->srcSize['mime'];
		$this->srcType = str_replace('image/', '', $this->srcMime);

		if ($this->srcType != 'jpeg' and $this->srcType != 'gif' and $this->srcType != 'png')
			throw new Error("Source file is not a valid image file.", 4);
		
		// Check the config 
		if (!$this->config['width'] && !$this->config['height'])
			throw new Error("Please specify the config or the width or the\nheight parameter!", 5);

		if (!$this->config['no_cache'])
			$this->dstPath = $this->getDestinationPath();
			
		return true;
	}

	/**
	 * Generates a thumbnail and cache if needed
	 *
	 * @return bool|string Success or not, image data
	 */
	public function generate()
	{

		// Resampling
		$this->calculateCoords();
		
		$func = 'imagecreatefrom'.$this->srcType;
		$srcImage = $func($this->src);
		
		if (!$srcImage)
			throw new Error("Can't create GD image from the source.", 6);

		$this->clean();
		
		$this->dstImage = imagecreatetruecolor(
			 $this->config['width'] ? $this->config['width'] : $this->dstW,
			 $this->config['height'] ? $this->config['height'] : $this->dstH
		);
		if (!$this->dstImage)
		{
			imagedestroy($srcImage);
			throw new Error("Can't create GD image for destination.", 7);
		}
		
		imagecopyresampled(
			$this->dstImage, $srcImage,
			$this->dstX, $this->dstY, $this->srcX, $this->srcY,
			$this->dstW, $this->dstH, $this->srcW, $this->srcH
		);
		imagedestroy($srcImage);
		
		$this->generateOverlay();
		
		// Brightness
		if (is_numeric($this->config['brightness']))
			imagefilter($this->dstImage, IMG_FILTER_BRIGHTNESS, $this->config['brightness']*255);

		// Contrast
		if (is_numeric($this->config['contrast']))
			imagefilter($this->dstImage, IMG_FILTER_CONTRAST, -$this->config['contrast']*255);
			
		// Cache if needed, and return
		ob_start();
		
		imagejpeg($this->dstImage, null, $this->config['quality']);
		
		$content = ob_get_contents();
		
		if (!$this->config['no_cache'])
		{
			$f = fopen($this->dstPath, 'w');
			if (!$f)
			{
				ob_end_clean();
				throw new Error("Can't write cache.", 12);
			}
			fwrite($f, $content);
			fclose($f);
		}
		
		ob_end_clean();
		
		return $content;
	}
	
	/**
	 * Generates an overlay to the dstImage by the "overlay", the "overlay_align"
	 * and the "overlay_valign" config parameters	 
	 */	
	private function generateOverlay()
	{
		// Overlay image
		if (!$this->config['overlay']) return;

		// Check overlay image
		if (!file_exists($this->config['overlay']))
			throw new Error("Overlay file not exists.", 8);

		$srcSize = getimagesize($this->config['overlay']);

		if (!$srcSize)
			throw new Error("Overlay file is not a valid image file.", 9);

		$srcType = str_replace('image/', '', $srcSize['mime']); 

		if ($srcType != 'gif' and $srcType != 'png')
			throw new Error("Overlay image type is not PNG or GIF.", 10);

		// Set overlay source rectangle (full image)
		$srcX = 0;
		$srcY = 0;
		$srcW = $srcSize[0];
		$srcH = $srcSize[1];
		
		// Create overlay image
		$func = 'imagecreatefrom'.$srcType;
		$srcImage = $func($this->config['overlay']);
		
		if (!$srcImage)
			throw new Error("Can't create GD image for overlay.", 11);

		$dstX = 0;
		$dstY = 0;
		
		if ($this->config['overlay_x'])
		{
			$dstX = $this->config['overlay_x'];
		}
		else if ($this->config['overlay_align'])
		{
			switch ($this->config['overlay_align'])
			{
				case 'left': $dstX = 0; break;
				case 'center': $dstX = ($this->dstW - $srcW) / 2; break;
				case 'right': $dstX = $this->dstW - $srcW; break;
			}
		}
		
		if ($this->config['overlay_y'])
		{
			$dstY = $this->config['overlay_y'];
		}
		else if ($this->config['overlay_valign'])
		{
			switch ($this->config['overlay_valign'])
			{
				case 'top': $dstY = 0; break;
				case 'center': $dstY = ($this->dstH - $srcH) / 2; break;
				case 'bottom': $dstY = $this->dstH - $srcH; break;
			}
		}

		// Draw overlay
		imagecopy($this->dstImage, $srcImage, $dstX, $dstY, 0, 0, $srcW, $srcH);
		imagedestroy($srcImage);
	}

	/**
	 * Destroys the destination image if exists
	 */
	public function clean()
	{
		if (is_resource($this->dstImage))
			imagedestroy($this->dstImage);
	}

	/**
	 * Returns with the config
	 *
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Sends the image headers by type and length
	 *
	 * @param string The image type (jpeg, gif, ... )
	 * @param int Length of the content
	 */
	public function imageHeaders($type, $length = 0)
	{
		header('Last-Modified: '.date('r'));
		header('Accept-Ranges: bytes');
		
		if ($length)
			header('Content-Length: '.$length);
		
		header('Content-Type: image/'.$type);
	}

	/**
	 * Returns with the destination filename (cached image) by src and config
	 * and if needed create the directories in cache_dir 	 
	 *
	 * @return string
	 */
	public function getDestinationPath()
	{
		$filename  = md5($this->src.serialize($this->config));
		$filename .= '.jpg';
		
		$dir1 = substr($filename, 0, 2);
		$dir2 = substr($filename, 2, 2);
		
		$path = $this->config['cache_dir'].'/'.$dir1.'/'.$dir2;
		
		if (!file_exists($path))
		{
			mkdir($path, 0777, true);
			
			chmod($this->config['cache_dir'].'/'.$dir1, 0777);
			chmod($this->config['cache_dir'].'/'.$dir1.'/'.$dir2, 0777);
		}
		
		$ret = $this->config['cache_dir'].'/'.$dir1.'/'.$dir2.'/'.$filename;
		
		return $ret;
	}

	/**
	 * Calculates the positions and the sizes of source and destination rectangle
	 */
	public function calculateCoords()
	{
		// Source rectangle

		$manual_crop = $this->config['src_x'] !== false and
			$this->config['src_y'] !== false and
			$this->config['src_w'] !== false and
			$this->config['src_h'] !== false;
			
		if ($manual_crop)
		{
			$this->srcX = $this->config['src_x'];
			$this->srcY = $this->config['src_y'];
			$this->srcW = $this->config['src_w'];
			$this->srcH = $this->config['src_h'];			
		}
		else
		{
			$this->srcX = 0;
			$this->srcY = 0;
			$this->srcW = $this->srcSize[0];
			$this->srcH = $this->srcSize[1];
		}		

  		// Destination rectangle

		$this->dstX = 0;
		$this->dstY = 0;
		$this->dstW = $this->config['width'];
		$this->dstH = $this->config['height'];
		
		if ($this->dstW and $this->dstH)
		{
			// Fixed size
			if ($this->config['crop'])
			{
				// Crop
				if (!$manual_crop)
				{
					if (($this->srcW / $this->dstW) > ($this->srcH / $this->dstH)) {
						$old_src_w = $this->srcW;
						$this->srcW = ($this->dstW / $this->dstH) * $this->srcH;
						$this->srcX = ($old_src_w - $this->srcW) / 2;
					} else {
						$old_src_h = $this->srcH;
						$this->srcH = ($this->dstH / $this->dstW) * $this->srcW;
						$this->srcY = ($old_src_h - $this->srcH) / 2;		
					}
				}
			}
			else if ($this->config['keep_ratio'])
			{
				// Keep ratio
				if ($this->srcW > $this->srcH) {
					$this->dstW = $this->config['width'];
					$this->dstH = ($this->srcH / $this->srcW) * $this->dstW;
				} else {
					$this->dstH = $this->config['height'];
					$this->dstW = ($this->srcW / $this->srcH) * $this->dstH;
				}
				$this->config['width'] = 0;
				$this->config['height'] = 0;
			}
		}
		else
		{
			// Fixed width or height
			if ($this->dstW)
			{
				$this->dstH = ($this->srcH / $this->srcW) * $this->dstW;
			}
			else
			{
				$this->dstW = ($this->srcW / $this->srcH) * $this->dstH;
			}
		}		
	}

	/**
	 * Returns with the path to PHP's temp directory
	 *
	 * This hack is from:
	 * http://www.phpbuilder.com/board/archive/index.php/t-10282458.html
	 *
	 * @return string
	 */
	private function getTempDir()
	{
		$tmpfile = tempnam('dummy', '');
		$ret = dirname($tmpfile);
		unlink($tmpfile);
		return $ret;
	}
	
	/**
	 * Destructor of Getthumb
	 *
	 * Cleans memory if needed
	 */
	public function __destruct()
	{
		$this->clean();
	}
	
}
