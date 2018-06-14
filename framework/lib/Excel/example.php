<?php	
	

	$data[0] = array(
		'date'			=> '2016-09-17 12:14:10',
		'goodsname'		=> '金装三段',
		'money'			=> '168'	
	);
	function createExcel($data)
	{	

		$excel_data = "日期\名称\价格\n";

		include_once './reader.php';
		error_reporting(E_ALL ^ E_NOTICE);
		set_time_limit(0);
		
		$file = date('YmdHis', time()).'.xls';
		foreach($data as $v) 
		{

			$excel_data .= "\"{$v['date']}\"\t";
			$excel_data .= "\"{$v['goodsname']}\"\t";
			$excel_data .= "\"{$v['money']}\"\t";
			$excel_data .= "\n";	
		}
		
		$excel_data .= "\n";
		
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=$file");
		echo $excel_data;
		exit;		
		
	}

	createExcel($data);
?>