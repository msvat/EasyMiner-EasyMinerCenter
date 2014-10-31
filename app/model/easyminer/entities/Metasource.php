<?php

namespace App\Model\EasyMiner\Entities;
use App\Libs\StringsHelper;
use App\Model\Data\Entities\DbConnection;
use LeanMapper\Entity;

/**
 * Class Metasource
 * @package App\Model\EasyMiner\Entities
 * @property int|null $metasourceId = null
 * @property Miner $miner m:hasOne
 * @property User|null $user = null m:hasOne
 * @property string $type = m:Enum('mysql','cassandra')
 * @property string $dbServer
 * @property int|null $dbPort = null
 * @property string $dbUsername
 * @property string $dbName
 * @property string $attributesTable
 * @property-read Task[] $tasks m:belongsToMany
 * @property-read Attribute[] $attributes m:belongsToMany
 * @property-read DbConnection $dbConnection
 */
class Metasource extends Entity{
  /**
   * Funkce vracející přehled typů databází
   * @return array
   */
  public static function getTypes(){
    return array(
      'mysql'=>'MySQL',
      'cassandra'=>'Cassandra DB'
    );
  }

  /**
   * @return DbConnection
   */
  public function getDbConnection(){
    $dbConnection=new DbConnection();
    $dbConnection->dbName=$this->dbName;
    $dbConnection->dbUsername=$this->dbUsername;
    $dbConnection->dbPassword=$this->getDbPassword();
    $dbConnection->dbPort=$this->dbPort;
    $dbConnection->dbServer=$this->dbServer;
    $dbConnection->type=$this->type;
    return $dbConnection;
  }

  /**
   * @return string
   */
  public function getDbPassword(){
    if (empty($this->row->db_password)){return null;}
    return StringsHelper::decodePassword($this->row->db_password);
  }

  /**
   * @param string $password
   */
  public function setDbPassword($password){
    $this->row->db_password=StringsHelper::encodePassword($password);
  }
} 