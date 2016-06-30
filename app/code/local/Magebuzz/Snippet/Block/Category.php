<?php

class Magebuzz_Snippet_Block_Category extends Mage_Core_Block_Template
{
  public function _prepareLayout()
  {
    return parent::_prepareLayout();
  }

  public function _construct()
  {
    $this->addData(array(
      'cache_lifetime' => 86400,
      'cache_tags' => array(Mage_Catalog_Model_Category::CACHE_TAG),
      'cache_key'  => Mage::registry('current_category')->getId(),
    ));
  }

  public function getCategory() {
    $currentCategory = Mage::registry('current_category');
    $cate_id = $currentCategory->getId();
    $_category = Mage::getModel('snippet/snippet')->load($cate_id, 'category_id');
    return $_category;
  }
}