<?php

class Magebuzz_Snippet_Adminhtml_SnippetController extends Mage_Adminhtml_Controller_Action
{
  /**
   * init layout and set active for current menu
   *
   * @return Magebuzz_Snippet_Adminhtml_SnippetController
   */
  protected function _initAction()
  {
    $this->loadLayout()
      ->_setActiveMenu('snippet/snippet')
      ->_addBreadcrumb(
        Mage::helper('adminhtml')->__('Items Manager'),
        Mage::helper('adminhtml')->__('Item Manager')
      );
    return $this;
  }

  /**
   * index action
   */
  public function indexAction()
  {
    $this->_initAction();
    $this->renderLayout();
  }

  public function newAction()
  {
    $category = Mage::getModel('snippet/snippet');
    if (!$category->getId()) {
      $this->_forward('update');
    } else {
      $this->_forward('create');
    }
  }

  public function updateAction()
  {
    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
    $snippet_table = Mage::getSingleton('core/resource')->getTableName('snippet');
    $delete_sql = "delete from {$snippet_table}";
    $write->query($delete_sql);
    $this->_forward('create');
  }

  public function createAction()
  {
    $read = Mage::getSingleton('core/resource')->getConnection('core_read');
    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
    $reviews = Mage::getSingleton('core/resource')->getTableName('review_entity_summary');
    $snippet_table = Mage::getSingleton('core/resource')->getTableName('snippet');
    $product_category = Mage::getSingleton('core/resource')->getTableName('catalog_category_product');
    $query = "SELECT " . $product_category . ".category_id, sum(reviews_count), avg(rating_summary)
							FROM " . $reviews . " inner join " . $product_category . "
							WHERE " . $reviews . ".entity_pk_value = " . $product_category . ".product_id and reviews_count != 0
							group by " . $product_category . ".category_id";
    $result = $read->query($query);
    while ($row = $result->fetch()) {
      $count = $row['sum(reviews_count)'];
      $cate_count = $row['category_id'];
      $rating = $row['avg(rating_summary)'];

      $categoryModel = Mage::getModel('catalog/category')->load($cate_count);
      $productColl = Mage::getModel('catalog/product')->getCollection()
        ->addCategoryFilter($categoryModel)
	      ->addAttributeToFilter('price', array('gt' => 0))
        ->addAttributeToSort('price', 'asc')
        ->setPageSize(1)
        ->load();
      $lowestProductPrice = 0;
      if ($productColl->getFirstItem()->getId()) $lowestProductPrice = $productColl->getFirstItem()->getPrice();

      $sql = "INSERT INTO {$snippet_table} (category_id, reviews_count, rating, from_price) VALUES(" . $cate_count . " , " . $count . " , " . $rating . " , " . $lowestProductPrice . ")";
      $write->query($sql);
    }
    $this->_forward('parent');
    $this->_redirect('*/*/');
  }

