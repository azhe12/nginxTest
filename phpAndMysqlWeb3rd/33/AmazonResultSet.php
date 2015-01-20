<?php
// you can switch between REST and SOAP using this constant set in 
// constants.php
if(METHOD=='SOAP')
{
  include_once('nusoap/nusoap.php');
}

// This class stores the result of queries
// Usually this is 1 or 10 instances of the Product class
class AmazonResultSet
{
  private $browseNode;
  private $page;
  private $mode;
  private $url;
  private $type;
  private $totalResults;
  private $currentProduct = null;
  private $products = array(); // array of Product objects
      
  function products()
  {
    return $this->products;
  }

  function totalResults()
  {
    return $this->totalResults;
  }

  function getProduct($i)
  {
    if(isset($this->products[$i]))
      return $this->products[$i] ;
    else
      return false;
  }

  // Perform a query to get a page full of products from a browse node
  // Switch between XML/HTTP and SOAP in constants.php
  // Returns an array of Products
  function browseNodeSearch($browseNode, $page, $mode)
  {
    if(METHOD=='SOAP')
    {
      $soapclient = new soapclient(
            'http://soap.amazon.com/schemas2/AmazonWebServices.wsdl',
            'wsdl');
      $soap_proxy = $soapclient->getProxy();
      $parameters['mode']=$mode;
      $parameters['page']=$page;      
      $parameters['type']='heavy';
      $parameters['tag']=$this->assocID;
      $parameters['devtag']=$this->devTag;
      $parameters['sort']='+salesrank';
      $parameters['browse_node'] = $browseNode;
      
      // perform actual soap query
      $result = $soap_proxy->BrowseNodeSearchRequest($parameters);
      if(isSOAPError($result))
        return false;
      $this->totalResults = $result['TotalResults'];
      
      foreach($result['Details'] as $product)
      {
        $this->products[] = new Product($product);
      }
      unset($soapclient);
      unset($soap_proxy);
    }    
    else
    {
      // form URL and call parseXML to download and parse it
      $this->type = 'browse';
      $this->browseNode = $browseNode;
      $this->page = $page;
      $this->mode = $mode;
      $this->url = 'http://xml.amazon.com/onca/xml2?t='.ASSOCIATEID
                  .'&dev-t='.DEVTAG.'&BrowseNodeSearch='
                  .$this->browseNode.'&mode='.$this->mode
                  .'&type=heavy&page='.$this->page.'&sort=+salesrank&f=xml';
      $this->parseXML();
    }
    
    return $this->products;
  }
  
  // Given an ASIN, get the URL of the large image
  // Returns a string
  function getImageUrlLarge($ASIN, $mode)
  {
    foreach($this->products as $product)
    {
      if( $product->ASIN()== $ASIN)
        return  $product->imageURLLarge();
    }
    // if not found
    $this->ASINSearch($ASIN, $mode);
      return $this->products(0)->imageURLLarge();
  }
    
  // Perform a query to get a products with specified ASIN
  // Switch between XML/HTTP and SOAP in constants.php
  // Returns a Products object
  function ASINSearch($ASIN, $mode = 'books')
  {
    $this->type = 'ASIN';
    $this->ASIN=$ASIN;
    $this->mode = $mode;
    $ASIN = padASIN($ASIN);

    if(METHOD=='SOAP')
    {
      error_reporting(E_ALL & ~E_NOTICE);
      $soapclient = new soapclient (
            'http://soap.amazon.com/schemas2/AmazonWebServices.wsdl',
            'wsdl') ;
      $soap_proxy = $soapclient->getProxy();
      $parameters['asin']=$ASIN;
      $parameters['mode']=$mode;
      $parameters['type']="heavy";
      $parameters['tag']=$this->assocID;
      $parameters['devtag']=$this->devTag;
      
      // perform actual soap query
      
      $result = $soap_proxy->AsinSearchRequest($parameters);
      if(isSOAPError($result))
      {
        print_r($result);
        return false;
      }
      $this->products[0] = new Product($result['Details'][0]);
      $this->totalResults=1;
      unset($soapclient);
      unset($soap_proxy);
    }
    else
    {
      // form URL and call parseXML to download and parse it
      $this->url = 'http://xml.amazon.com/onca/xml2?t='.ASSOCIATEID
                    .'&dev-t='.DEVTAG.'&AsinSearch='
                    .$this->ASIN
                    .'&type=heavy&f=xml';
      $this->parseXML();
    }
    return $this->products[0];
   }
  
  // Perform a query to get a page full of products with a keyword search
  // Switch between XML/HTTP and SOAP in index.php
  // Returns an array of Products
  function keywordSearch($search, $page, $mode = 'books')
  {
    if(METHOD=='SOAP')
    {
      error_reporting(E_ALL & ~E_NOTICE);   
      $soapclient = new soapclient(
           'http://soap.amazon.com/schemas2/AmazonWebServices.wsdl','wsdl');
      $soap_proxy = $soapclient->getProxy();
      $parameters['mode']=$mode;
      $parameters['page']=$page;      
      $parameters['type']="heavy";
      $parameters['tag']=$this->assocID;
      $parameters['devtag']=$this->devTag;
      $parameters['sort']='+salesrank';
      $parameters['keyword'] = $search;

      // perform actual soap request
      $result = $soap_proxy->KeywordSearchRequest($parameters);
        
      if(isSOAPError($result) )
        return false;
      
      foreach($result['Details'] as $product)
      {
        $this->products[] = new Product($product);
      }
      $this->totalResults = $result['TotalResults'] ;
      unset($soapclient);
      unset($soap_proxy);
    }    
    else
    {
      $this->type = 'search';
      $this->search=$search;
      $this->page = $page;    
      $search = urlencode($search);
      $this->mode = $mode;
      $this->url = 'http://xml.amazon.com/onca/xml2?t='.ASSOCIATEID
                    .'&dev-t='.DEVTAG.'&KeywordSearch='
                    .$search.'&mode='.$this->mode
                    .'&type=heavy&page='
                    .$this->page
                    .'&sort=+salesrank&f=xml';
      $this->parseXML();
    }
    return $this->products;
  }
    
  // Parse the XML into Product object(s)
  function parseXML()
  {
    // suppress errors because this will fail sometimes
    $xml = @simplexml_load_file($this->url);
    if(!$xml)
    {
      //try a second time in case just server busy
      $xml = @simplexml_load_file($this->url);
      if(!$xml)
      {
        return false; 
      }
       
    }
    $this->totalResults = (integer)$xml->TotalResults;
    foreach($xml->Details as $productXML)
    {
      $this->products[] = new Product($productXML);
    }
  }
}
?>