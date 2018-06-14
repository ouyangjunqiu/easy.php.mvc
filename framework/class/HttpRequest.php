<?php
/**
 * HTTP主动请求类
 * @author Jack Chan
 * @since 2016-01-06
 * @deprecated
 * @see system\util\Curl
 */
class HttpRequest extends HttpBase
{
	/**
	 * 默认超时时间，单位为秒
	 */
	const DEFAULT_TIMEOUT	= 30;
	
	/********** 错误类型定义 **********/
	/**
	 * 请求错误
	 */
	const ERROR_REQUEST		= 20;
	
	/**
	 * 接口返回错误
	 */
	const ERROR_API_RETURN	= 21;
	
	/********** 错误消息前缀 **********/
	/**
	 * 发送HTTP请求错误消息前缀
	 */
	const MESSAGE_PREFIX_REQUEST	= 'Failed to send HTTP request: ';
	
	/**
	 * HTTP头部信息数组
	 */
	protected $headers = array();
	
	/**
	 * 代理主机
	 */
	protected $proxyHost;
	
	/**
	 * 代理端口
	 */
	protected $proxyPort;
	
	/**
	 * 代理授权用户名
	 */
	protected $proxyUsername;
	
	/**
	 * 代理授权密码
	 */
	protected $proxyPassword;
	
	/**
	 * SSL证书
	 */
	protected $sslCert;
	
	/**
	 * SSL密钥
	 */
	protected $sslKey;
	
	/**
	 */
	protected $rootCa;
	
	/**
	 * 超时时间，单位为秒
	 */
	protected $timeout = self::DEFAULT_TIMEOUT;
	
	/**
	 * 请求URL
	 */
	protected $requestUrl;
	
	/**
	 * POST数据
	 */
	protected $postData;
	
	/**
	 * 设置HTTP头部信息
     * @param string $name
     * @param string $value
	 */
	public function setHeader($name, $value)
	{
		$this->headers[$name] = $name.': '.$value;
	}
	
	/**
	 * 设置代理
     * @param string $proxyHost
     * @param string $proxyPort
     * @param null|string $proxyUsername
     * @param null|string $proxyPassword
	 */
	public function setProxy($proxyHost, $proxyPort, $proxyUsername = null, $proxyPassword = null)
	{
		$this->proxyHost		= $proxyHost;
		$this->proxyPort		= $proxyPort;
		$this->proxyUsername	= $proxyUsername;
		$this->proxyPassword	= $proxyPassword;
	}
	
	/**
	 * 设置HTTPS证书和密钥
     * @param string $sslCert
     * @param string $sslKey
     * @param string $rootCa
	 */
	public function setCert($sslCert, $sslKey, $rootCa)
	{
		$certDir = getcwd();
		
		if (strpos($sslCert, './') === 0)
			$sslCert = substr($sslCert, 1);
		
		if (strpos($sslKey, './') === 0)
			$sslKey = substr($sslKey, 1);
		
		if (strpos($rootCa, './') === 0)
			$rootCa = substr($rootCa, 1);
		
		$this->sslCert	= $certDir.$sslCert;
		$this->sslKey	= $certDir.$sslKey;
		$this->rootCa	= $certDir.$rootCa;
	}
	
	/**
	 * 设置超时时间
     * @param int $timeout
	 */
	public function setTimeout($timeout)
	{
		$this->timeout = $timeout;
	}
	
	/**
	 * 获取超时时间
	 */
	public function getTimeout()
	{
		return $this->timeout;
	}

	/**
	 * 获取请求URL
	 */
	public function getRequestUrl()
	{
		return $this->requestUrl;
	}
	
	/**
	 * 获取POST数据
	 */
	public function getPostData()
	{
		return $this->postData;
	}
	
	/**
	 * 发送HTTP请求，并解析响应内容为JSON对象。
     * @param string $url
     * @param mixed $postData
	 * @return  object|false JSON
	 */
	public function requestJson($url, $postData = null)
	{
		return $this->request($url, $postData, self::CONTENT_TYPE_JSON);
	}
	
	/**
	 * 发送HTTP请求，并解析响应内容为XML对象。
     * @param string $url
     * @param mixed $postData
	 * @return  object|false JSON
	 */
	public function requestXml($url, $postData = null)
	{
		return $this->request($url, $postData, self::CONTENT_TYPE_XML);
	}
	
	/**
	 * 发送HTTP请求，并解析响应内容。
     * @param string $url
     * @param mixed $postData
     * @param string $contentType
	 * @return object|false
	 */
	public function request($url, $postData = null, $contentType = null)
	{

		$this->requestUrl	= $url;
		$this->postData		= $postData;
		
		$result = $this->doRequest();
		
		if (!$result)
			return false;
		
		$result = $this->parseContent($contentType);

		if (!$result)
			return false;
		
		$this->errorType	= self::ERROR_NONE;
		$this->errorMessage	= null;
		return $result;
	}
	
	/**
	 * 发送HTTP请求
	 * @return bool
	 */
	protected function doRequest()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->requestUrl);

		if (strpos(strtolower($this->requestUrl), 'https') === 0)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		
		if ($this->proxyHost && $this->proxyPort)
		{
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_PROXY, $this->proxyHost);
			curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxyPort);
			
			if ($this->proxyUsername && $this->proxyPassword)
			{
				curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_PROXYUSERNAME, $this->proxyUsername);
				curl_setopt($ch, CURLOPT_PROXYPASSWORD, $this->proxyPassword);
			}
		}
		
		if ($this->sslCert)
		{
			curl_setopt($ch, CURLOPT_SSLCERT, $this->sslCert);
			curl_setopt($ch, CURLOPT_SSLKEY, $this->sslKey);
			curl_setopt($ch, CURLOPT_CAINFO, $this->rootCa);
		}
		
		if ($this->postData)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
		}
		
		if (!empty($this->headers))
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		
		$this->content = curl_exec($ch);
		
		// CURL错误
		if (curl_errno($ch))
		{
			$this->errorType 	= self::ERROR_REQUEST;
			$this->errorMessage = self::MESSAGE_PREFIX_REQUEST.curl_error($ch);
			curl_close($ch);
			return false;
		}
		
		curl_close($ch);
		return true;
	}
}