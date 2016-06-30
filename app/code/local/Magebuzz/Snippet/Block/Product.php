<?php

class Magebuzz_Snippet_Block_Product extends Mage_Core_Block_Template
{
  /**
   * prepare block's layout
   *
   * @return Magebuzz_Snippet_Block_Product
   */
  public function _prepareLayout()
  {
    return parent::_prepareLayout();
  }

  public function getProduct()
  {
    return Mage::registry('product');
  }

  protected function _construct()
  {
    $this->addData(array(
      'cache_lifetime'  => 86400,
      'cache_tags'      => array(Mage_Catalog_Model_Product::CACHE_TAG),
      'cache_key'       => $this->getProduct()->getId(),
    ));
  }

}