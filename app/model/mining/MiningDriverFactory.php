<?php
namespace EasyMinerCenter\Model\Mining;


use EasyMinerCenter\Model\EasyMiner\Entities\Task;
use EasyMinerCenter\Model\EasyMiner\Facades\MetaAttributesFacade;
use EasyMinerCenter\Model\EasyMiner\Facades\MinersFacade;
use EasyMinerCenter\Model\EasyMiner\Facades\RulesFacade;
use Nette\ArgumentOutOfRangeException;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * Class MiningDriverFactory - třída zajišťující vytvoření odpovídajícího driveru pro dolování
 * @package EasyMinerCenter\Model\Mining
 */
class MiningDriverFactory extends Object{
  private $params;

  public function __construct($params){
    $this->params=$params;
  }

  /**
   * Funkce vracející URL pro přístup ke zvolenému mineru
   * @param string $minerType
   * @return string
   */
  public function getMinerUrl($minerType) {
    $url=@$this->params['driver_'.$minerType]['server'];
    if(!empty($url)&&!empty($this->params['driver_'.$minerType]['minerUrl'])){
      if (!Strings::endsWith($this->params['driver_'.$minerType]['server'],'/')){
        $url.=ltrim($this->params['driver_'.$minerType]['minerUrl'],'/');
      }
    }
    return $url;
  }

  /**
   * @param Task $task
   * @param MinersFacade $minersFacade
   * @param RulesFacade $rulesFacade
   * @param MetaAttributesFacade $metaAttributesFacade
   * @return IMiningDriver
   */
  public function getDriverInstance(Task $task ,MinersFacade $minersFacade, RulesFacade $rulesFacade, MetaAttributesFacade $metaAttributesFacade){
    if (isset($this->params['driver_'.$task->type])){
      $driverClass='\\'.$this->params['driver_'.$task->type]['class'];
      return new $driverClass($task, $minersFacade, $rulesFacade, $metaAttributesFacade, $this->params['driver_'.$task->type]);
    }
    throw new ArgumentOutOfRangeException('Requested mining driver was not found!',500);
  }

} 