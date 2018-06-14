<?php
/**
 * HTTP被动服务类
 * @author Jack Chan
 * @since 2016-01-15
 */
class HttpServe extends HttpBase
{
	/********** 错误类型定义 **********/
	/**
	 * 接收输入内容错误
	 */
	const ERROR_RECEIVE		= 10;
	
	/********** 错误消息前缀 **********/
	/**
	 * 接收输入内容错误消息前缀
	 */
	const MESSAGE_PREFIX_RECEIVE	= 'Failed to receive input content: ';
	
	/**
	 * 接收输入数据，并解析输入内容为JSON对象。
	 * @return  object|false JSON
	 */
	public function receiveJson()
	{
		return $this->receive(self::CONTENT_TYPE_JSON);
	}
	
	/**
	 * 接收输入数据，并解析输入内容为XML对象。
	 * @return  object|false JSON
	 */
	public function receiveXml()
	{
		return $this->receive(self::CONTENT_TYPE_XML);
	}
	
	/**
	 * 接收输入数据，并解析输入内容。
     * @param string $inputContentType
	 * @return object|false
	 */
	public function receive($inputContentType = null)
	{
		$result = $this->doReceive();
		
		if (!$result)
			return false;
		
		$result = $this->parseContent($inputContentType);
		
		if (!$result)
			return false;
		
		$this->errorType	= self::ERROR_NONE;
		$this->errorMessage	= null;
		return $result;
	}
	
	/**
	 * 获取输入内容
	 * @return bool
	 */
	protected function doReceive()
	{
		$this->content = file_get_contents('php://input');
		
		if ($this->content === false)
		{
			$error = error_get_last();
			$this->errorType	= self::ERROR_RECEIVE;
			$this->errorMessage	= self::MESSAGE_PREFIX_RECEIVE.$error['message'];
			return false;
		}
		
		return true;
	}
}
