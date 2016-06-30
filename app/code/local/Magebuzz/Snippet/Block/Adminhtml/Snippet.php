<?php

class Magebuzz_Snippet_Block_Adminhtml_Snippet extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_snippet';
    $this->_blockGroup = 'snippet';
    $this->_headerText = Mage::helper('snippet')->__('All Category Reviews');
    if (Mage::getModel('snippet/snippet')->getCollection()->getSize() <= 0) {
      $this->_addButtonLabel = Mage::helper('snippet')->__('Generate');
    } else {
      $this->_addButtonLabel = Mage::helper('snippet')->__('Delete Current Data and Re-Generate');
    }
    parent::__construct();
  }
}