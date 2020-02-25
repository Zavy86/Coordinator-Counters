<?php
/**
 * Counters - Counter Measurement
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 /**
  * Counters Counter Measurement class
  */
 class cCountersCounterMeasurement extends cObject{

  /** Parameters */
  static protected $table="counters__counters__measurements";
  static protected $logs=false;

  /** Properties */
  protected $id;
  protected $deleted;
  protected $fkCounter;
  protected $period;
  protected $current;
  protected $previous;

  /**
   * Get Counter
   *
   * @return object
   */
  public function getCounter(){return new cCountersCounter($this->fkCounter);}

  /**
   * Get Value
   *
   * @return double
   */
  public function getValue(){return (double)$this->current-$this->previous;}

  /**
   * Check
   *
   * @return boolean
   * @throws Exception
   */
  protected function check(){
   // check properties
   if(!strlen(trim($this->fkCounter))){throw new Exception("Measurement counter key is mandatory..");}
   if(!strlen(trim($this->period))){throw new Exception("Measurement period is mandatory..");}
   if(!strlen(trim($this->current))){throw new Exception("Measurement current value is mandatory..");}
   if(!strlen(trim($this->previous))){throw new Exception("Measurement previous value is mandatory..");}
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
   $form=new strForm(api_url(array_merge(["mod"=>"counters","scr"=>"controller","act"=>"store","obj"=>"cCountersCounterMeasurement","idMeasurement"=>$this->id],$additional_parameters)),"POST",null,null,"counters_counter_measurement_edit_form");
   // fields
   $form->addField("select","fkCounter",api_text("cCountersCounterMeasurement-property-fkCounter"),$this->fkCounter,api_text("cCountersCounterMeasurement-placeholder-fkCounter"),null,null,null,"required");
   foreach(cCountersCounter::availables(true) as $counter_fobj){$form->addFieldOption($counter_fobj->id,$counter_fobj->getLabel());}
   $form->addField("select","period",api_text("cCountersCounterMeasurement-property-period"),$this->period,api_text("cCountersCounterMeasurement-placeholder-period"),null,null,null,"required");
   foreach(api_period_range(date("Ym",strtotime("-1 month")),min(array_filter(array(date("Ym",strtotime("-3 month")),$this->period)))) as $value=>$label){$form->addFieldOption($value,$label);}
   $form->addField("text","current",api_text("cCountersCounterMeasurement-property-current"),$this->current,api_text("cCountersCounterMeasurement-placeholder-current"),null,null,null,"required");
   $form->addField("text","previous",api_text("cCountersCounterMeasurement-property-previous"),$this->previous,api_text("cCountersCounterMeasurement-placeholder-previous"),null,null,null,"required");
   // controls
   $form->addControl("submit",api_text("form-fc-submit"));
   // return
   return $form;
  }

  // debug
  protected function event_triggered($event){
   //api_dump($event,static::class." event triggered");
   // skip trace events
   if($event->typology=="trace"){return;}
   // log event to counter
   $this->getCounter()->event_log($event->typology,$event->action,array_merge(["_obj"=>"cCountersCounterMeasurement","_id"=>$this->id,"_period"=>$this->period],$event->properties));
  }

 }

?>