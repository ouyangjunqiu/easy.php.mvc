<?php
class EclpIbAddOutsideMainRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.eclp.ib.addOutsideMain";
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
                                                        		                                    	                   			private $deptId;
    	                        
	public function setDeptId($deptId){
		$this->deptId = $deptId;
         $this->apiParas["deptId"] = $deptId;
	}

	public function getDeptId(){
	  return $this->deptId;
	}

                        	                   			private $isvOutsideNo;
    	                        
	public function setIsvOutsideNo($isvOutsideNo){
		$this->isvOutsideNo = $isvOutsideNo;
         $this->apiParas["isvOutsideNo"] = $isvOutsideNo;
	}

	public function getIsvOutsideNo(){
	  return $this->isvOutsideNo;
	}

                        	                   			private $warehouseIdOut;
    	                        
	public function setWarehouseIdOut($warehouseIdOut){
		$this->warehouseIdOut = $warehouseIdOut;
         $this->apiParas["warehouseIdOut"] = $warehouseIdOut;
	}

	public function getWarehouseIdOut(){
	  return $this->warehouseIdOut;
	}

                        	                   			private $warehouseIdIn;
    	                        
	public function setWarehouseIdIn($warehouseIdIn){
		$this->warehouseIdIn = $warehouseIdIn;
         $this->apiParas["warehouseIdIn"] = $warehouseIdIn;
	}

	public function getWarehouseIdIn(){
	  return $this->warehouseIdIn;
	}

                        	                   			private $shipperId;
    	                        
	public function setShipperId($shipperId){
		$this->shipperId = $shipperId;
         $this->apiParas["shipperId"] = $shipperId;
	}

	public function getShipperId(){
	  return $this->shipperId;
	}

                        	                   			private $skus;
    	                        
	public function setSkus($skus){
		$this->skus = $skus;
         $this->apiParas["skus"] = $skus;
	}

	public function getSkus(){
	  return $this->skus;
	}

                                                    	}





        
 

