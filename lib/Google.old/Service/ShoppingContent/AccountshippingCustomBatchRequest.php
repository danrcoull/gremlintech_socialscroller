<?php
class Google_Service_ShoppingContent_AccountshippingCustomBatchRequest extends Google_Collection
{
  protected $collection_key = 'entries';
  protected $internal_gapi_mappings = array(
  );
  protected $entriesType = 'Google_Service_ShoppingContent_AccountshippingCustomBatchRequestEntry';
  protected $entriesDataType = 'array';


  public function setEntries($entries)
  {
    $this->entries = $entries;
  }
  public function getEntries()
  {
    return $this->entries;
  }
}