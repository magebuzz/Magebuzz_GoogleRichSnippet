<?php

class Magebuzz_Snippet_Model_Observer
{
  public function controllerActionPredispatch($observer)
  {
    $action = $observer->getEvent()->getControllerAction();
    return $this;
  }
}