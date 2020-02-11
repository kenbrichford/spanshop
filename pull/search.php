<?php
include "pull/sortfilter.php";

class Search {
  public function __construct($market) {
    global $page, $safe_query, $category_array, $sort_array;

    $this->page = $page;
    if (!empty($category_array)) {
      $category = $category_array[$market];
    } else {
      $category = NULL;
    }
    $sort = $sort_array[$market];

    $url = $this->createUrl($safe_query, $category, $sort);
    $response = cacheData($url, $market);
    $this->results = $this->parseData($response);
  }
}

class EbaySearch extends Search {
  public function __construct() {
    parent::__construct("ebay");
  }

  protected function createUrl($query, $category, $sort) {
    $app_id = "YOUR EBAY APP ID HERE";
    $affiliate_id = "YOUR EBAY AFFILIATE ID HERE";
    $url = "http://svcs.ebay.com/services/search/FindingService/v1?";
    $url .= "OPERATION-NAME=findItemsAdvanced";
    $url .= "&RESPONSE-DATA-FORMAT=JSON";
    $url .= "&SERVICE-VERSION=1.0.0";
    $url .= "&SECURITY-APPNAME=$app_id";
    $url .= "&GLOBAL-ID=EBAY-US";
    if (isset($query)) {
    	$url .= "&keywords=$query";
    }
    if (isset($category)) {
    	foreach ($category as $key => $category_id) {
    		$url .= "&categoryId=$category_id";
    	}
    }
    $url .= "&sortOrder=$sort";
    $url .= "&paginationInput.entriesPerPage=12";
    $url .= "&paginationInput.pageNumber=$this->page";
    $url .= "&outputSelector=PictureURLLarge";
    $url .= "&affiliate.trackingId=$affiliate_id";
    $url .= "&affiliate.networkId=9";
    return $url;
  }

  protected function parseData($response) {
    global $total_results;
    $results = array();
    $response = $response->findItemsAdvancedResponse[0];
    $total_results["Ebay"] = (string)$response->paginationOutput[0]->totalEntries[0];
    if ($response->ack[0] == "Success" && $response->paginationOutput[0]->totalPages[0] >= $this->page) {
    	foreach($response->searchResult[0]->item as $item) {
    		if (isset($item->pictureURLLarge[0]) && $item->pictureURLLarge[0] != "") {
    			$picture = (string)$item->pictureURLLarge[0];
    		} elseif (isset($item->galleryURL[0]) && $item->galleryURL[0] != "") {
    			$picture = (string)$item->galleryURL[0];
    		} else {
    			$picture = "media/noimage.png";
    		}
        $price_string = $item->sellingStatus[0]->convertedCurrentPrice[0]->__value__;
    		$price = convertPriceString($price_string);
    		if (isset($item->shippingInfo[0]->shippingServiceCost[0])) {
          $shipping_string = $item->shippingInfo[0]->shippingServiceCost[0]->__value__;
    			if ($shipping_string == "0.0") {
    				$shipping = " + Free Shipping";
    			} else {
            $shipping_cost = convertPriceString($shipping_string);
    				$shipping = " + $shipping_cost Shipping";
    			}
    		} else {
    			$shipping = " + Variable Shipping";
    		}
    		$link = (string)$item->viewItemURL[0];
    		$title = (string)$item->title[0];
        if (isset($item->condition[0]->conditionDisplayName[0])) {
          $condition = (string)$item->condition[0]->conditionDisplayName[0];
        } else {
          $condition = "Unknown";
        }
    		$category = (string)$item->primaryCategory[0]->categoryName[0];
        $result_array = array(
          "link" => $link,
          "picture" => $picture,
          "title" => $title,
          "category" => $category,
          "condition" => $condition,
          "price" => $price,
          "shipping" => $shipping,
          "market" => "ebay"
        );
    		array_push($results, $result_array);
    	}
    }
    return $results;
  }
}

class WalmartSearch extends Search {
  public function __construct() {
    parent::__construct("walmart");
  }

  protected function createUrl($safe_query, $category, $sort) {
    $start = (($this->page - 1) * 12) + 1;
    $app_id = "YOUR WALMART APP ID HERE";
    $url = "https://api.walmartlabs.com/v1/search?";
    $url .= "apiKey=$app_id";
    if (isset($safe_query) && !empty($safe_query)) {
    	$url .= "&query=$safe_query";
    } else {
    	$url .= "&query=$category[1]";
    }
    if (isset($category) && !is_null($category)) {
    	$url .= "&categoryId=$category[0]";
    }
    $url .= "&start=$start";
    $url .= "&sort=$sort";
    if ($sort == "price") {
    	$url .= "&order=asc";
    }
    $url .= "&responseGroup=full";
    $url .= "&numItems=12";
    return $url;
  }

