<?php
/**
 * Counters - Counters List
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("counters-usage","dashboard");
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // definitions
 $users_array=array();
 // set application title
 $app->setTitle(api_text("counters_list"));
 // definitions
 $counters_array=array();
 // build filter
 $filter=new strFilter();
 $filter->addSearch(["name","description","identifier"]);
 // build query object
 $query=new cQuery("counters__counters",$filter->getQueryWhere());
 $query->addQueryOrderField("order");
 // build pagination object
 $pagination=new strPagination($query->getRecordsCount());
 // cycle all results
 foreach($query->getRecords($pagination->getQueryLimits()) as $result_f){$counters_array[$result_f->id]=new cCountersCounter($result_f);}
 // build table
 $table=new strTable(api_text("counters_list-tr-unvalued"));
 $table->addHeader($filter->link(api_icon("fa-filter",api_text("filters-modal-link"),"hidden-link")),"text-center",16);
 $table->addHeader(api_text("cCountersCounter-property-name"),"nowrap");
 $table->addHeader(api_text("cCountersCounter-property-description"),null,"100%");
 $table->addHeader(api_text("cCountersCounter-property-identifier"),"nowrap text-right");
 if(api_checkAuthorization("counters-manage")){$table->addHeader("&nbsp;",null,16);}
 // cycle all counters
 foreach($counters_array as $counter_fobj){
  // build operation button
  $ob=new strOperationsButton();
  $ob->addElement(api_url(["scr"=>"controller","act"=>"move","direction"=>"up","obj"=>"cCountersCounter","idCounter"=>$counter_fobj->id,"return"=>["scr"=>"counters_list"]]),"fa-arrow-up",api_text("table-td-move-up"),($counter_fobj->order>1));
  $ob->addElement(api_url(["scr"=>"controller","act"=>"move","direction"=>"down","obj"=>"cCountersCounter","idCounter"=>$counter_fobj->id,"return"=>["scr"=>"counters_list"]]),"fa-arrow-down",api_text("table-td-move-down"),($counter_fobj->order!=count($counters_array)));
  $ob->addElement(api_url(["scr"=>"counters_edit","idCounter"=>$counter_fobj->id,"return"=>["scr"=>"counters_list"]]),"fa-pencil",api_text("table-td-edit"));
  if($counter_fobj->deleted){$ob->addElement(api_url(["scr"=>"controller","act"=>"undelete","obj"=>"cCountersCounter","idCounter"=>$counter_fobj->id,"return"=>["scr"=>"counters_list"]]),"fa-trash-o",api_text("table-td-undelete"),true,api_text("cCountersCounter-confirm-undelete"));}
  else{$ob->addElement(api_url(["scr"=>"controller","act"=>"delete","obj"=>"cCountersCounter","idCounter"=>$counter_fobj->id,"return"=>["scr"=>"counters_list"]]),"fa-trash",api_text("table-td-delete"),true,api_text("cCountersCounter-confirm-delete"));}
  // make table row class
  $tr_class_array=array();
  if($counter_fobj->id==$_REQUEST['idCounter']){$tr_class_array[]="currentrow";}
  if($counter_fobj->deleted){$tr_class_array[]="deleted";}
  // make counters row
  $table->addRow(implode(" ",$tr_class_array));
  $table->addRowFieldAction(api_url(["scr"=>"counters_view","idCounter"=>$counter_fobj->id]),"fa-search",api_text("table-td-view"));
  $table->addRowField($counter_fobj->name,"nowrap");
  $table->addRowField($counter_fobj->description,"truncate-ellipsis");
  $table->addRowField(api_tag("samp",$counter_fobj->identifier),"nowrap text-right");
  if(api_checkAuthorization("counters-manage")){$table->addRowField($ob->render(),"nowrap text-right");}
 }
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($filter->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($table->render(),"col-xs-12");
 $grid->addRow();
 $grid->addCol($pagination->render(),"col-xs-12");
 // add content to counter
 $app->addContent($grid->render());
 // renderize counter
 $app->render();
 // debug
 api_dump($query,"query");
?>