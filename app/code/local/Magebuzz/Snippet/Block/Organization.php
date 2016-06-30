<?php

class Magebuzz_Snippet_Block_Organization extends Mage_Core_Block_Template
{
  public function _prepareLayout()
  {
    return parent::_prepareLayout();
  }

  public function _construct()
  {
    $this->addData(array(
      'cache_lifetime' => 86400,
    ));
  }
}