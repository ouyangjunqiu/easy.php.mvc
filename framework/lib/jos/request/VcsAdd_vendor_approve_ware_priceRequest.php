<?php
class VcsAdd_vendor_approve_ware_priceRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.vcs.add_vendor_approve_ware_price";
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
                                    	                                                 	                        	                                                                                                                                                                                                                                                                                                               private $wareId;
                              public function setWareId($wareId ){
                 $this->wareId=$wareId;
                 $this->apiParas["wareId"] = $wareId;
              }

              public function getWareId(){
              	return $this->wareId;
              }
                                                                                                                                                                                                                                                                                                                                              private $vendorCode;
                              public function setVendorCode($vendorCode ){
                 $this->vendorCode=$vendorCode;
                 $this->apiParas["vendorCode"] = $vendorCode;
              }

              public function getVendorCode(){
              	return $this->vendorCode;
              }
                                                                                                                                                                                                                                                                                                                                              private $companyID;
                              public function setCompanyID($companyID ){
                 $this->companyID=$companyID;
                 $this->apiParas["companyID"] = $companyID;
              }

              public function getCompanyID(){
              	return $this->companyID;
              }
                                                                                                                                                                                                                                                                                                                                              private $currency;
                              public function setCurrency($currency ){
                 $this->currency=$currency;
                 $this->apiParas["currency"] = $currency;
              }

              public function getCurrency(){
              	return $this->currency;
              }
                                                                                                                                                                                                                                                                                                                                              private $priceType;
                              public function setPriceType($priceType ){
                 $this->priceType=$priceType;
                 $this->apiParas["priceType"] = $priceType;
              }

              public function getPriceType(){
              	return $this->priceType;
              }
                                                                                                                                                                                                                                                                                                                                              private $price;
                              public function setPrice($price ){
                 $this->price=$price;
                 $this->apiParas["price"] = $price;
              }

              public function getPrice(){
              	return $this->price;
              }
                                                                                                                                                                                                                                                                                                                                              private $inputModel;
                              public function setInputModel($inputModel ){
                 $this->inputModel=$inputModel;
                 $this->apiParas["inputModel"] = $inputModel;
              }

              public function getInputModel(){
              	return $this->inputModel;
              }
                                                                                                                                                                                                                                                                                                                                              private $publicPrice;
                              public function setPublicPrice($publicPrice ){
                 $this->publicPrice=$publicPrice;
                 $this->apiParas["publicPrice"] = $publicPrice;
              }

              public function getPublicPrice(){
              	return $this->publicPrice;
              }
                                                                                                                                                                                                                                                                                                                                              private $discount;
                              public function setDiscount($discount ){
                 $this->discount=$discount;
                 $this->apiParas["discount"] = $discount;
              }

              public function getDiscount(){
              	return $this->discount;
              }
                                                                                                                                                                                                                                                                                                                                              private $startTime;
                              public function setStartTime($startTime ){
                 $this->startTime=$startTime;
                 $this->apiParas["startTime"] = $startTime;
              }

              public function getStartTime(){
              	return $this->startTime;
              }
                                                                                                                                                                                                                                                                                                                                              private $endTime;
                              public function setEndTime($endTime ){
                 $this->endTime=$endTime;
                 $this->apiParas["endTime"] = $endTime;
              }

              public function getEndTime(){
              	return $this->endTime;
              }
                                                                                                                                                                                                                                                                                                                                              private $changeReason;
                              public function setChangeReason($changeReason ){
                 $this->changeReason=$changeReason;
                 $this->apiParas["changeReason"] = $changeReason;
              }

              public function getChangeReason(){
              	return $this->changeReason;
              }
                                                                                                                }





        
 

