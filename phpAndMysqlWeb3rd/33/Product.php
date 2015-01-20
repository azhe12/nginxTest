<?php
// This class holds data about a single amazon product

// Most of the work is done by the libraries (simpleXML and nuSOAP) 
// and dealt with in the constructor for this class.

// This class' main purpose is to provide a common interface to the data from
// these two sources so all the display code can be common

class Product
{
  private $ASIN;
  private $productName;
  private $releaseDate;
  private $manufacturer;
  private $imageUrlMedium;
  private $imageUrlLarge;
  private $listPrice;
  private $ourPrice;
  private $salesRank;
  private $availability;
  private $avgCustomerRating;
  private $authors = array();
  private $reviews = array();
  private $similarProducts = array();
  private $soap; // array returned by SOAP calls

  function __construct($xml)
  {
    if(METHOD=='SOAP')
    {
      $this->ASIN = $xml['Asin'];
      $this->productName = $xml['ProductName'];
      if($xml['Authors'])
      {
        foreach($xml['Authors'] as $author)
        {
          $this->authors[] = $author;
        }
      }
      $this->releaseDate = $xml['ReleaseDate'];
      $this->manufacturer = $xml['Manufacturer'];
      $this->imageUrlMedium = $xml['ImageUrlMedium'];
      $this->imageUrlLarge = $xml['ImageUrlLarge'];

      $this->listPrice = $xml['ListPrice'];
      $this->listPrice = str_replace('$', '', $this->listPrice);
      $this->listPrice = str_replace(',', '', $this->listPrice);
      $this->listPrice = floatval($this->listPrice);

      $this->ourPrice = $xml['OurPrice'];
      $this->ourPrice = str_replace('$', '', $this->ourPrice);
      $this->ourPrice = str_replace(',', '', $this->ourPrice);
      $this->ourPrice = floatval($this->ourPrice);

      $this->salesRank = $xml['SalesRank'];
      $this->availability = $xml['Availability'];
      $this->avgCustomerRating = $xml['Reviews']['AvgCustomerRating'];
      $reviewCount = 0;
      if($xml['Reviews']['CustomerReviews'])
      {
        foreach ($xml['Reviews']['CustomerReviews'] as $review)
        {
          $this->reviews[$reviewCount]['rating'] = $review['Rating'];
          $this->reviews[$reviewCount]['summary'] = $review['Summary'];
          $this->reviews[$reviewCount]['comment'] = $review['Comment'];
          $reviewCount++;
        }
      }
      if($xml['SimilarProducts'])
      {
        foreach ($xml['SimilarProducts'] as $similar)
        {
          $this->similarProducts[] = $similar;
        }
      }
    }
    else // using REST
    {
      $this->ASIN = (string)$xml->Asin;
      $this->productName = (string)$xml->ProductName;
      if($xml->Authors->Author)
      {
        foreach($xml->Authors->Author as $author)
        {
          $this->authors[] = (string)$author;
        }
      }
      $this->releaseDate = (string)$xml->ReleaseDate;
      $this->manufacturer = (string)$xml->Manufacturer;
      $this->imageUrlMedium = (string)$xml->ImageUrlMedium;
      $this->imageUrlLarge = (string)$xml->ImageUrlLarge;

      $this->listPrice = (string)$xml->ListPrice;
      $this->listPrice = str_replace('$', '', $this->listPrice);
      $this->listPrice = str_replace(',', '', $this->listPrice);
      $this->listPrice = floatval($this->listPrice);

      $this->ourPrice = (string)$xml->OurPrice;
      $this->ourPrice = str_replace('$', '', $this->ourPrice);
      $this->ourPrice = str_replace(',', '', $this->ourPrice);
      $this->ourPrice = floatval($this->ourPrice);

      $this->salesRank = (string)$xml->SalesRank;
      $this->availability = (string)$xml->Availability;
      $this->avgCustomerRating = (float)$xml->Reviews->AvgCustomerRating;
      $reviewCount = 0;
      if($xml->Reviews->CustomerReview)
      {
        foreach ($xml->Reviews->CustomerReview as $review)
        {
          $this->reviews[$reviewCount]['rating'] = (float)$review->Rating;
          $this->reviews[$reviewCount]['summary'] = (string)$review->Summary;
          $this->reviews[$reviewCount]['comment'] = (string)$review->Comment;
          $reviewCount++;
        }
      }
      if($xml->SimilarProducts->Product)
      {
        foreach ($xml->SimilarProducts->Product as $similar)
        {
          $this->similarProducts[] = (string)$similar;
        }
      }

    }
  }

  // most methods in this class are similar
  // and just return the private variable
  function similarProductCount()
  {
    return count($this->similarProducts);
  }
  
  function similarProduct($i)
  {
    return $this->similarProducts[$i];
  }
  
  function customerReviewCount()
  {
    return count($this->reviews);
  }
  
  function customerReviewRating($i)
  {
    return $this->reviews[$i]['rating'];
  }

  function customerReviewSummary($i)
  {
    return $this->reviews[$i]['summary'];
  }

  function customerReviewComment($i)
  {
    return $this->reviews[$i]['comment'];
  }
    
  function valid()
  {
    if(isset($this->productName)&&($this->ourPrice>0.001)&&isset($this->ASIN))
      return true;
    else
      return false;
  }
  
  function ASIN()
  {
    return padASIN($this->ASIN);
  }
  
  function imageURLMedium()
  {
    return $this->imageUrlMedium;
  }
  
  function imageURLLarge()
  {
    return $this->imageUrlLarge;
  }
  
  function productName()
  {
    return $this->productName;
  }
  
  function ourPrice()
  {
    return number_format($this->ourPrice,2, '.', '');
  }
    
  function listPrice()
  {
    return number_format($this->listPrice,2, '.', '');
  }
    
  function authors()
  {
    if(isset($this->authors))
      return $this->authors;
    else
      return false;
  }

  function releaseDate()
  {
    if(isset($this->releaseDate))
      return $this->releaseDate;
    else
      return false;
  }  

  function avgCustomerRating()
  {
    if(isset($this->avgCustomerRating))
      return $this->avgCustomerRating;
    else
      return false;
  }

  function manufacturer()
  {
    if(isset($this->manufacturer))
      return $this->manufacturer;
    else
      return false;
  }

  function salesRank()
  {
    if(isset($this->salesRank))
      return $this->salesRank;
    else
      return false;
  }

  function availability()
  {
    if(isset($this->availability))
      return $this->availability;
    else
      return false;
  } 
}
?>