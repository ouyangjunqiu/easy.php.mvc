<?php
/**
 * 文件上传类
 * @author Jack Chan
 * @since 2016-01-20
 */
class FileUploader
{
	/***************** 错误类型定义 *****************/
	/**
	 * 无任何错误
	 */
	const ERROR_NONE						= 0;
	
	/**
	 * 创建目录失败错误
	 */
	const ERROR_CREATE_DIRECTORY_FAILED		= 1;
	
	/**
	 * 表单字段名未找到错误
	 */
	const ERROR_FIELD_NAME_NOT_FOUND		= 2;
	
	/**
	 * 超出PHP配置中的大小限制错误
	 */
	const ERROR_EXCEEDS_INI_SIZE			= 3;
	
	/**
	 * 超出表单中大小限制错误
	 */
	const ERROR_EXCEEDS_FORM_SIZE			= 4;
	
	/**
	 * 文件只有部分被上传错误
	 */
	const ERROR_PARTIAL_UPLOADED			= 5;
	
	/**
	 * 没有文件被上传错误
	 */
	const ERROR_NO_FILE_UPLOADED			= 6;
	
	/**
	 * 文件扩展名不允许错误
	 */
	const ERROR_EXTENSION_NOT_ALLOWED		= 7;
	
	/**
	 * 超出大小限制错误
	 */
	const ERROR_EXCEEDS_MAX_SIZE			= 8;
	
	/**
	 * 移动上传的临时文件错误
	 */
	const ERROR_MOVE_FILE_FAILED			= 9;
	
	/**
	 * 图片文件无效错误
	 */
	const ERROR_IMAGE_FILE_INVALID			= 10;
	
	/**
	 * 图片类型不支持错误
	 */
	const ERROR_IMAGE_TYPE_NOT_SUPPORTED	= 11;
	
	/**
	 * 上传文件到阿里云服务器失败错误
	 */
	const ERROR_UPLOAD_TO_ALIOSS_FAILED		= 12;
	
	/***************** 错误消息定义 *****************/
	/**
	 * 创建目录失败错误消息
	 */
	const MESSAGE_CREATE_DIRECTORY_FAILED	= '创建目录失败：';
	
	/**
	 * 表单字段名未找到错误消息
	 */
	const MESSAGE_FIELD_NAME_NOT_FOUND		= '表单字段名未找到：';
	
	/**
	 * 超出PHP配置中的大小限制错误消息
	 */
	const MESSAGE_EXCEEDS_INI_SIZE			= '超出PHP配置中的大小限制';
	
	/**
	 * 超出表单中大小限制错误消息
	 */
	const MESSAGE_EXCEEDS_FORM_SIZE			= '超出表单中大小限制';
	
	/**
	 * 文件只有部分被上传错误消息
	 */
	const MESSAGE_PARTIAL_UPLOADED			= '文件只有部分被上传';
	
	/**
	 * 没有文件被上传错误消息
	 */
	const MESSAGE_NO_FILE_UPLOADED			= '没有文件被上传';
	
	/**
	 * 文件扩展名不允许错误消息
	 */
	const MESSAGE_EXTENSION_NOT_ALLOWED		= '文件扩展名不允许：';
	
	/**
	 * 文件超过大小限制错误消息
	 */
	const MESSAGE_EXCEEDS_MAX_SIZE			= '文件超过大小限制：';
	
	/**
	 * 移动上传的临时文件失败错误消息
	 */
	const MESSAGE_MOVE_FILE_FAILED			= '移动上传的临时文件失败，目标路径：';
	
	/**
	 * 图片文件无效错误消息
	 */
	const MESSAGE_IMAGE_FILE_INVALID		= '图片文件无效';
	
	/**
	 * 图片类型不支持错误消息
	 */
	const MESSAGE_IMAGE_TYPE_NOT_SUPPORTED	= '图片类型不支持：';
	
	/**
	 * 上传文件到阿里云服务器失败错误消息
	 */
	const MESSAGE_UPLOAD_TO_ALIOSS_FAILED	= '上传文件到阿里云服务器失败';
	
	/********** 图片类型定义 **********/
	/**
	 * GIF图片类型
	 */
	const IMAGE_TYPE_GIF	= 1;
	
	/**
	 * JPG图片类型
	 */
	const IMAGE_TYPE_JPG	= 2;
	
	/**
	 * PNG图片类型
	 */
	const IMAGE_TYPE_PNG	= 3;
	
