<?php
class UnionThemeGoodsServiceQueryCouponGoodsRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.UnionThemeGoodsService.queryCouponGoods";
	}
	
	public function getApiParas(){
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
	public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
                                    	                        	                   			private $from;
    	                        
	public function setFrom($from){
		$this->from = $from;
         $this->apiParas["from"] = $from;
	}

	public function getFrom(){
	  return $this->from;
	}

                        	                   			private $pageSize;
    	                        
	public function setPageSize($pageSize){
		$this->pageSize = $pageSize;
         $this->apiParas["pageSize"] = $pageSize;
	}

	public function getPageSize(){
	  return $this->pageSize;
	}

}





        
 

