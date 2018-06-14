<?php
class MiniwmsOcGoodsQuerySkuJdPriceRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.miniwms.oc.goods.querySkuJdPrice";
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
                                                        		                                    	                   			private $sku;
    	                        
	public function setSku($sku){
		$this->sku = $sku;
         $this->apiParas["sku"] = $sku;
	}

	public function getSku(){
	  return $this->sku;
	}

                        	                   			private $stationId;
    	                        
	public function setStationId($stationId){
		$this->stationId = $stationId;
         $this->apiParas["stationId"] = $stationId;
	}

	public function getStationId(){
	  return $this->stationId;
	}

                            }





        
 

