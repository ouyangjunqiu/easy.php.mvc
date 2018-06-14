<?php
/**
 * URL工具
 * 
 * @author Jack Chan
 * @since 2016-05-18
 * @deprecated
 * @see system\util\Url
 */
class UrlUtil
{
	/**
	 * 获取站点URL，包含协议、域名以及端口，不包含路径。
     * @return string
	 */
	public static function getSiteUrl()
	{
		$scheme	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
		$host	= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];
		$port	= isset($_SERVER['HTTP_HOST']) || $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];
		
		return $scheme.$host.$port;
	}
	
	/**
	 * 获取当前页面的完整路径，包括协议、域名、端口和路径。
     * @return string
	 */
	public static function getFullPath()
	{
		return self::getSiteUrl().$_SERVER['SCRIPT_NAME'];
	}
	
	/**
	 * 获取当前页面的完整URL，包括协议、域名、端口、路径和查询字符串。
     * @return string
	 */
	public static function getFullUrl()
	{
		return self::getSiteUrl().$_SERVER['REQUEST_URI'];
	}

    /**
     * 获取绝对路径
     * @param string $url 需要转换的URL
     * @param mixed $referenceUrl 参照URL
     * @return string
     */
	public static function getAbsoluteUrl($url, $referenceUrl)
	{
		$portions	= parse_url($referenceUrl);
		$scheme		= isset($portions['scheme']) ? $portions['scheme'] : 'http';
		
		if (strpos($url, $scheme) !== 0)
		{
			$upass	= isset($portions['user']) ? $portions['user'].(isset($portions['pass']) ? ':'.$portions['pass'] : null).'@' : null;
			$host	= isset($portions['host']) ? $portions['host'] : 'localhost';
			$port	= isset($portions['port']) ? ':'.$portions['port'] : null;
			$base	= $scheme.'://'.$upass.$host.$port;
			
			if (isset($portions['path']))
			{
				$path = $portions['path'];
				
				if (($pos = strrpos($path, '/')) !== false)
					$path = substr($path, 0, $pos);
				
				if (substr($path, 0, 1) != '/')
					$path = '/'.$path;
			}
			else $path = '/';
			
			if (substr($url, 0, 1) == '/')
			{
				$path	= '/';
				$url	= substr($url, 1);
			}
			else if (substr($url, 0, 2) == './')
				$url = substr($url, 2);
			else
			{
				while (substr($url, 0, 3) == '../')
				{
					$path	= substr($path, 0, strrpos($path, '/'));
					$url	= substr($url, 3);
				}
			}
			
			$url = $base.($path == '/' ? null : $path).'/'.$url;
		}
		
		return $url;
	}
}