<?php

class Magebuzz_Snippet_Model_Mysql4_Snippet_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
  public function _construct()
  {
    parent::_construct();
    $this->_init('snippet/snippet');
  }
}