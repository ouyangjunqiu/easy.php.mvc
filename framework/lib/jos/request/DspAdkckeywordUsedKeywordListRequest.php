<?php
class DspAdkckeywordUsedKeywordListRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.dsp.adkckeyword.usedKeyword.list";
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
                                                        		                                    	                   			private $startDate;
    	                        
	public function setStartDate($startDate){
		$this->startDate = $startDate;
         $this->apiParas["startDate"] = $startDate;
	}

	public function getStartDate(){
	  return $this->startDate;
	}

                        	                   			private $endDate;
    	                        
	public function setEndDate($endDate){
		$this->endDate = $endDate;
         $this->apiParas["endDate"] = $endDate;
	}

	public function getEndDate(){
	  return $this->endDate;
	}

                        	                   			private $id;
    	                        
	public function setId($id){
		$this->id = $id;
         $this->apiParas["id"] = $id;
	}

	public function getId(){
	  return $this->id;
	}

                        	                   			private $campaignId;
    	                        
	public function setCampaignId($campaignId){
		$this->campaignId = $campaignId;
         $this->apiParas["campaignId"] = $campaignId;
	}

	public function getCampaignId(){
	  return $this->campaignId;
	}

                        	                   			private $platform;
    	                        
	public function setPlatform($platform){
		$this->platform = $platform;
         $this->apiParas["platform"] = $platform;
	}

	public function getPlatform(){
	  return $this->platform;
	}

                        	                   			private $valType;
    	                        
	public function setValType($valType){
		$this->valType = $valType;
         $this->apiParas["valType"] = $valType;
	}

	public function getValType(){
	  return $this->valType;
	}

                        	                   			private $isTodayOr15Days;
    	                        
	public function setIsTodayOr15Days($isTodayOr15Days){
		$this->isTodayOr15Days = $isTodayOr15Days;
         $this->apiParas["isTodayOr15Days"] = $isTodayOr15Days;
	}

	public function getIsTodayOr15Days(){
	  return $this->isTodayOr15Days;
	}

                        	                   			private $isOrderOrClick;
    	                        
	public function setIsOrderOrClick($isOrderOrClick){
		$this->isOrderOrClick = $isOrderOrClick;
         $this->apiParas["isOrderOrClick"] = $isOrderOrClick;
	}

	public function getIsOrderOrClick(){
	  return $this->isOrderOrClick;
	}

                        	                        	                        	                   			private $pageIndex;
    	                        
	public function setPageIndex($pageIndex){
		$this->pageIndex = $pageIndex;
         $this->apiParas["pageIndex"] = $pageIndex;
	}

	public function getPageIndex(){
	  return $this->pageIndex;
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





        
 

