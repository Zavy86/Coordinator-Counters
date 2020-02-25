<?php
/**
 * Counters - Counters Edit
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("counters-manage","dashboard");
 // get objects
 $counter_obj=new cCountersCounter($_REQUEST['idCounter']);
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(($counter_obj->id?api_text("counters_edit",$counter_obj->name):api_text("counters_edit-add")));
 // get form
 $form=$counter_obj->form_edit(["return"=>api_return(["scr"=>"counters_view"])]);
 // additional controls
 if($counter_obj->id){
  $form->addControl("button",api_text("form-fc-cancel"),api_return_url(["scr"=>"counters_view","idCounter"=>$counter_obj->id]));
  if(!$counter_obj->deleted){
   $form->addControl("button",api_text("form-fc-delete"),api_url(["scr"=>"controller","act"=>"delete","obj"=>"cCountersCounter","idCounter"=>$counter_obj->id]),"btn-danger",api_text("cCountersCounter-confirm-delete"));
  }else{
   $form->addControl("button",api_text("form-fc-undelete"),api_url(["scr"=>"controller","act"=>"undelete","obj"=>"cCountersCounter","idCounter"=>$counter_obj->id,"return"=>["scr"=>"counters_view"]]),"btn-warning");
   $form->addControl("button",api_text("form-fc-remove"),api_url(["scr"=>"controller","act"=>"remove","obj"=>"cCountersCounter","idCounter"=>$counter_obj->id]),"btn-danger",api_text("cCountersCounter-confirm-remove"));
  }
 }else{$form->addControl("button",api_text("form-fc-cancel"),api_url(["scr"=>"counters_list"]));}
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($form->render(),"col-xs-12");
 // add content to counter
 $app->addContent($grid->render());
 // renderize counter
 $app->render();
 // debug
 api_dump($counter_obj,"counter");
?>