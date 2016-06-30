<?php

class Magebuzz_Snippet_IndexController extends Mage_Core_Controller_Front_Action
{
  /**
   * index action
   */
  public function indexAction()
  {
    $this->loadLayout();
    $this->renderLayout();
  }
}