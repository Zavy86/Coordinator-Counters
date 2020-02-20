<?php
/**
 * Counters - Counters View (Measurements)
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // build measurements description list
 $measurements_table=new strTable(api_text("counters_view-measurements-tr-unvalued"));
 $measurements_table->addHeader(api_text("cCountersCounterMeasurement-property-period"),"nowrap");
 $measurements_table->addHeader(api_text("counters_view-measurements-th-value"),"nowrap text-right");
 $measurements_table->addHeader(api_text("cCountersCounterMeasurement-property-current"),"nowrap text-right");
 $measurements_table->addHeader(api_text("cCountersCounterMeasurement-property-previous"),"nowrap text-right");
 $measurements_table->addHeader("&nbsp;",null,"100%");

 // cycle all measurements
 foreach($counter_obj->getMeasurements() as $measurement_fobj){ /** @todo aggiungere tasto archivio e limitare a 10 */
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"counters_view","tab"=>"measurements","act"=>"measurement_edit","idCounter"=>$counter_obj->id,"idMeasurement"=>$measurement_fobj->id]),"fa-pencil",api_text("table-td-edit"),(api_checkAuthorization("counters-manage")));
  $ob->addElement(api_url(["scr"=>"controller","act"=>"remove","obj"=>"cCountersCounterMeasurement","idCounter"=>$counter_obj->id,"idMeasurement"=>$measurement_fobj->id,"return"=>["scr"=>"counters_view","tab"=>"measurements","idCounter"=>$counter_obj->id]]),"fa-trash",api_text("table-td-remove"),(api_checkAuthorization("counters-manage")),api_text("cCountersCounterMeasurement-confirm-remove"));
  // make table row class
  $tr_class_array=array();
  if($measurement_fobj->id==$_REQUEST['idMeasurement']){$tr_class_array[]="currentrow";}
  if($measurement_fobj->deleted){$tr_class_array[]="deleted";}
  // make counters row
  $measurements_table->addRow(implode(" ",$tr_class_array));
  $measurements_table->addRowField(api_tag("samp",$measurement_fobj->period),"nowrap");
  $measurements_table->addRowField(api_number_format($measurement_fobj->getValue(),0),"nowrap text-right");
  $measurements_table->addRowField(api_number_format($measurement_fobj->current,0),"nowrap text-right");
  $measurements_table->addRowField(api_number_format($measurement_fobj->previous,0),"nowrap text-right");
  $measurements_table->addRowField("&nbsp;","truncate-ellipsis");
  $measurements_table->addRowField($ob->render(),"nowrap text-right");
 }

 // check for version actions
 if(in_array(ACTION,["measurement_add","measurement_edit"]) && api_checkAuthorization("counters-manage")){
  // get selected measurement
  $selected_measurement_obj=new cCountersCounterMeasurement($_REQUEST['idMeasurement']);
  // get form
  $form=$selected_measurement_obj->form_edit(["return"=>["scr"=>"counters_view","tab"=>"measurements","idCounter"=>$counter_obj->id]]);
  // replace fkCounter
  $form->removeField("fkCounter");
  $form->addField("hidden","fkCounter",null,$counter_obj->id);
  // additional controls
  $form->addControl("button",api_text("form-fc-cancel"),"#",null,null,null,"data-dismiss='modal'");
  if($selected_measurement_obj->id){$form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"controller","act"=>"remove","obj"=>"cCountersCounterMeasurement","idCounter"=>$counter_obj->id,"idMeasurement"=>$selected_measurement_obj->id,"return"=>["scr"=>"counters_view","tab"=>"measurements","idCounter"=>$counter_obj->id]]),"btn-danger",api_text("cCountersCounterMeasurement-confirm-remove"));}
  // build modal
  $modal=new strModal(api_text("counters_view-measurements-modal-title-".($selected_measurement_obj->id?"edit":"add"),$counter_obj->name),null,"counters_view-measurements");
  $modal->setBody($form->render(1));
  // add modal to counter
  $app->addModal($modal);
  // modal scripts
  $app->addScript("$(function(){\$('#modal_counters_view-measurements').modal({show:true,backdrop:'static',keyboard:false});});");
 }

?>