	/***************** 默认值定义 *****************/
	/**
	 * 默认文件保存根目录
	 */
	const DEFAULT_BASE_DIRECTORY			= './uploads';
	
	/**
	 * 默认日期格式目录
	 */
	const DEFAULT_DATE_FORMAT_DIRECTORY		= '/Y/m/d';
	
	/**
	 * 默认表单字段名称
	 */
	const DEFAULT_FIELD_NAME				= 'file';
	
	/**
	 * 默认文件保存名随机数字长度
	 */
	const DEFAULT_RANDOM_NUMBER_LENGTH		= 3;
	
	/**
	 * 默认图片最大宽度
	 */
	const DEFAULT_MAX_WIDTH		= 800;
	
	/**
	 * 默认图片最大高度
	 */
	const DEFAULT_MAX_HEIGHT	= 800;
	
	/**
	 * 默认允许的图片扩展名数组
	 */
	protected static $defaultAllowedImageExtensions	= array('jpg', 'jpeg', 'png', 'gif');
	
	/**
	 * 文件保存根目录
	 */
	protected $baseDirectory = self::DEFAULT_BASE_DIRECTORY;
	
	/**
	 * 文件保存目录
	 */
	protected $saveDirectory;
	
	/**
	 * 文件保存名
	 */
	protected $saveName;
	
	/**
	 * 文件保存名后缀
	 */
	protected $saveNameSuffix;
	
	/**
	 * 文件保存扩展名
	 */
	protected $saveExtension;
	
	/**
	 * 允许的文件扩展名数组
	 */
	protected $allowedExtensions;
	
	/**
	 * 文件大小限制，单位为兆字节(MB)
	 */
	protected $maxFileSize;
	
	/**
	 * 文件原始信息
	 */
	protected $fileInfo;
	
	/**
	 * 错误类型
	 */
	protected $errorType;
	
	/**
	 * 错误消息
	 */
	protected $errorMessage;
	
	/**
	 * 设置文件保存根目录
     * @param string|null $baseDirectory
	 * @return bool
	 */
	public function setBaseDirectory($baseDirectory = null)
	{
		// 保证文件保存根目录为子目录情况下不以/结尾
		if (!$baseDirectory || $baseDirectory == '/')
			$baseDirectory = '.';
		else
			$baseDirectory = rtrim($baseDirectory, '/');
		
		if (!$this->createDirectory($baseDirectory))
			return false;
		
		$this->baseDirectory = $baseDirectory;
		return true;
	}
	
	/**
	 * 获取文件保存根目录
	 */
	public function getBaseDirectory()
	{
		return $this->baseDirectory;
	}
	
	/**
	 * 通过日期创建文件保存目录，相对于文件保存根目录。
     * @param null|string $dateFormat
	 * @return bool
	 */
	public function createSaveDirectoryByDate($dateFormat = self::DEFAULT_DATE_FORMAT_DIRECTORY)
	{
		return $this->setSaveDirectory(date($dateFormat));
	}
	
	/**
	 * 设置文件保存目录，相对于文件保存根目录。
     * @param string $saveDirectory
	 * @return bool
	 */
	public function setSaveDirectory($saveDirectory)
	{
		// 保证文件保存目录以/开头
		if (strpos($saveDirectory, '/') !== 0)
			$saveDirectory = '/'.$saveDirectory;
		
		if (!$this->createDirectory($this->baseDirectory.$saveDirectory))
			return false;
		
		$this->saveDirectory = $saveDirectory;
		return true;
	}
	
	/**
	 * 获取文件保存目录
	 */
	public function getSaveDirectory()
	{
		return $this->saveDirectory;
	}
	
	/**
	 * 获取完整文件保存目录，包括文件保存根目录和文件保存目录。
	 */
	public function getFullSaveDirectory()
	{
		return $this->baseDirectory.$this->saveDirectory;
	}
	
	/**
	 * 通过微秒时间生成文件保存名，确保文件名唯一。
	 * @return string 返回生成的文件保存名
	 */
	public function createSaveNameByMicroTime()
	{
		return $this->saveName = DateTimeUtil::getMicroTime();
	}
	
	/**
	 * 设置文件保存名
     * @param string $saveName
	 */
	public function setSaveName($saveName)
	{
		$this->saveName = $saveName;
	}
	
	/**
	 * 获取文件保存名
	 */
	public function getSaveName()
	{
		return $this->saveName;
	}