  public function parentAction()
  {
    $cou = array();
    $read = Mage::getSingleton('core/resource')->getConnection('core_read');
    $category_table = Mage::getSingleton('core/resource')->getTableName('catalog_category_entity');
    $snippet_table = Mage::getSingleton('core/resource')->getTableName('snippet');
    $query = "select level from {$category_table} where level > 1 group by level";
    $result = $read->query($query);
    while ($row = $result->fetch()) {
      $count = $row['level'];
      $cou[] = $count;
    }
    $level_cat = array_reverse($cou);
    for ($i = 0; $i < count($level_cat) - 1; $i++) {

      $collection = Mage::getModel('catalog/category')->getResourceCollection();
      foreach ($collection->getItems() as $item) {
        $sum = 0;
        $price = array();
	      $sum_avg_rev  = 0;
        if ($item->getChildrenCount() > 0) {
          $id = $item->getId();
          $root = Mage::getModel('catalog/category')->load($id);
          $subCat = explode(',', $root->getChildren());

          $collection = $root
            ->getCollection()
            ->addAttributeToSelect("*")
            ->addAttributeToFilter('level', $level_cat[$i])
            ->addFieldToFilter("entity_id", array("in", $subCat));
          foreach ($collection->getItems() as $cat) {
            $id_cate = $cat->getId();
            $categoryModel = Mage::getModel('catalog/category')->load($id_cate);

            $productColl = Mage::getModel('catalog/product')->getCollection()
              ->addCategoryFilter($categoryModel)
	            ->addAttributeToFilter('price', array('gt' => 0))
              ->addAttributeToSort('price', 'asc')
              ->setPageSize(1)
              ->load();
            $lowestProductPrice = $productColl->getFirstItem()->getPrice();
	          if($lowestProductPrice > 0){
              $price[] = $lowestProductPrice;
	          }

	          $sql = "SELECT from_price FROM ".$snippet_table." WHERE category_id = ".$id_cate;
	          $_result = $read->query($sql);
	          while($_row = $_result->fetch())
	          {
		          $pri = $_row['from_price'];
	          }
	          $price[] = $pri;

            $snip = Mage::getModel('snippet/snippet')->getCollection();
            foreach ($snip as $snippet) {
              if ($snippet->getCategoryId() == $id_cate) {
	              $review = $snippet->getReviewsCount();
	              $sum += $review;
	              $rating  = $snippet->getRating();
	              $sum_avg_rev += $review*$rating;
              }
            }
          }
          if ($sum > 0) {
	          if(Mage::helper('snippet')->getChildCategory())
	          {
	          $avg = round($sum_avg_rev/$sum);
	          $_pri = min($price);
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $reviews = Mage::getSingleton('core/resource')->getTableName('review_entity_summary');
            $product_category = Mage::getSingleton('core/resource')->getTableName('catalog_category_product');
            $query = "SELECT sum(reviews_count), avg(rating_summary), category_id
													FROM " . $reviews . " inner join " . $product_category . "
													WHERE " . $reviews . ".entity_pk_value = " . $product_category . ".product_id and reviews_count != 0 and
													category_id = " . $id . "
													group by " . $product_category . ".category_id";
            $result = $read->query($query);
            while ($row = $result->fetch()) {
	            if(isset($row['category_id']) && $row['category_id'] == $id){
              $sum_review_count = $row['sum(reviews_count)'];
              $avg_rating_summary = $row['avg(rating_summary)'];
              $sum += $sum_review_count;
	            $avg = round(($sum_review_count* $avg_rating_summary + $sum_avg_rev)/$sum);
	            }
            }

            $categoryModel = Mage::getModel('catalog/category')->load($id);
            $productColl = Mage::getModel('catalog/product')->getCollection()
              ->addCategoryFilter($categoryModel)
	            ->addAttributeToFilter('price', array('gt' => 0))
              ->addAttributeToSort('price', 'asc')
              ->setPageSize(1)
              ->load();
            $lowestProductPrice = $productColl->getFirstItem()->getPrice();
            if (($lowestProductPrice) > 0 && ($lowestProductPrice < $_pri)) {
              $_pri = $lowestProductPrice;
            }

            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $_sql = " DELETE FROM {$snippet_table} where category_id = " . $id;
            $write->query($_sql);

            $_snippet = Mage::getModel('snippet/snippet');
		          $_snippet->setData('category_id', $id);
		          $_snippet->setData('reviews_count', $sum);
		          $_snippet->setData('rating', $avg);
		          $_snippet->setData('from_price', $_pri);
		          $_snippet->save();
	          }else{
		          $categoryModel = Mage::getModel('catalog/category')->load($id);
		          $productColl = Mage::getModel('catalog/product')->getCollection()
			          ->addCategoryFilter($categoryModel)
			          ->addAttributeToFilter('price', array('gt' => 0))
			          ->addAttributeToSort('price', 'asc')
			          ->setPageSize(1)
			          ->load();
		          $lowestProductPrice = $productColl->getFirstItem()->getPrice();

		          $write = Mage::getSingleton('core/resource')->getConnection('core_write');
		          $snippet_table = Mage::getSingleton('core/resource')->getTableName('snippet');

		          $_sql = " DELETE FROM {$snippet_table} where category_id = " . $id;
		          $write->query($_sql);
		          $read = Mage::getSingleton('core/resource')->getConnection('core_read');
		          $reviews = Mage::getSingleton('core/resource')->getTableName('review_entity_summary');
		          $product_category = Mage::getSingleton('core/resource')->getTableName('catalog_category_product');

		          $query = "SELECT " . $product_category . ".category_id, sum(reviews_count), avg(rating_summary)
								FROM " . $reviews . " inner join " . $product_category . "
								WHERE " . $reviews . ".entity_pk_value = " . $product_category . ".product_id and reviews_count != 0 and
								".$product_category.".category_id = ". $id."
								group by " . $product_category . ".category_id";
		          $result = $read->query($query);
		          while ($row = $result->fetch()) {
			          $count = $row['sum(reviews_count)'];
			          $rating = $row['avg(rating_summary)'];

			          $_snippet = Mage::getModel('snippet/snippet');
			          $_snippet->setData('category_id', $id);
			          $_snippet->setData('reviews_count', $count);
			          $_snippet->setData('rating', $rating);
			          $_snippet->setData('from_price', $lowestProductPrice);

			          $_snippet->save();
		          }
            }
          }
        }
      }
    }
	  $this->_forward('getcategoryzero');
  }

