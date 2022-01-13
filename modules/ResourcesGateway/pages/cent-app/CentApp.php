<?php

class CentApp
{
  public $api_url = "https://cent.app/api/v1/";
  public $api_key;
  public $headers;

  public function __construct($api_key, $shop_id)
  {
    $this->api_key = $api_key;
    $this->shop_id = $shop_id;
    $this->headers = array(
      "Accept: application/json",
      "Authorization: Bearer {$api_key}",
   );
  }

  private function getDataStr(array $data)
  {
    $resp = 'shop_id='. $this->shop_id . '&';
    foreach ($data as $key => $value) {
     $resp .= $key. '=' . $value . '&'; 
    }
    return trim($resp, '&');
  }

  public function getSignature($InvId, $OutSum)
  {
    return strtoupper(md5($OutSum . ":" . $InvId . ":" . $this->api_key));
  }

  // $options = array('order_id' => 1, 'amount' => 10, 'currency_in' => 'USD', 'name' => '', 'description' => '', 'custom' => '');
  public function createBill(array $options)
  {
    $url = $this->api_url . 'bill/create';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getDataStr($options));
    $resp = curl_exec($curl);
    curl_close($curl);
    return json_decode($resp, true);
    var_dump($resp);
  }
}