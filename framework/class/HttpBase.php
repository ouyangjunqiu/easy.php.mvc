<?php
/**
 * HTTP基础类
 * @author Jack Chan
 * @since 2016-01-06
 * @deprecated
 * @see system\util\Curl
 */
class HttpBase
{
	/********** 内容类型定义 **********/
	/**
	 * 纯文本类型
	 */
	const CONTENT_TYPE_PLAIN	= 0;
	
	/**
	 * JSON类型
	 */
	const CONTENT_TYPE_JSON		= 1;
	
	/**
	 * XML类型
	 */
	const CONTENT_TYPE_XML		= 2;
	
	/********** 错误类型定义 **********/
	/**
	 * 无任何错误
	 */
	const ERROR_NONE			= 0;
	
	/**
	 * 解析JSON错误
	 */
	const ERROR_PARSE_JSON		= 1;
	
	/**
	 * 解析XML错误
	 */
	const ERROR_PARSE_XML		= 2;
	
	/********** 错误消息前缀 **********/
	/**
	 * 解析JSON错误消息前缀
	 */
	const MESSAGE_PREFIX_PARSE_JSON	= 'Cannot decode content as JSON: ';
	
	/**
	 * 解析XML错误消息前缀
	 */
	const MESSAGE_PREFIX_PARSE_XML	= 'Cannot decode content as XML: ';
	
	/**
	 * 内容类型
	 */
	protected $contentType = self::CONTENT_TYPE_PLAIN;
	
	/**
	 * 输入或响应内容
	 */
	protected $content;
	
	/**
	 * 内容解析后的JSON对象
	 */
	protected $jsonObject;
	
	/**
	 * 内容解析后的XML对象
	 */
	protected $xmlObject;
	
	/**
	 * 错误类型
	 */
	protected $errorType;
	
	/**
	 * 错误消息
	 */
	protected $errorMessage;
	
	/**
	 * 获取内容类型
	 */
	public function getContentType()
	{
		return $this->contentType;
	}
	
	/**
	 * 获取输入或响应内容
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * 获取内容解析后的JSON对象
	 */
	public function getJsonObject()
	{
		return $this->jsonObject;
	}
	
	/**
	 * 获取内容解析后的XML对象
	 */
	public function getXmlObject()
	{
		return $this->xmlObject;
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
	 * 解析输入或响应内容
     * @param string $contentType
	 * @return bool
	 */
	protected function parseContent($contentType)
	{
		switch ($contentType)
		{
			case self::CONTENT_TYPE_JSON:
				return $this->parseJsonContent();
				
			case self::CONTENT_TYPE_XML:
				return $this->parseXmlContent();
				
			default:
				return $this->parsePlainContent();
		}
	}
	
	/**
	 * 解析纯文本内容，子类可以覆盖此方法完成特定文本格式解析。
	 * @return string 返回响应内容，不做任何解析。
	 */
	protected function parsePlainContent()
	{
		$this->contentType = self::CONTENT_TYPE_PLAIN;
		return $this->content;
	}
	
	/**
	 * 解析JSON内容
	 * @return  object|false JSON
	 */
	protected function parseJsonContent()
	{
		$this->contentType = self::CONTENT_TYPE_JSON;
		$this->jsonObject = json_decode($this->content);
		
		// JSON解析失败
		if (!$this->jsonObject)
		{
			$error = error_get_last();
			$this->errorType 	= self::ERROR_PARSE_JSON;
			$this->errorMessage = self::MESSAGE_PREFIX_PARSE_JSON.$error['message'];
			return false;
		}
		
		return $this->jsonObject;
	}
	
	/**
	 * 解析XML内容
	 * @return  object|false XML
	 */
	protected function parseXmlContent()
	{
		$this->contentType = self::CONTENT_TYPE_XML;
		$this->xmlObject = simplexml_load_string($this->content);
		
		// XML解析失败
		if (!$this->xmlObject)
		{
			$error = error_get_last();
			$this->errorType 	= self::ERROR_PARSE_XML;
			$this->errorMessage = self::MESSAGE_PREFIX_PARSE_XML.$error['message'];
			return false;
		}
		
		return $this->xmlObject;
	}
}