  protected function parseData($response) {
    global $total_results;
    $results = array();
    if ($response->totalResults == 0) {
    	$total_results["Walmart"] = (string)$response->numItems;
    } else {
      $total_results["Walmart"] = (string)$response->totalResults;
    }
    foreach ($response->items as $item) {
    	$picture = $item->largeImage;
      if (isset($item->salePrice)) {
        $price_string = $item->salePrice;
      	$price = convertPriceString($price_string);
        if (isset($item->standardShipRate) && $item->standardShipRate != "0.0") {
          $shipping_string = $item->standardShipRate;
          $shipping_cost = convertPriceString($shipping_string);
      		$shipping = " + $shipping_cost Shipping";
      	} else {
      		$shipping = " + Free Shipping";
      	}
      } else {
        $price = "Click for pricing";
      }
    	$link = str_replace("/|PUBID|", "", $item->productUrl);
    	$title = $item->name;
    	$category = explode("/", $item->categoryPath)[0];
    	$result_array = array(
        "link" => $link,
        "picture" => $picture,
        "title" => $title,
        "category" => $category,
        "condition" => "New",
        "price" => $price,
        "shipping" => $shipping,
        "market" => "walmart"
      );
      array_push($results, $result_array);
    }
    return $results;
  }
}

function getCategory($categories) {
  global $random_category;
  if ($random_category === True) {
    return array_rand($categories);
  } elseif (!isset($_GET["c"])) {
    return NULL;
  } elseif (array_key_exists(rawurldecode($_GET["c"]), $categories)) {
    return rawurldecode($_GET["c"]);
  }
}

function getPage() {
  if (isset($_GET["p"]) && is_numeric($_GET["p"])) {
    $page = $_GET["p"];
    if ($page < 1) {
      $page = 1;
    } elseif ($page > 5) {
      $page = 5;
    }
  } else {
    $page = 1;
  }
  return $page;
}

function getQuery() {
  if (isset($_GET["q"])) {
    return $_GET["q"];
  }
}

function getSort($sort_by) {
  if (isset($_GET["s"]) && array_key_exists($_GET["s"], $sort_by)) {
    $sort_string = rawurldecode($_GET["s"]);
  } else {
    $sort_string = "Relevance";
  }
  return $sort_string;
}

function cacheData($url, $market) {
  global $safe_query, $category_name, $sort_string, $page;
  $filename = "$sort_string-$page.json";
  if (!empty($category_name)) {
    $filename = urlencode($category_name) . "-$filename";
  }
  if (!empty($safe_query)) {
    $filename = urlencode($safe_query) . "-$filename";
  }
  $filename = strtolower("cache/$market-$filename");
  if (file_exists($filename) && filesize($filename)) {
    $update_time = filemtime($filename);
  } else {
    $update_time = 0;
  }
  $current_time = (int)$_SERVER["REQUEST_TIME"] - 3600;
  if ($update_time < $current_time) {
    $json = file_get_contents($url);
    $response = json_decode($json);
    file_put_contents($filename, $json);
  } else {
    $json = file_get_contents($filename);
    $response = json_decode($json);
  }
  return $response;
}

function convertPriceString($price_string) {
  return number_format(floatval($price_string), 2, ".", ",");
}

function sortResults($ebay_results, $walmart_results) {
  if (isset($_GET["s"]) && $_GET["s"] == "Price") {
  	$results = array_merge($ebay_results, $walmart_results);
  	usort($results, function($a, $b){
  		return $a["price"] - $b["price"];
  	});
  } else {
  	$result_count = array(count($ebay_results),count($walmart_results));
  	$results = array();
  	for ($i=0; $i<max($result_count); $i++) {
  		if (isset($ebay_results[$i])) {
  			array_push($results, $ebay_results[$i]);
  		}
  		if (isset($walmart_results[$i])) {
  			array_push($results, $walmart_results[$i]);
  		}
  	}
  }
  return $results;
}

$total_results = array();

$category_name = getCategory($categories);
if (!empty($category_name)) {
  $category_array = $categories[$category_name];
} else {
  $category_array = NULL;
}


$page = getPage();

$query = getQuery();
$safe_query = rawurlencode($query);

$sort_string = getSort($sort_by);
$sort_array = $sort_by[$sort_string];

$ebay = new EbaySearch();
$ebay_results = $ebay->results;

$walmart = new WalmartSearch();
$walmart_results = $walmart->results;

$results = sortResults($ebay_results, $walmart_results);
?>
