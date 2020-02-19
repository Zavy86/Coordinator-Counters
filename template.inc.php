<?php
/**
 * Counters - Template
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 // build application
 $app=new strApplication();
 // build nav object
 $nav=new strNav("nav-tabs");
 // dashboard
 $nav->addItem(api_icon("fa-th-large",null,"hidden-link"),api_url(["scr"=>"dashboard"]));
 // counters
 if(api_script_prefix()=="counters"){
  $nav->addItem(api_text("nav-counters-list"),api_url(["scr"=>"counters_list"]));
  // operations
  if($counter_obj->id && in_array(SCRIPT,array("counters_view","counters_edit"))){
   $nav->addItem(api_text("nav-operations"),null,null,"active");
   $nav->addSubItem(api_text("nav-counters-operations-edit"),api_url(["scr"=>"counters_edit","idCounter"=>$counter_obj->id]),(api_checkAuthorization("counters-manage")));
   $nav->addSubSeparator();
   $nav->addSubItem(api_text("nav-counters-operations-measurement_add"),api_url(["scr"=>"counters_view","tab"=>"measurements","act"=>"measurement_add","idCounter"=>$counter_obj->id]),(api_checkAuthorization("counters-manage")));
  }else{
   $nav->addItem(api_text("nav-counters-add"),api_url(["scr"=>"counters_edit"]),(api_checkAuthorization("counters-manage")));
  }
 }
 // add nav to html
 $app->addContent($nav->render(false));
?>