	/**
	 * 设置文件保存名后缀
     * @param string $saveNameSuffix
	 */
	public function setSaveNameSuffix($saveNameSuffix)
	{
		$this->saveNameSuffix = $saveNameSuffix;
	}
	
	/**
	 * 获取文件保存名后缀
	 */
	public function getSaveNameSuffix()
	{
		return $this->saveNameSuffix;
	}

	/**
	 * 设置文件保存扩展名
     * @param string $saveExtension
	 */
	public function setSaveExtension($saveExtension)
	{
		$this->saveExtension = strtolower($saveExtension);
	}
	
	/**
	 * 获取文件保存扩展名
	 */
	public function getSaveExtension()
	{
		return $this->saveExtension;
	}
	
	/**
	 * 获取完整文件保存名，包括文件保存名和文件保存名后缀。
	 */
	public function getFullSaveName()
	{
		return $this->saveName.$this->saveNameSuffix;
	}
	
	/**
	 * 获取完整文件保存文件名，包括文件保存名、文件保存名后缀和文件保存扩展名。
	 */
	public function getFullSaveFileName()
	{
		return $this->saveName.$this->saveNameSuffix.'.'.$this->saveExtension;
	}
	
	/**
	 * 获取完整文件保存路径，包括文件保存根目录、文件保存目录、文件保存名、文件保存名后缀和文件保存扩展名。
	 */
	public function getFullSaveFilePath()
	{
		return $this->baseDirectory.$this->saveDirectory.'/'.$this->saveName.$this->saveNameSuffix.'.'.$this->saveExtension;
	}
	
	/**
	 * 设置允许的文件扩展名数组
	 */
	public function setAllowedExtendsions(array $allowedExtensions)
	{
		// 转换为小写
		foreach ($allowedExtensions as $k => $v)
			$allowedExtensions[$k] = strtolower($v);
		
		$this->allowedExtensions = $allowedExtensions;
	}
	
	/**
	 * 获取允许的文件扩展名数组
	 */
	public function getAllowedExtendsions()
	{
		return $this->allowedExtensions;
	}
	
	/**
	 * 设置文件大小限制
	 * @param float $maxFileSize 单位为兆字节(MB)
	 */
	public function setMaxFileSize($maxFileSize)
	{
		$this->maxFileSize = $maxFileSize;
	}
	
	/**
	 * 获取文件大小限制
	 */
	public function getMaxFileSize()
	{
		return $this->maxFileSize;
	}
	
	/**
	 * 获取文件原始信息
	 */
	public function getFileInfo()
	{
		return $this->fileInfo;
	}
	
	/**
	 * 获取错误类型
	 */
	public function getErrorType()
	{
		return $this->errorType;
	}
	
