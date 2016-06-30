<?php

class Magebuzz_Snippet_Model_Status extends Varien_Object
{
  const STATUS_ENABLED = 1;
  const STATUS_DISABLED = 2;

  /**
   * get model option as array
   *
   * @return array
   */
  static public function getOptionArray()
  {
    return array(
      self::STATUS_ENABLED => Mage::helper('snippet')->__('Enabled'),
      self::STATUS_DISABLED => Mage::helper('snippet')->__('Disabled')
    );
  }

  /**
   * get model option hash as array
   *
   * @return array
   */
  static public function getOptionHash()
  {
    $options = array();
    foreach (self::getOptionArray() as $value => $label) {
      $options[] = array(
        'value' => $value,
        'label' => $label
      );
    }
    return $options;
  }
}