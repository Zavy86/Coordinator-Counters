<?php
/**
 * Counters - Counters View
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */
 api_checkAuthorization("counters-usage","dashboard");
 // get objects
 $counter_obj=new cCountersCounter($_REQUEST['idCounter']);
 // check objects
 if(!$counter_obj->id){api_alerts_add(api_text("cCountersCounter-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=counters_list");}
 // deleted alert
 if($counter_obj->deleted){api_alerts_add(api_text("cCountersCounter-warning-deleted"),"warning");}
 // include module template
 require_once(MODULE_PATH."template.inc.php");
 // set application title
 $app->setTitle(api_text("counters_view",$counter_obj->name));
 // check for tab
 if(!defined(TAB)){define("TAB","informations");}
 // build counters description list
 $left_dl=new strDescriptionList("br","dl-horizontal");
 $left_dl->addElement(api_text("cCountersCounter-property-name"),api_tag("strong",$counter_obj->name));
 $left_dl->addElement(api_text("cCountersCounter-property-identifier"),api_tag("samp",$counter_obj->identifier));
 // build right description list
 $right_dl=new strDescriptionList("br","dl-horizontal");
 if($counter_obj->description){$right_dl->addElement(api_text("cCountersCounter-property-description"),nl2br($counter_obj->description));}
 // include tabs
 require_once(MODULE_PATH."counters_view-informations.inc.php");
 require_once(MODULE_PATH."counters_view-measurements.inc.php");
 // build view tabs
 $tab=new strTab();
 $tab->addItem(api_icon("fa-flag-o")." ".api_text("counters_view-tab-informations"),$informations_dl->render(),("informations"==TAB?"active":null));
 $tab->addItem(api_icon("fa-line-chart")." ".api_text("counters_view-tab-measurements"),$measurements_table->render(),("measurements"==TAB?"active":null));
 $tab->addItem(api_icon("fa-file-text-o")." ".api_text("counters_view-tab-logs"),api_logs_table($counter_obj->getLogs((!$_REQUEST['all_logs']?10:null)))->render(),("logs"==TAB?"active":null));
 // build grid object
 $grid=new strGrid();
 $grid->addRow();
 $grid->addCol($left_dl->render(),"col-xs-12 col-md-5");
 $grid->addCol($right_dl->render(),"col-xs-12 col-md-7");
 $grid->addRow();
 $grid->addCol($tab->render(),"col-xs-12");
 // add content to counter
 $app->addContent($grid->render());
 // renderize counter
 $app->render();
 // debug
 api_dump($selected_measurement_obj,"selected measurement");
 api_dump($counter_obj,"counter");
?>