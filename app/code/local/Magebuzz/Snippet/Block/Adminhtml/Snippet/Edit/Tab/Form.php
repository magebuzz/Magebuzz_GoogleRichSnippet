<?php

class Magebuzz_Snippet_Block_Adminhtml_Snippet_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  /**
   * prepare tab form's information
   *
   * @return Magebuzz_Snippet_Block_Adminhtml_Snippet_Edit_Tab_Form
   */
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $this->setForm($form);

    if (Mage::getSingleton('adminhtml/session')->getSnippetData()) {
      $data = Mage::getSingleton('adminhtml/session')->getSnippetData();
      Mage::getSingleton('adminhtml/session')->setSnippetData(null);
    } elseif (Mage::registry('snippet_data')) {
      $data = Mage::registry('snippet_data')->getData();
    }
    $fieldset = $form->addFieldset('snippet_form', array(
      'legend' => Mage::helper('snippet')->__('Category information')
    ));

    $fieldset->addField('category_id', 'text', array(
      'label' => Mage::helper('snippet')->__('Category'),
      'class' => 'required-entry',
      'disabled' => true,
      'required' => true,
      'name' => 'category_id',
    ));

    $fieldset->addField('reviews_count', 'text', array(
      'label' => Mage::helper('snippet')->__('Reviews Count'),
      'required' => true,
      'name' => 'reviews_count',
    ));

    $fieldset->addField('rating', 'text', array(
      'label' => Mage::helper('snippet')->__('Ratings'),
      'required' => true,
      'name' => 'rating',
    ));

    $fieldset->addField('from_price', 'text', array(
      'label' => Mage::helper('snippet')->__('Start Price'),
      'required' => true,
      'name' => 'from_price',
    ));

    $form->setValues($data);
    return parent::_prepareForm();
  }
}