	public function getcategoryzeroAction()
	{
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$table_category = Mage::getSingleton('core/resource')->getTableName('catalog_category_entity');
		$table_snippet = Mage::getSingleton('core/resource')->getTableName('snippet');
		$sql = "SELECT distinct  ". $table_category .".entity_id
		        FROM ".$table_category ."
		        WHERE ".$table_category.".entity_id NOT IN (SELECT category_id FROM ".$table_snippet." ) and entity_id > 1
		         ";
		$result = $read->query($sql);
		while($row = $result->fetch())
		{
			$id = $row['entity_id'];
			$categoryModel = Mage::getModel('catalog/category')->load($id);
			$productColl = Mage::getModel('catalog/product')->getCollection()
				->addCategoryFilter($categoryModel)
				->addAttributeToFilter('price', array('gt' => 0))
				->addAttributeToSort('price', 'asc')
				->setPageSize(1)
				->load();
			$lowestProductPrice = 0;
			if ($productColl->getFirstItem()->getId()) $lowestProductPrice = $productColl->getFirstItem()->getPrice();

			$count = 0;
			$rating = 0;
			$sql = "INSERT INTO {$table_snippet} (category_id, reviews_count, rating, from_price) VALUES(" . $id . " ,
			" . $count . " , " . $rating . " , " . $lowestProductPrice . ")";
			$write->query($sql);
		}
	}

  public function editAction()
  {
    $snippetId = $this->getRequest()->getParam('id');
    $model = Mage::getModel('snippet/snippet')->load($snippetId);

    if ($model->getId() || $snippetId == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
      if (!empty($data)) {
        $model->setData($data);
      }
      Mage::register('snippet_data', $model);

      $this->loadLayout();
      $this->_setActiveMenu('snippet/snippet');

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
      $this->_addContent($this->getLayout()->createBlock('snippet/adminhtml_snippet_edit'))
        ->_addLeft($this->getLayout()->createBlock('snippet/adminhtml_snippet_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(
        Mage::helper('snippet')->__('Item does not exist')
      );
      $this->_redirect('*/*/');
    }
  }

  public function saveAction()
  {
    if ($data = $this->getRequest()->getPost()) {

      $model = Mage::getModel('snippet/snippet');
      $model->setData($data)
        ->setId($this->getRequest()->getParam('id'));

      try {
        $model->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(
          Mage::helper('snippet')->__('Item was successfully saved')
        );
        Mage::getSingleton('adminhtml/session')->setFormData(false);

        if ($this->getRequest()->getParam('back')) {
          $this->_redirect('*/*/edit', array('id' => $model->getId()));
          return;
        }
        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    Mage::getSingleton('adminhtml/session')->addError(
      Mage::helper('snippet')->__('Unable to find item to save')
    );
    $this->_redirect('*/*/');
  }

  /*	protected function _isAllowed()
    {
      return Mage::getSingleton('admin/session')->isAllowed('snippet');
    }*/
}