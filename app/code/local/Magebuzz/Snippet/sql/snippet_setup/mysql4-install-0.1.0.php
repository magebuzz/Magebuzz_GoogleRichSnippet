<?php
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create snippet table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('snippet')};

CREATE TABLE {$this->getTable('snippet')} (
  `snippet_id` int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `reviews_count` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `from_price` decimal(12,4) NOT NULL,
  PRIMARY KEY (`snippet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

