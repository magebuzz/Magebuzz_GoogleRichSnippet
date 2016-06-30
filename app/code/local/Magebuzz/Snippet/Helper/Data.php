<?php

class Magebuzz_Snippet_Helper_Data extends Mage_Core_Helper_Abstract
{
	//general configuration
	public function isEnable()
	{
		return Mage::getStoreConfig('snippet/general/enabled');
	}

	//Product configuration
	public function showPrice()
	{
		return Mage::getStoreConfig('snippet/product/price');
	}

	public function showAvailability()
	{
		return Mage::getStoreConfig('snippet/product/availability');
	}

	public function showRatings()
	{
		return Mage::getStoreConfig('snippet/product/ratings');
	}

	public function showDescription()
	{
		return Mage::getStoreConfig('snippet/product/description');
	}

	public function showImage()
	{
		return Mage::getStoreConfig('snippet/product/image');
	}

	//BreadCrumbs configuration
	public function enableBreadcrumbs()
	{
		return Mage::getStoreConfig('snippet/link/breadcrumbs');
	}

	// Organization Configuration
  public function showOrganization()
  {
    return Mage::getStoreConfig('snippet/organization/enabled');
  }

  public function getName()
  {
    return Mage::getStoreConfig('snippet/organization/name');
  }

  public function getLogo()
  {
    return Mage::getStoreConfig('snippet/organization/logo');
  }

  public function getStreet()
  {
    return Mage::getStoreConfig('snippet/organization/street');
  }

  public function getTelephone()
  {
    return Mage::getStoreConfig('snipet/organization/telephone');
  }

  public function getLink()
  {
    return Mage::getStoreConfig('snippet/organization/link');
  }

  public function getPostalCode()
  {
    return Mage::getStoreConfig('snippet/organization/postalcode');
  }

  public function getRegion()
  {
    return Mage::getStoreConfig('snippet/organization/region');
  }

  public function getCountry()
  {
    return Mage::getStoreConfig('snippet/organization/country');
  }

	//Category Configuration
  public function showCategory()
  {
    return Mage::getStoreConfig('snippet/category/enabled');
  }

	public function getChildCategory()
	{
		return Mage::getStoreConfig('snippet/category/child_category');
	}

}