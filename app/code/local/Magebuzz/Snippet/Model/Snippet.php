<?php

class Magebuzz_Snippet_Model_Snippet extends Mage_Core_Model_Abstract
{
  public function _construct()
  {
    parent::_construct();
    $this->_init('snippet/snippet');
  }
}