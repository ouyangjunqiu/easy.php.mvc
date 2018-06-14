<?php
class PopInvoiceOwnApplyRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.pop.invoice.own.apply";
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
                                                             	                        	                                                                                                                                                                                                                                                                                                                                                                                                       private $productId;
                              public function setProductId($productId ){
                 $this->productId=$productId;
                 $this->apiParas["product_id"] = $productId;
              }

              public function getProductId(){
              	return $this->productId;
              }
                                                                                                                                                                                                                                                                                                                                                                                                                                      private $productName;
                              public function setProductName($productName ){
                 $this->productName=$productName;
                 $this->apiParas["product_name"] = $productName;
              }

              public function getProductName(){
              	return $this->productName;
              }
                                                                                                                                                                                                                                                                                                                                              private $num;
                              public function setNum($num ){
                 $this->num=$num;
                 $this->apiParas["num"] = $num;
              }

              public function getNum(){
              	return $this->num;
              }
                                                                                                                                                                                                                                                                                                                                              private $price;
                              public function setPrice($price ){
                 $this->price=$price;
                 $this->apiParas["price"] = $price;
              }

              public function getPrice(){
              	return $this->price;
              }
                                                                                                                                                                                                                                                                                                                                              private $spec;
                              public function setSpec($spec ){
                 $this->spec=$spec;
                 $this->apiParas["spec"] = $spec;
              }

              public function getSpec(){
              	return $this->spec;
              }
                                                                                                                                                                                                                                                                                                                                              private $unit;
                              public function setUnit($unit ){
                 $this->unit=$unit;
                 $this->apiParas["unit"] = $unit;
              }

              public function getUnit(){
              	return $this->unit;
              }
                                                                                                                                                                                                                                                                                                                                                                                                                                      private $taxRate;
                              public function setTaxRate($taxRate ){
                 $this->taxRate=$taxRate;
                 $this->apiParas["tax_rate"] = $taxRate;
              }

              public function getTaxRate(){
              	return $this->taxRate;
              }
                                                                                                                                                            		                                    	                   			private $orderId;
    	                                                            
	public function setOrderId($orderId){
		$this->orderId = $orderId;
         $this->apiParas["order_id"] = $orderId;
	}

	public function getOrderId(){
	  return $this->orderId;
	}

                        	                        	                   			private $receiverTaxNo;
    	                                                                        
	public function setReceiverTaxNo($receiverTaxNo){
		$this->receiverTaxNo = $receiverTaxNo;
         $this->apiParas["receiver_tax_no"] = $receiverTaxNo;
	}

	public function getReceiverTaxNo(){
	  return $this->receiverTaxNo;
	}

                        	                   			private $receiverName;
    	                                                            
	public function setReceiverName($receiverName){
		$this->receiverName = $receiverName;
         $this->apiParas["receiver_name"] = $receiverName;
	}

	public function getReceiverName(){
	  return $this->receiverName;
	}

                        	                   			private $invoiceCode;
    	                                                            
	public function setInvoiceCode($invoiceCode){
		$this->invoiceCode = $invoiceCode;
         $this->apiParas["invoice_code"] = $invoiceCode;
	}

	public function getInvoiceCode(){
	  return $this->invoiceCode;
	}

                        	                   			private $invoiceNo;
    	                                                            
	public function setInvoiceNo($invoiceNo){
		$this->invoiceNo = $invoiceNo;
         $this->apiParas["invoice_no"] = $invoiceNo;
	}

	public function getInvoiceNo(){
	  return $this->invoiceNo;
	}

                        	                   			private $ivcTitle;
    	                                                            
	public function setIvcTitle($ivcTitle){
		$this->ivcTitle = $ivcTitle;
         $this->apiParas["ivc_title"] = $ivcTitle;
	}

	public function getIvcTitle(){
	  return $this->ivcTitle;
	}

                        	                   			private $totalPrice;
    	                                                            
	public function setTotalPrice($totalPrice){
		$this->totalPrice = $totalPrice;
         $this->apiParas["total_price"] = $totalPrice;
	}

	public function getTotalPrice(){
	  return $this->totalPrice;
	}

                        	                   			private $invoiceTime;
    	                                                            
	public function setInvoiceTime($invoiceTime){
		$this->invoiceTime = $invoiceTime;
         $this->apiParas["invoice_time"] = $invoiceTime;
	}

	public function getInvoiceTime(){
	  return $this->invoiceTime;
	}

                        	                   			private $pdfInfo;
    	                                                            
	public function setPdfInfo($pdfInfo){
		$this->pdfInfo = $pdfInfo;
         $this->apiParas["pdf_info"] = $pdfInfo;
	}

	public function getPdfInfo(){
	  return $this->pdfInfo;
	}

                            }





        
 

