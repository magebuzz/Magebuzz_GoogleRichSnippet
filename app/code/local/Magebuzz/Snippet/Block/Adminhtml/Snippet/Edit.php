<?php

class Magebuzz_Snippet_Block_Adminhtml_Snippet_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
  public function __construct()
  {
    parent::__construct();
    $this->_removeButton('delete');

    $this->_objectId = 'id';
    $this->_blockGroup = 'snippet';
    $this->_controller = 'adminhtml_snippet';

    $this->_updateButton('save', 'label', Mage::helper('snippet')->__('Save Item'));
  }

  public function getHeaderText()
  {
    if (Mage::registry('snippet_data')
      && Mage::registry('snippet_data')->getId()
    ) {
      return Mage::helper('snippet')->__("Edit Category Id '%s'", $this->htmlEscape(Mage::registry('snippet_data')
        ->getCategoryId()));
    }
    return Mage::helper('snippet')->__('Add Item');
  }
}
