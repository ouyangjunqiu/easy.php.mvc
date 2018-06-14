<?php
/**
 * 手机设备参数生成器（仅考虑Android系统）
 * 
 * @author Jack Chan
 * @since 2016-05-20
 */
class MobileDevice
{
	/**
	 * 移动网络运营商：中国移动
	 */
	const MNO_CMCC	= 0;
	
	/**
	 * 移动网络运营商：中国联通
	 */
	const MNO_CUCC	= 1;
	
	/**
	 * 移动网络运营商：中国电信
	 */
	const MNO_CTCC	= 2;
	
	/**
	 * 分辨率宽高分隔符
	 */
	const RESOLUTION_SEPARATOR = 'x';
	
	/**
	 * MNC（移动网络码）权重数组
	 */
	static $mncWeights = array(
		// 00,02:移动  01:联通  03:电信
		'00' => 5, '02' => 5, '01' => 6, '03' => 4
	);
	
	/**
	 * 手机号段分组
	 */
	static $mobileNumberGroups = array(
		// 移动
		self::MNO_CMCC => array(134, 135, 136, 137, 138, 139, 147, 150, 151, 152, 157, 158, 159, 178, 182, 183, 184, 187, 188),
		// 联通
		self::MNO_CUCC => array(130, 131, 132, 145, 155, 156, 176, 185, 186),
		// 电信
		self::MNO_CTCC => array(133, 153, 177, 180, 181, 189)
	);
	
	/**
	 * 手机号段权重数组
	 */
	static $mobileNumberWeights = array(
		'130' => 26, '131' => 32, '132' => 38, '133' => 44, '134' => 42, '135' => 84, '136' => 56, '137' => 70, '138' => 78, '139' => 72,
		'145' => 1, '147' => 5,
		'150' => 64, '151' => 66, '152' => 78, '153' => 34, '155' => 37, '156' => 34, '157' => 16, '158' => 82, '159' => 64,
		'176' => 2, '177' => 12, '178' => 2,
		'180' => 35, '181' => 32, '182' => 50, '183' => 38, '184' => 4, '185' => 22, '186' => 85, '187' => 48, '188' => 38, '189' => 51
	);
	
	/**
	 * 芯片型号权重数组
	 */
	static $chipModeWeights = array(
		'msm8974' => 10, 'msm8274' => 10, 'msm8674' => 10, 'MSM8916' => 10, 'MSM8630' => 10, 'MSM8974' => 10, 'MSM8974AB' => 10,
		'MSM8992' => 10, 'MSM8994' => 10, 'MSM8936' => 10, 'msm8228' => 10, 'msm8628' => 5, 'msm8674' => 5, 'msm8928' => 5,
		'msm8930' => 5, '8930AB' => 5, 'msm 8064T' => 5, 'msm8064M' => 5, 'MSM8939' => 5, 'APQ8064T' => 5, 'APQ8064' => 5,
		'MT6573' => 5, 'MT6577' => 5, 'MT6589' => 3, 'MT6592' => 15, 'MT6595' => 20, 'MT6795' => 25, 'MT6792' => 15,
		'Exynos 4210' => 15
	);
	
	/**
	 * Build版本号权重数组
	 */
	static $buildVersionWeights = array(
		'5.0-LRX21O' => 8, '5.0.1-LRX22C' => 8, '5.1.0-LMY47D' => 8, '5.0.2-LRX22L' => 8, '5.1.1-LMY47X' => 8,
		'4.4-KRT16M' => 10, '4.4.1-KOT49E' => 10, '4.4.2-KOT49H' => 10, '4.4.3-KTU84M' => 10, '4.4.4-KTU84P' => 10, '4.4.4_r2-KTU84Q' => 10,
		'4.3-JSS15J' => 7, '4.3_r1-JSS15Q' => 7, '4.3_r2-JSS15R' => 7, '4.3_r3-JWR66V' => 7, '4.3_r4-JWR66Y' => 7,
		'4.2-JOP40C' => 9, '4.2.2-JDQ39' => 9, '4.2.1-JOP40D' => 9,
		'4.1-JRN84D' => 9, '4.1.1-JRO03D' => 9, '4.1.2-JZO54K' => 9, '4.1.1_r2-JRO03E' => 9, '4.1.1_r4-JRO03L' => 9,
		'4.0.4-IMM76D' => 7, '4.0.3-IML74K' => 7
	);
	
	/**
	 * 分辨率权重数组
	 */
	static $resolutionWeights = array(
		'640x960' => 10, '640x1136' => 10, '720x1280' => 10, '320x480' => 10
	);
	
	/**
	 * 生成手机号码
     * @return string
	 */
	public static function createMobileNumber()
	{
		return RandomUtil::getRandomKeyByWeight(self::$mobileNumberWeights)
				.RandomUtil::createRandomString(8, RandomUtil::RANDOM_STRING_NUMBER);
	}
	
	/**
	 * 通过手机号段获取移动运营商类型
     * @param string $mobileNumber
	 * @return int 返回移动运营商类型（0为移动，1为联通，2为电信），手机号段无效则返回-1。
	 */
	public static function getMobileType($mobileNumber)
	{
		if (!is_numeric($mobileNumber) || strlen($mobileNumber) < 3)
			return -1;
		
		$mobileNumber = substr($mobileNumber, 0, 3);
		
		foreach(self::$mobileNumberGroups as $type => $group)
		{
			if (in_array($mobileNumber, $group))
				return $type;
		}
		
		return -1;
	}
	
	/**
	 * 生成Build版本号
     * @return string
	 */
	public static function createBuildVersion()
	{
		return RandomUtil::getRandomKeyByWeight(self::$buildVersionWeights);
	}
	
