<?php
require_once('lib/OAuth.php');
class Library_Yelp
{
  protected $CONSUMER_KEY = 'C_QH9jARKrmuQG5BqpW16g';
  protected $CONSUMER_SECRET = 'gC9Js9SYXt5rriLiN6CatLv1riM=';
  protected $TOKEN = '4ZUIO546m5zXesUzv6D1KSZs0B0NfQYf';
  protected $TOKEN_SECRET = 'PL2eNdoEBUegd9Ip5PyozQs-TOs';

  protected $API_HOST = 'api.yelp.com';
  protected $DEFAULT_TERM = 'dinner';
  protected $DEFAULT_LOCATION = 'San Jose, CA';
  protected $SEARCH_LIMIT = 20;
  protected $SEARCH_PATH = '/v2/search/';
  protected $BUSINESS_PATH = '/v2/business/';

  /** 
   * Makes a request to the Yelp API and returns the response
   * 
   * @param    $host    The domain host of the API 
   * @param    $path    The path of the APi after the domain
   * @return   The JSON response from the request      
   */
  function request($host, $path) {
      $unsigned_url = "http://" . $host . $path;
  
      // Token object built using the OAuth library
      $token = new OAuthToken($this->TOKEN, $this->TOKEN_SECRET);
  
      // Consumer object built using the OAuth library
      $consumer = new OAuthConsumer($this->CONSUMER_KEY, $this->CONSUMER_SECRET);
  
      // Yelp uses HMAC SHA1 encoding
      $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
  
      $oauthrequest = OAuthRequest::from_consumer_and_token(
          $consumer, 
          $token, 
          'GET', 
          $unsigned_url
      );
      
      // Sign the request
      $oauthrequest->sign_request($signature_method, $consumer, $token);
      
      // Get the signed URL
      $signed_url = $oauthrequest->to_url();
      
      // Send Yelp API Call
      $ch = curl_init($signed_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      
      return $data;
  }
  
  /**
   * Query the Search API by a search term and location 
   * 
   * @param    $term        The search term passed to the API 
   * @param    $location    The search location passed to the API 
   * @return   The JSON response from the request 
   */
  function search($term, $location, $lat, $lng) {
      $url_params = array();
      
      $url_params['term'] = $term;
      $url_params['location'] = $location;
      $url_params['cll'] = "$lat,$lng";
      $url_params['limit'] = $this->SEARCH_LIMIT;
      $search_path = $this->SEARCH_PATH . "?" . http_build_query($url_params);
      $jsonResult = $this->request($this->API_HOST, $search_path);
      $results = json_decode($jsonResult, 1);
      return $results;
  }
  
  /**
   * Query the Business API by business_id
   * 
   * @param    $business_id    The ID of the business to query
   * @return   The JSON response from the request 
   */
  function get_business($business_id) {
      $business_path = $this->BUSINESS_PATH . $business_id;
      
      return $this->request($this->API_HOST, $business_path);
  }
  
  /**
   * Queries the API by the input values from the user 
   * 
   * @param    $term        The search term to query
   * @param    $location    The location of the business to query
   */
  function query_api($term, $location) {     
      $response = json_decode(search($term, $location));
      $business_id = $response->businesses[0]->id;
      
      print sprintf(
          "%d businesses found, querying business info for the top result \"%s\"\n\n",         
          count($response->businesses),
          $business_id
      );
      
      $response = get_business($business_id);
      
      print sprintf("Result for business \"%s\" found:\n", $business_id);
      print "$response\n";
  }
}//end class
?>