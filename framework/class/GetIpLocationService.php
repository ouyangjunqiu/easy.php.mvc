<?php
/**
 * 淘宝接口请求类
 * @author Jack Chan
 * @since 2016-05-20
 */
class GetIpLocationService extends HttpRequest
{

	/**
	 * 淘宝api
	 */
	const TAOBAO_API 	= 'http://ip.taobao.com/service/getIpInfo.php?ip=';
	
	/**
	 *  添加删除群任务
     * @param string $ip
	 * @return NULL|string
	 */
	public function getLocation($ip)
	{
		$count = 0;
		//循环请求三次
		do 
		{
			$data = $this->requestJson(self::TAOBAO_API.$ip);
			
			if ($data)
				return $data;
		}
		while (++$count < 3);
		
		return  false;
	}


    /**
     * 解析淘宝接口返回的城市信息
     * @param mixed $json
     * @return null|string
     */
	public function getCity($json)
	{

		if(!empty($json->data) && $json->code == 0)
		{
			// 获取市
			$city 	  =	ucfirst(PinyinUtil::getPinyin(substr($json->data->city,0,strlen($json->data->city)-3)));
			if($city == 'Shen')
			{
				$city = 'Shenzhen';
			}
			// 获取省份
			$province =	ucfirst(PinyinUtil::getPinyin(substr($json->data->region,0,strlen($json->data->region)-3)));
		}
		
		return $province && $city ? 'CN_'.$province.'_'.$city :null;
	}
	

}