	/**
	 * 生成分辨率
	 * @param bool $returnArray 是否返回数组
	 * @return mixed 返回数组或字符串。如果返回数组，则元素0是宽，元素1是高。
	 */
	public static function createResolution($returnArray = false)
	{
		$resolution = RandomUtil::getRandomKeyByWeight(self::$resolutionWeights);
		return $returnArray ? explode(self::RESOLUTION_SEPARATOR, $resolution) : $resolution;
	}
	
	/**
	 * 生成IMEI（国际移动设备识别码，手机唯一识别码）
	 * IMEI由15位数字组成（TAC+FAC+SNR+SP）：
	 * 1、TAC（6位）：型号认证码，一般代表机型。
	 * 2、FAC（2位）：最终装配码，一般代表产地。
	 * 3、SNR（6位）：流水号，一般代表生产顺序号。
	 * 4、SP（1位）：校验码，目前暂备用。
     * @return string
	 */
	public static function createImei()
	{
		// 生成TAC
		$tacArray = array(mt_rand(352000, 385000), mt_rand(850000, 880000));
		$tac = RandomUtil::getRandomValue($tacArray);
		
		// 生成FAC
		$facArray = array(62, 37, 80, 81, 82, 83, 48, 92, 93, 63, 40, 48, 60);
		$fac = RandomUtil::getRandomValue($facArray);
		
		// 生成SNR
		$snr = str_pad(mt_rand(1999, 754999), 6, '0', STR_PAD_LEFT);
		
		// 生成SP
		$arr = str_split($tac.$fac.$snr);
		$sum = 0;
		
		for ($i = 0, $l = count($arr); $i < $l; $i++)
		{
			$a = $arr[$i++];
			$b = $arr[$i] * 2;
			$sum += $a + ($b < 10 ? $b : $b - 9);
		}
		
		$sp = $sum % 10;
		$sp = $sp == 0 ? 0 : 10 - $sp;
		
		return $tac.$fac.$snr.$sp;
	}
	
	/**
	 * 生成IMSI（国际移动用户识别码）
     * @return string
	 */
	public static function createImsi()
	{
		$mnc = RandomUtil::getRandomKeyByWeight(self::$mncWeights);
		$a = RandomUtil::createRandomNumber(5);
		$b = RandomUtil::createRandomNumber(5);
		
		return '460'.$mnc.$a.$b;
	}
	
	/**
	 * 生成安卓ID
     * @return string
	 */
	public static function createAndroidId()
	{
		// 此处用mt_rand函数max参数会溢出
		return dechex(rand(805306368, 4008636142)).dechex(rand(805306368, 4008636142));
	}

	/**
	 * 生成ICCID（IC卡识别码，固化在SIM卡中）
     * @return string
	 */
	public static function createIccid()
	{
		return \system\helpers\ICCIDHelper::generate();
	}
	
	/**
	 * 生成MAC地址
     * @return string
	 */
	public static function createMac()
	{
		$num = "";
		
		for ($i = 0; $i < 12; $i++)
		{
			if ($i % 2 == 0) $num .= ":";
			$num .= dechex(mt_rand(0, 15));
		}
		
		$num = substr($num, 1);
		return $num;
	}
	
	/**
	 * 生成芯片型号
     * @return string
	 */
	public static function createChipMode()
	{
		return RandomUtil::getRandomKeyByWeight(self::$chipModeWeights);
	}
	
	/**
	 * 生成唯一设备号（ro.serialno）
     * @return string
	 */
	public static function createSerialNo()
	{
		return RandomUtil::createRandomString(16, '0123456789ABCKLO0123456789FGHLU2012345678954TYPR0123456789543VJE');
	}
	
	/**
	 * 生成SSID（WIFI名称）
     * @return string
	 */
	public static function createSsid()
	{
		$array = array(
			"TP-LINK","china","MERCURY","tp-link","360wifi","cmcc","Chinanet",
			"free", 'my', 'Wifi', 'haha', '808', 'mianfei', 'Hiwifi', 'abcd', 'CMCC-AUTO',
			'Net', 'McDonald', 'KFC', '2015', 'Home', 'FM', 'apt', 'sky', 'Sun', '168', 'linksys',
			'000', 'hello', 'LAN', 'Cloud', 'Hotel', 'Cofee', 'VC', 'Pub', 'Love', 'linkme', 'con',
			'mima', 'six', 'HI', 'AK', '1', '2', '3', '4', '5', '123', '456'
		);
		
		// 是否为十六进制
		$hexFlag = RandomUtil::getRandomKeyByWeight(array(4, 1));
		
		// 随机字符串长度
		$n = RandomUtil::getRandomKeyByWeight(array(4 => 4, 6 => 1));
		
		return RandomUtil::getRandomValue($array).'_'.
				RandomUtil::createRandomString($n, $hexFlag ? RandomUtil::RANDOM_STRING_HEX : RandomUtil::RANDOM_STRING_NUMBER);
	}
	
	/**
	 * 生成基带版本
     * @return string
	 */
	public static function createBasebandVersion()
	{
		$str = 'LMNOPQRSTUVWXYZ';
		$str2 = 'PV';
		
		$l = strlen($str);
		$n = mt_rand(0, $l-1);
		$m = mt_rand(0, $l-1);
		$i = mt_rand(0, 1);
		$p = mt_rand(0, 9);
		
		$v = ($p > 5 ? $str[$n].$str[$m].'.'.$str2[$i] : '');
		$v .= mt_rand(0, 19).'.'.mt_rand(100, 9900).'.'.mt_rand(10, 98);
		
		return $v;
	}
}