<?php
/**
 * Counters - Counter
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 /**
  * Counters Counter class
  */
 class cCountersCounter extends cObject{

  /** Parameters */
  static protected $table="counters__counters";
  static protected $logs=true;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $order;
  protected $name;
  protected $description;
  protected $identifier;

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->name))){throw new Exception("Counter name is mandatory..");}
   // return
   return true;
  }

  /**
   * Decode log properties
   *
   * {@inheritdoc}
   */
  public static function log_decode($event,$properties){
   // make return array
   $return_array=array();
   // subobject events
   if($properties['_obj']=="cCountersCounterMeasurement"){$return_array[]=api_text($properties['_obj'])." ".api_period($properties['_period']);}
   // return
   return implode(" | ",$return_array);
  }

  /**
   * Get Label
   *
   * @return string Counter label
   */
  public function getLabel(){
   // make label
   $label=$this->name;
   if($this->identifier){$label.=" (".$this->identifier.")";}
   // return
   return $label;
  }

  /**
   * Get Measurements
   *
   * @param integer limit Limit number of measurements
   * @return object[]|false Array of measurements objects or false
   */
  public function getMeasurements($limit=null){return api_sortObjectsArray(cCountersCounterMeasurement::availables(true,["fkCounter"=>$this->id],$limit),"period",true);}

  /**
   * Get Last Measurement
   *
   * @return object[]|false Last measurements objects or false
   */
  public function getLastMeasurement(){
   /** @todo migliorabile? */
   $measurements_array=cCountersCounterMeasurement::select("`fkCounter`='".$this->id."'","`period` DESC",1);
   // check results
   if(!is_array($measurements_array) || !count($measurements_array)){return false;}
   // return
   return reset($measurements_array);
  }

  /**
   * Edit form
   *
   * @param string[] $additional_parameters Array of url additional parameters
   * @return object Form structure
   */
  public function form_edit(array $additional_parameters=null){
   // build form
   $form=new strForm(api_url(array_merge(["mod"=>"counters","scr"=>"controller","act"=>"store","obj"=>"cCountersCounter","idCounter"=>$this->id],$additional_parameters)),"POST",null,null,"counters_counter_edit_form");
   // fields
   $form->addField("text","name",api_text("cCountersCounter-property-name"),$this->name,api_text("cCountersCounter-placeholder-name"),null,null,null,"required");
   $form->addField("textarea","description",api_text("cCountersCounter-property-description"),$this->description,api_text("cCountersCounter-placeholder-description"),null,null,null,"rows='2'");
   $form->addField("text","identifier",api_text("cCountersCounter-property-identifier"),$this->identifier,api_text("cCountersCounter-placeholder-identifier"),null,null,null,"required");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  /**
   * Store
   *
   * {@inheritdoc}
   */
  public function store(array $properties,$log=true){
   $result=parent::store($properties,$log);
   if(!$result){return false;}
   // set maximum order              @todo integrare nello store (se esiste campo order?)
   api_dump($order_update_query="UPDATE `".static::$table."` SET `order`=( SELECT * FROM ( SELECT MAX(`order`)+'1' AS `order` FROM `".static::$table."` ) AS `subquerytable`) WHERE `id`='".$this->id."'","order_update_query");
   // execute query
   $GLOBALS['database']->queryExecute($order_update_query);
   // return
   return true;
  }

  /**
   * Move
   *
   * @param string $direction Order move direction
   * @param boolean $log Log event
   * @return boolean
   */
  public function move($direction,$log=true){
   // checks parameters
   if(!in_array(strtolower($direction),array("up","down"))){throw new Exception("Move direction not defined..");}
   // check for exist
   if(!$this->exists()){throw new Exception("Counter does not exist..");}
   // build query object
   $query_obj=new stdClass();
   $query_obj->id=$this->id;
   // switch order move direction
   switch(strtolower($direction)){
    // up -> order -1
    case "up":
     // set previous order
     $query_obj->order=$this->order-1;
     // check for order
     if($query_obj->order<1){throw new Exception("Counter order cannot be less than zero..");}
     // update object
     api_dump($query_obj,static::class."->move update query object");
     // execute query
     $GLOBALS['database']->queryUpdate(static::$table,$query_obj);
     /* @todo check? */
     // rebase other objects
     // @todo non serve qui ma magari se faccio function generica puo tornare utile if($menu_obj->fkMenu){$rebase_query_where="`fkMenu`='".$menu_obj->fkMenu."'";}else{$rebase_query_where="`fkMenu` IS null";}   //+AND ".$rebase_query_where
     api_dump($rebase_query="UPDATE `".static::$table."` SET `order`=`order`+'1' WHERE `order`<'".$this->order."' AND `order`>='".$query_obj->order."' AND `order`<>'0' AND `id`!='".$this->id."'","rebase_query");
     // execute query
     $GLOBALS['database']->queryExecute($rebase_query);
     break;
    // down -> order +1
    case "down":
     // set following order
     $query_obj->order=$this->order+1;
     // update object
     api_dump($query_obj,static::class."->move update query object");
     // execute query
     $GLOBALS['database']->queryUpdate(static::$table,$query_obj);
     /* @todo check? */
     // rebase other menus
     // @todo vedi sopra
     api_dump($rebase_query="UPDATE `".static::$table."` SET `order`=`order`-'1' WHERE `order`>'".$this->order."' AND `order`<='".$query_obj->order."' AND `order`<>'0' AND `id`!='".$this->id."'","rebase_query");
     // execute query
     $GLOBALS['database']->queryExecute($rebase_query);
     break;
   }
   // throw event
   //$this->event("information","moved",null,$log); @todo loggare?
   // return
   return true;
  }

  // Disable remove function
  public function remove(){
   //throw new Exception("Counter remove function disabled by developer..");
   // call parent
   $result=parent::remove();
   if(!$result){return false;}
   // rebase other menus
   // @todo vedi sopra per AND aggiuntivo
   api_dump($rebase_query="UPDATE `".static::$table."` SET `order`=`order`-'1' WHERE `order`>'".$this->order."'","rebase_query");
   // execute query
   $GLOBALS['database']->queryExecute($rebase_query);
   // return
   return true;
  }

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>