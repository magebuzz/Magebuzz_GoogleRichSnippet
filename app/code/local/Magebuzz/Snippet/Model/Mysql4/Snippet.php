<?php

class Magebuzz_Snippet_Model_Mysql4_Snippet extends Mage_Core_Model_Mysql4_Abstract
{
  public function _construct()
  {
    $this->_init('snippet/snippet', 'snippet_id');
  }
}