	/**
	 * 获取错误消息
	 */
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	/**
	 * 上传文件
	 * @param string $fieldName 表单文件字段名
	 * @return string|false 成功返回完整文件保存路径，失败返回false。
	 */
	public function upload($fieldName = self::DEFAULT_FIELD_NAME)
	{
		// 判断表单文件字段名
		if (!isset($_FILES[$fieldName]))
		{
			$this->errorType	= self::ERROR_FIELD_NAME_NOT_FOUND;
			$this->errorMessage	= self::MESSAGE_FIELD_NAME_NOT_FOUND.$fieldName;
			return false;
		}
		
		$this->fileInfo = $fileInfo = $_FILES[$fieldName];
		
		// 判断文件上传错误信息
		if (isset($fileInfo['error']) && $fileInfo['error'] != UPLOAD_ERR_OK)
		{
			switch ($fileInfo['error'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->errorType	= self::ERROR_EXCEEDS_INI_SIZE;
					$this->errorMessage	= self::MESSAGE_EXCEEDS_INI_SIZE;
					break;
					
				case UPLOAD_ERR_FORM_SIZE:
					$this->errorType	= self::ERROR_EXCEEDS_FORM_SIZE;
					$this->errorMessage	= self::MESSAGE_EXCEEDS_FORM_SIZE;
					break;
					
				case UPLOAD_ERR_PARTIAL:
					$this->errorType	= self::ERROR_PARTIAL_UPLOADED;
					$this->errorMessage	= self::MESSAGE_PARTIAL_UPLOADED;
					break;
					
				case UPLOAD_ERR_NO_FILE:
					$this->errorType	= self::ERROR_NO_FILE_UPLOADED;
					$this->errorMessage	= self::MESSAGE_NO_FILE_UPLOADED;
					break;
			}
			
			return false;
		}
		
		// 扩展名限制
		$pathInfo	= pathinfo($fileInfo['name']);
		$extension	= strtolower($pathInfo['extension']);
		
		if (is_array($this->allowedExtensions) && !in_array($extension, $this->allowedExtensions))
		{
			$this->errorType	= self::ERROR_EXTENSION_NOT_ALLOWED;
			$this->errorMessage	= self::MESSAGE_EXTENSION_NOT_ALLOWED.$extension;
			return false;
		}
		
		// 文件大小限制
		if ($this->maxFileSize > 0 && $fileInfo['size'] > $this->maxFileSize * 1024 * 1024)
		{
			$this->errorType	= self::ERROR_EXCEEDS_MAX_SIZE;
			$this->errorMessage	= self::MESSAGE_EXCEEDS_MAX_SIZE.$this->maxFileSize.'MB';
			return false;
		}
		
		// 设置默认的根目录
		if (!$this->baseDirectory)
			$this->baseDirectory = self::DEFAULT_BASE_DIRECTORY;
		
		// 生成默认的文件保存目录
		if (!$this->saveDirectory)
			$this->createSaveDirectoryByDate();
		
		// 生成默认的文件保存名
		if (!$this->saveName)
			$this->createSaveNameByMicroTime();
		
		// 设置默认的文件保存扩展名
		if (!$this->saveExtension)
			$this->saveExtension = $extension;
		
		// 移动上传的临时文件
		$filePath = $this->getFullSaveFilePath();
		
		if (!move_uploaded_file($fileInfo['tmp_name'], $filePath))
		{
			$this->errorType	= self::ERROR_MOVE_FILE_FAILED;
			$this->errorMessage	= self::MESSAGE_MOVE_FILE_FAILED.$filePath;
			return false;
		}
		
		$this->errorType	= self::ERROR_NONE;
		$this->errorMessage	= null;
		return $filePath;
	}
	
	/**
	 * 上传图片，如果最大宽度或最大高度大于零则自动缩放大小，宽度和高度不超过最大值，并且缩略图将覆盖原图。
	 * @param string $name 表单文件字段名或临时文件路径
	 * @param int $maxWidth 小于等于0则不限制最大宽度
	 * @param int $maxHeight 小于等于0则不限制最大高度
     * @param int $previewWidth
     * @param int $cropX
     * @param int $cropY
     * @param int $cropWidth
     * @param int $cropHeight
	 * @return string|false 成功返回完整文件保存路径，失败返回false。
	 */
	public function uploadImage($name, $maxWidth = self::DEFAULT_MAX_WIDTH, $maxHeight = self::DEFAULT_MAX_HEIGHT,
			$previewWidth = 0, $cropX = 0, $cropY = 0, $cropWidth = 0, $cropHeight = 0)
	{
		if (!$this->allowedExtensions)
			$this->allowedExtensions = self::$defaultAllowedImageExtensions;
		
		if (is_file($name))
		{
			// 设置扩展名
			$pathInfo = pathinfo($name);
			$this->saveExtension = strtolower($pathInfo['extension']);
			
			// 设置默认的根目录
			if (!$this->baseDirectory)
				$this->baseDirectory = self::DEFAULT_BASE_DIRECTORY;
			
			// 创建文件保存目录
			$this->createSaveDirectoryByDate();
			
			// 生成文件保存名
			$this->createSaveNameByMicroTime();
			
			// 完整文件保存路径
			$filePath = $this->getFullSaveFilePath();
			
			// 移动临时文件
			if (!rename($name, $filePath))
			{
				$this->errorType	= self::ERROR_MOVE_FILE_FAILED;
				$this->errorMessage	= self::MESSAGE_MOVE_FILE_FAILED.$filePath;
				return false;
			}
		}
		else $filePath = $this->upload($name);
		
		if (!$filePath)
			return false;
		
		// 获取图片信息
		$this->imageInfo = $imageInfo = getimagesize($filePath);
		
		if (!$imageInfo)
		{
			$this->errorType	= self::ERROR_IMAGE_FILE_INVALID;
			$this->errorMessage	= self::MESSAGE_IMAGE_FILE_INVALID;
			return false;
		}
		
		// 图片原始宽度和高度
		$width	= $imageInfo[0];
		$height	= $imageInfo[1];
		$type	= $imageInfo[2];
		
		// 创建来源图片
		switch ($type)
		{
			case self::IMAGE_TYPE_GIF:
				$srcImg = imagecreatefromgif($filePath);
				break;
				
			case self::IMAGE_TYPE_JPG:
				$srcImg = imagecreatefromjpeg($filePath);
				break;
				
			case self::IMAGE_TYPE_PNG:
				$srcImg = imagecreatefrompng($filePath);
				break;
				
			default:
				$this->errorType	= self::ERROR_IMAGE_TYPE_NOT_SUPPORTED;
				$this->errorMessage	= self::MESSAGE_IMAGE_TYPE_NOT_SUPPORTED.$this->saveExtension;
				return false;
		}
		
		// 裁剪图片
		if ($cropWidth > 0 && $cropHeight > 0)
		{
			// 裁剪缩放比例
			$ratio		= $width / $previewWidth;
			$cropX		= round($cropX * $ratio);
			$cropY		= round($cropY * $ratio);
			$cropWidth	= round($cropWidth * $ratio);
			$cropHeight	= round($cropHeight * $ratio);
			
			// 创建目标图片
			if ($type == self::IMAGE_TYPE_GIF)
				$dstImg = imagecreate($cropWidth, $cropHeight);
			else
				$dstImg = imagecreatetruecolor($cropWidth, $cropHeight);
			
			// 填充透明度
			if ($type == self::IMAGE_TYPE_PNG)
				imagefill($dstImg, 0, 0, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
			
			// 裁剪图片
			imagecopy($dstImg, $srcImg, 0, 0, $cropX, $cropY, $cropWidth, $cropHeight);
			
			// 保存透明度
			if ($type == self::IMAGE_TYPE_PNG)
				imagesavealpha($dstImg, true);
			
			// 销毁图片
			imagedestroy($srcImg);
			
			// 用裁剪后的图片作为来源图片
			$srcImg = $dstImg;
			
			// 更新宽度和高度
			$width	= $cropWidth;
			$height	= $cropHeight;
		}
		
		// 缩放	
		if ($maxWidth > 0 && $width > $maxWidth)
		{
			$scaleWidth		= $maxWidth;
			$scaleHeight	= round(($height / $width) * $scaleWidth);
			
			if ($maxHeight > 0 && $scaleHeight > $maxHeight)
			{
				$scaleHeight	= $maxHeight;
				$scaleWidth		= round(($width / $height) * $scaleHeight);
			}
		}
		else if ($maxHeight > 0 && $height > $maxHeight)
		{
			$scaleHeight	= $maxHeight;
			$scaleWidth		= round(($width / $height) * $scaleHeight);
			
			if ($maxWidth > 0 && $scaleWidth > $maxWidth)
			{
				$scaleWidth		= $maxWidth;
				$scaleHeight	= round(($height / $width) * $scaleWidth);
			}
		}
		else
		{
			$scaleWidth		= $width;
			$scaleHeight	= $height;
		}
		
		// 创建目标图片
		if ($type == self::IMAGE_TYPE_GIF)
			$dstImg = imagecreate($scaleWidth, $scaleHeight);
		else
			$dstImg = imagecreatetruecolor($scaleWidth, $scaleHeight);
		
		// 填充透明度
		if ($type == self::IMAGE_TYPE_PNG)
			imagefill($dstImg, 0, 0, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
		
		// 缩放图片
		imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $scaleWidth, $scaleHeight, $width, $height);
		
		// 保存透明度
		if ($type == self::IMAGE_TYPE_PNG)
			imagesavealpha($dstImg, true);
		
		// 销毁图片
		imagedestroy($srcImg);
		
		// 生成图片文件
		switch ($type)
		{
			case self::IMAGE_TYPE_GIF:
				imagegif($dstImg, $filePath);
				break;
				
			case self::IMAGE_TYPE_JPG:
				imagejpeg($dstImg, $filePath);
				break;
				
			case self::IMAGE_TYPE_PNG:
				imagepng($dstImg, $filePath);
				break;
		}
		
		// 销毁图片
		imagedestroy($dstImg);
		
		return $filePath;
	}
	
	/**
	 * 创建目录以及子目录，设置权限为可读写。
     * @param string $path
	 * @return bool
	 */
	protected function createDirectory($path)
	{
		if (!is_dir($path) && !mkdir($path, 0777, true))
		{
			$this->errorType	= self::ERROR_CREATE_DIRECTORY_FAILED;
			$this->errorMessage	= self::MESSAGE_CREATE_DIRECTORY_FAILED.$path;
			return false;
		}
		
		return true;
	}
}
