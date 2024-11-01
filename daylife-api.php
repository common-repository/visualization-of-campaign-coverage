<?php
/***
 * Daylife API Wrapper
 * @package ccviz
 * @author Chris Barna
 ***/
class DaylifeAPI {
	protected $baseurl = "http://freeapi.daylife.com/phprest/publicapi/4.10/";
	
	private $accesskey;
	private $sharedkey;
	
	// Constructor.
	public function __construct ($accesskey, $sharedsecret) {
		$this->accesskey = $accesskey;
		$this->sharedkey = $sharedsecret;
	}
	
	// Call the API.
	public function call ($objectName, $method, $parameters) {
		
		// Create the signature based on what type we're querying.
		switch ($objectName) {
			case "topic":
				if ($parameters['name']) {
					$parameters["signature"] = $this->signature($parameters['name']);
					$parameters['name'] = urlencode($parameters['name']);
				} else {
					$parameters["signature"] = $this->signature($parameters['topic_id']);
				}
				break;
				
			case "article":
				if ($parameters['url']) {
					$parameters["signature"] = $this->signature($parameters['url']);
				} else {
					$parameters["signature"] = $this->signature($parameters['article_id']);
				}
				break;
			
			default:
				$parameters['signature'] = $this->signature($parameters[$objectName."_id"]);
				break;
		}

		$url = $this->baseurl.$objectName."_".$method."?accesskey=".$this->accesskey."&";
		$url = add_query_arg($parameters, $url);
		
		$data = wp_remote_get($url);
		$data = unserialize($data['body']);
		return $data['response']['payload'];
	}
	
	// Create the signature.
	private function signature($coreinput) {
		return md5($this->accesskey.$this->sharedkey.$coreinput);
	}
}
?>