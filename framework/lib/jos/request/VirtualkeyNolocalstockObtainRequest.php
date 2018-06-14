<?php
class VirtualkeyNolocalstockObtainRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.virtualkey.nolocalstock.obtain";
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
                                    	                                            		                                    	                   			private $resultCode;
    	                        
	public function setResultCode($resultCode){
		$this->resultCode = $resultCode;
         $this->apiParas["resultCode"] = $resultCode;
	}

	public function getResultCode(){
	  return $this->resultCode;
	}

                        	                   			private $resultMsg;
    	                        
	public function setResultMsg($resultMsg){
		$this->resultMsg = $resultMsg;
         $this->apiParas["resultMsg"] = $resultMsg;
	}

	public function getResultMsg(){
	  return $this->resultMsg;
	}

                        	                   			private $returnTime;
    	                        
	public function setReturnTime($returnTime){
		$this->returnTime = $returnTime;
         $this->apiParas["returnTime"] = $returnTime;
	}

	public function getReturnTime(){
	  return $this->returnTime;
	}

                        	                   			private $hostTxId;
    	                        
	public function setHostTxId($hostTxId){
		$this->hostTxId = $hostTxId;
         $this->apiParas["hostTxId"] = $hostTxId;
	}

	public function getHostTxId(){
	  return $this->hostTxId;
	}

                                            		                                    	                   			private $id;
    	                        
	public function setId($id){
		$this->id = $id;
         $this->apiParas["id"] = $id;
	}

	public function getId(){
	  return $this->id;
	}

                        	                   			private $orderId;
    	                        
	public function setOrderId($orderId){
		$this->orderId = $orderId;
         $this->apiParas["orderId"] = $orderId;
	}

	public function getOrderId(){
	  return $this->orderId;
	}

                        	                   			private $facilitatorCode;
    	                        
	public function setFacilitatorCode($facilitatorCode){
		$this->facilitatorCode = $facilitatorCode;
         $this->apiParas["facilitatorCode"] = $facilitatorCode;
	}

	public function getFacilitatorCode(){
	  return $this->facilitatorCode;
	}

                                                 	                        	                                                                                                                                                                                                                                                                                                               private $uniqueId;
                              public function setUniqueId($uniqueId ){
                 $this->uniqueId=$uniqueId;
                 $this->apiParas["uniqueId"] = $uniqueId;
              }

              public function getUniqueId(){
              	return $this->uniqueId;
              }
                                                                                                                                                                                                                                                                                                                                              private $platformId;
                              public function setPlatformId($platformId ){
                 $this->platformId=$platformId;
                 $this->apiParas["platformId"] = $platformId;
              }

              public function getPlatformId(){
              	return $this->platformId;
              }
                                                                                                                                                                                                                                                                                                                                              private $facilitatorSkuId;
                              public function setFacilitatorSkuId($facilitatorSkuId ){
                 $this->facilitatorSkuId=$facilitatorSkuId;
                 $this->apiParas["facilitatorSkuId"] = $facilitatorSkuId;
              }

              public function getFacilitatorSkuId(){
              	return $this->facilitatorSkuId;
              }
                                                                                                                                                                                                                                                                                                                                              private $keySerialNum;
                              public function setKeySerialNum($keySerialNum ){
                 $this->keySerialNum=$keySerialNum;
                 $this->apiParas["keySerialNum"] = $keySerialNum;
              }

              public function getKeySerialNum(){
              	return $this->keySerialNum;
              }
                                                                                                                                                                                                                                                                                                                                              private $keySerialPass;
                              public function setKeySerialPass($keySerialPass ){
                 $this->keySerialPass=$keySerialPass;
                 $this->apiParas["keySerialPass"] = $keySerialPass;
              }

              public function getKeySerialPass(){
              	return $this->keySerialPass;
              }
                                                                                                                                                                                                                                                                                                                                              private $expiryDate;
                              public function setExpiryDate($expiryDate ){
                 $this->expiryDate=$expiryDate;
                 $this->apiParas["expiryDate"] = $expiryDate;
              }

              public function getExpiryDate(){
              	return $this->expiryDate;
              }
                                                                                                                                                                                                                                                                                                                                              private $softWareDownloadUrl;
                              public function setSoftWareDownloadUrl($softWareDownloadUrl ){
                 $this->softWareDownloadUrl=$softWareDownloadUrl;
                 $this->apiParas["softWareDownloadUrl"] = $softWareDownloadUrl;
              }

              public function getSoftWareDownloadUrl(){
              	return $this->softWareDownloadUrl;
              }
                                                                                                                                                                        }





        
 

