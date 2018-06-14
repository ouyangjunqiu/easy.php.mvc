<?php
class JcloudWmsMasterSupplierCreateRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.jcloud.wms.master.supplier.create";
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
                                    	                        	                                            		                                    	                   			private $tenantId;
    	                        
	public function setTenantId($tenantId){
		$this->tenantId = $tenantId;
         $this->apiParas["tenantId"] = $tenantId;
	}

	public function getTenantId(){
	  return $this->tenantId;
	}

                        	                   			private $supplierNo;
    	                        
	public function setSupplierNo($supplierNo){
		$this->supplierNo = $supplierNo;
         $this->apiParas["supplierNo"] = $supplierNo;
	}

	public function getSupplierNo(){
	  return $this->supplierNo;
	}

                        	                   			private $supplierName;
    	                        
	public function setSupplierName($supplierName){
		$this->supplierName = $supplierName;
         $this->apiParas["supplierName"] = $supplierName;
	}

	public function getSupplierName(){
	  return $this->supplierName;
	}

                        	                   			private $contact;
    	                        
	public function setContact($contact){
		$this->contact = $contact;
         $this->apiParas["contact"] = $contact;
	}

	public function getContact(){
	  return $this->contact;
	}

                        	                   			private $contactMobile;
    	                        
	public function setContactMobile($contactMobile){
		$this->contactMobile = $contactMobile;
         $this->apiParas["contactMobile"] = $contactMobile;
	}

	public function getContactMobile(){
	  return $this->contactMobile;
	}

                        	                   			private $contactPhone;
    	                        
	public function setContactPhone($contactPhone){
		$this->contactPhone = $contactPhone;
         $this->apiParas["contactPhone"] = $contactPhone;
	}

	public function getContactPhone(){
	  return $this->contactPhone;
	}

                        	                   			private $province;
    	                        
	public function setProvince($province){
		$this->province = $province;
         $this->apiParas["province"] = $province;
	}

	public function getProvince(){
	  return $this->province;
	}

                        	                   			private $city;
    	                        
	public function setCity($city){
		$this->city = $city;
         $this->apiParas["city"] = $city;
	}

	public function getCity(){
	  return $this->city;
	}

                        	                   			private $district;
    	                        
	public function setDistrict($district){
		$this->district = $district;
         $this->apiParas["district"] = $district;
	}

	public function getDistrict(){
	  return $this->district;
	}

                        	                   			private $address;
    	                        
	public function setAddress($address){
		$this->address = $address;
         $this->apiParas["address"] = $address;
	}

	public function getAddress(){
	  return $this->address;
	}

                        	                   			private $contactEmail;
    	                        
	public function setContactEmail($contactEmail){
		$this->contactEmail = $contactEmail;
         $this->apiParas["contactEmail"] = $contactEmail;
	}

	public function getContactEmail(){
	  return $this->contactEmail;
	}

                        	                   			private $returnAddress;
    	                        
	public function setReturnAddress($returnAddress){
		$this->returnAddress = $returnAddress;
         $this->apiParas["returnAddress"] = $returnAddress;
	}

	public function getReturnAddress(){
	  return $this->returnAddress;
	}

                        	                   			private $memo;
    	                        
	public function setMemo($memo){
		$this->memo = $memo;
         $this->apiParas["memo"] = $memo;
	}

	public function getMemo(){
	  return $this->memo;
	}

                        	                   			private $operateUser;
    	                        
	public function setOperateUser($operateUser){
		$this->operateUser = $operateUser;
         $this->apiParas["operateUser"] = $operateUser;
	}

	public function getOperateUser(){
	  return $this->operateUser;
	}

                        	                   			private $operateTime;
    	                        
	public function setOperateTime($operateTime){
		$this->operateTime = $operateTime;
         $this->apiParas["operateTime"] = $operateTime;
	}

	public function getOperateTime(){
	  return $this->operateTime;
	}

                            }





        
 

