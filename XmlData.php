<?php


namespace Xml;


class XmlData
{
	/** @var Dibi\Connection */
	public $database;


	public function __construct($database) {
		$this->database = $database;
	}


	/**
	 * @return Dibi\Connection
	 */
	public function getDatabase() {
		return $this->database;
	}

	public function installTableZaznamy() {
		$database = $this->getDatabase();

		$database->query('
            CREATE TABLE IF NOT EXISTS `zaznamy` (
            id INT(10) NOT NULL AUTO_INCREMENT,
            jmeno VARCHAR(64) NOT NULL,
            prijmeni VARCHAR(64) NOT NULL,
            datum DATE,
            PRIMARY KEY (id))
        ');
	}


	public function loadDataFromXml($file): \SimpleXMLElement|bool|string|null {
		return simplexml_load_file($file);
	}

	public function instertDataIntoTableZaznamy($values) {
		$database = $this->getDatabase();

		$database->query('INSERT INTO `zaznamy` %v', $values);
	}

	public function getDataFromTableZaznamy() {
		$database = $this->getDatabase();

		return $database->query('
            SELECT id, jmeno, prijmeni, datum 
            FROM `zaznamy`
            ORDER BY datum
        ');
	}

}