<?php

class Magebuzz_Snippet_Block_Adminhtml_Snippet_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('snippetGrid');
    $this->setDefaultSort('category_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(true);
  }

  /**
   * prepare collection for block to display
   *
   * @return Magebuzz_Snippet_Block_Adminhtml_Snippet_Grid
   */
  protected function _prepareCollection()
  {
    $collection = Mage::getModel('snippet/snippet')->getCollection();
    $collection->getSelect()->join(Mage::getConfig()->getTablePrefix() . 'catalog_category_flat_store_1',
      'category_id= ' . Mage::getConfig()->getTablePrefix() . 'catalog_category_flat_store_1.entity_id',
      array('name'));
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  /**
   * prepare columns for this grid
   *
   * @return Magebuzz_Snippet_Block_Adminhtml_Snippet_Grid
   */
  protected function _prepareColumns()
  {
    $this->addColumn('category_id', array(
      'header' => Mage::helper('snippet')->__('Category ID'),
      'align' => 'right',
      'index' => 'category_id',
    ));

    $this->addColumn('name', array(
      'header' => Mage::helper('snippet')->__('Category Name'),
      'align' => 'left',
      'index' => 'name',
    ));

    $this->addColumn('reviews_count', array(
      'header' => Mage::helper('snippet')->__('Reviews Count'),
      'align' => 'right',
      'index' => 'reviews_count',
    ));

    $this->addColumn('rating', array(
      'header' => Mage::helper('snippet')->__('Ratings Summary'),
      'align' => 'right',
      'index' => 'rating',
    ));

    $this->addColumn('from_price', array(
      'header' => Mage::helper('snippet')->__('Start Price'),
      'align' => 'right',
      'index' => 'from_price',
    ));

    $this->addColumn('action',
      array(
        'header' => Mage::helper('snippet')->__('Action'),
        'width' => '100',
        'type' => 'action',
        'getter' => 'getId',
        'actions' => array(
          array(
            'caption' => Mage::helper('snippet')->__('Edit'),
            'url' => array('base' => '*/*/edit'),
            'field' => 'id'
          )),
        'filter' => false,
        'sortable' => false,
        'index' => 'stores',
        'is_system' => true,
      ));
    return parent::_prepareColumns();
  }

  /**
   * prepare mass action for this grid
   *
   * @return Magebuzz_Snippet_Block_Adminhtml_Snippet_Grid
   */
  protected function _prepareMassaction()
  {
    $this->setMassactionIdField('snippet_id');
    $this->getMassactionBlock()->setFormFieldName('snippet');

    $statuses = Mage::getSingleton('snippet/status')->getOptionArray();

    array_unshift($statuses, array('label' => '', 'value' => ''));
    $this->getMassactionBlock()->addItem('', array(
      'label' => Mage::helper('snippet')->__('No Action'),
    ));
    return $this;
  }

  public function getRowUrl($row)
  {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}