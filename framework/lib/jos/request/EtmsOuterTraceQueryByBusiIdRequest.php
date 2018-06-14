<?php
class EtmsOuterTraceQueryByBusiIdRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.etms.outerTrace.queryByBusiId";
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
                                    	                   			private $outerCode;
    	                        
	public function setOuterCode($outerCode){
		$this->outerCode = $outerCode;
         $this->apiParas["outerCode"] = $outerCode;
	}

	public function getOuterCode(){
	  return $this->outerCode;
	}

                        	                   			private $busiId;
    	                        
	public function setBusiId($busiId){
		$this->busiId = $busiId;
         $this->apiParas["busiId"] = $busiId;
	}

	public function getBusiId(){
	  return $this->busiId;
	}

}





        
 

