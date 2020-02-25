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
  protected $name;
  protected $description;
  protected $identifier;

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

  // Disable remove function
  public function remove(){throw new Exception("Counter remove function disabled by developer..");}

  // debug
  //protected function event_triggered($event){api_dump($event,static::class." event triggered");}

 }

?>