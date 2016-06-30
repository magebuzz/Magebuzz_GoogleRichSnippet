<?php

class Magebuzz_Snippet_Block_Adminhtml_Snippet_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('snippet_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('snippet')->__('Category Information'));
  }

  /**
   * prepare before render block to html
   *
   * @return Magebuzz_Snippet_Block_Adminhtml_Snippet_Edit_Tabs
   */
  protected function _beforeToHtml()
  {
    $this->addTab('form_section', array(
      'label' => Mage::helper('snippet')->__('Category Information'),
      'title' => Mage::helper('snippet')->__('Category Information'),
      'content' => $this->getLayout()
          ->createBlock('snippet/adminhtml_snippet_edit_tab_form')
          ->toHtml(),
    ));
    return parent::_beforeToHtml();
  }

}