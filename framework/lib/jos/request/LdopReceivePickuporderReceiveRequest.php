<?php
class LdopReceivePickuporderReceiveRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.ldop.receive.pickuporder.receive";
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
                                                        		                                    	                   			private $pickupAddress;
    	                        
	public function setPickupAddress($pickupAddress){
		$this->pickupAddress = $pickupAddress;
         $this->apiParas["pickupAddress"] = $pickupAddress;
	}

	public function getPickupAddress(){
	  return $this->pickupAddress;
	}

                        	                   			private $pickupName;
    	                        
	public function setPickupName($pickupName){
		$this->pickupName = $pickupName;
         $this->apiParas["pickupName"] = $pickupName;
	}

	public function getPickupName(){
	  return $this->pickupName;
	}

                        	                   			private $pickupTel;
    	                        
	public function setPickupTel($pickupTel){
		$this->pickupTel = $pickupTel;
         $this->apiParas["pickupTel"] = $pickupTel;
	}

	public function getPickupTel(){
	  return $this->pickupTel;
	}

                        	                   			private $customerTel;
    	                        
	public function setCustomerTel($customerTel){
		$this->customerTel = $customerTel;
         $this->apiParas["customerTel"] = $customerTel;
	}

	public function getCustomerTel(){
	  return $this->customerTel;
	}

                        	                   			private $customerCode;
    	                        
	public function setCustomerCode($customerCode){
		$this->customerCode = $customerCode;
         $this->apiParas["customerCode"] = $customerCode;
	}

	public function getCustomerCode(){
	  return $this->customerCode;
	}

                        	                   			private $backAddress;
    	                        
	public function setBackAddress($backAddress){
		$this->backAddress = $backAddress;
         $this->apiParas["backAddress"] = $backAddress;
	}

	public function getBackAddress(){
	  return $this->backAddress;
	}

                        	                   			private $customerContract;
    	                        
	public function setCustomerContract($customerContract){
		$this->customerContract = $customerContract;
         $this->apiParas["customerContract"] = $customerContract;
	}

	public function getCustomerContract(){
	  return $this->customerContract;
	}

                        	                   			private $desp;
    	                        
	public function setDesp($desp){
		$this->desp = $desp;
         $this->apiParas["desp"] = $desp;
	}

	public function getDesp(){
	  return $this->desp;
	}

                        	                   			private $orderId;
    	                        
	public function setOrderId($orderId){
		$this->orderId = $orderId;
         $this->apiParas["orderId"] = $orderId;
	}

	public function getOrderId(){
	  return $this->orderId;
	}

                        	                   			private $weight;
    	                        
	public function setWeight($weight){
		$this->weight = $weight;
         $this->apiParas["weight"] = $weight;
	}

	public function getWeight(){
	  return $this->weight;
	}

                        	                   			private $remark;
    	                        
	public function setRemark($remark){
		$this->remark = $remark;
         $this->apiParas["remark"] = $remark;
	}

	public function getRemark(){
	  return $this->remark;
	}

                        	                   			private $volume;
    	                        
	public function setVolume($volume){
		$this->volume = $volume;
         $this->apiParas["volume"] = $volume;
	}

	public function getVolume(){
	  return $this->volume;
	}

                            }





        
 

