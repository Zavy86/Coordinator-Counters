<?php
/**
 * Counters - Controller
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // debug
 api_dump($_REQUEST,"_REQUEST");
 // check if object controller function exists
 if(function_exists($_REQUEST['obj']."_controller")){
  // call object controller function
  call_user_func($_REQUEST['obj']."_controller",$_REQUEST['act']);
 }else{
  api_alerts_add(api_text("alert_controllerObjectNotFound",[MODULE,$_REQUEST['obj']."_controller"]),"danger");
  api_redirect("?mod=".MODULE);
 }

 /**
  * Counter controller
  *
  * @param string $action Object action
  */
 function cCountersCounter_controller($action){
  // check authorizations
  api_checkAuthorization("counters-manage","dashboard");
  // get object
  $counter_obj=new cCountersCounter($_REQUEST['idCounter']);
  api_dump($counter_obj,"counter object");
  // check object
  if($action!="store" && !$counter_obj->id){api_alerts_add(api_text("cCountersCounter-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=counters_list");}
  // execution
  try{
   switch($action){
    case "store":
     $counter_obj->store($_REQUEST);
     api_alerts_add(api_text("cCountersCounter-alert-stored"),"success");
     break;
    case "move":
     $counter_obj->move($_REQUEST["direction"]);
     api_alerts_add(api_text("cCountersCounter-alert-moved"),"success"); /** @todo specific alert? */
     break;
    case "delete":
     $counter_obj->delete();
     api_alerts_add(api_text("cCountersCounter-alert-deleted"),"warning");
     break;
    case "undelete":
     $counter_obj->undelete();
     api_alerts_add(api_text("cCountersCounter-alert-undeleted"),"warning");
     break;
    case "remove":
     $counter_obj->remove();
     api_alerts_add(api_text("cCountersCounter-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Counter action \"".$action."\" was not defined..");
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"counters_list","idCounter"=>$counter_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"counters_list","idCounter"=>$counter_obj->id]),"cCountersCounter-alert-error");
  }
 }

 /**
  * Counter Measurement controller
  *
  * @param string $action Object action
  */
 function cCountersCounterMeasurement_controller($action){
  // check authorizations
  api_checkAuthorization("counters-manage","dashboard");
  // get object
  $measurement_obj=new cCountersCounterMeasurement($_REQUEST['idMeasurement']);
  api_dump($measurement_obj,"counter measurement object");
  // check object
  if($action!="store" && !$measurement_obj->id){api_alerts_add(api_text("cCountersCounterMeasurement-alert-exists"),"danger");api_redirect("?mod=".MODULE."&scr=counters_list");}
  // execution
  try{
   switch($action){
    case "store":
     $measurement_obj->store($_REQUEST);
     api_alerts_add(api_text("cCountersCounterMeasurement-alert-stored"),"success");
     break;
    case "delete":
     $measurement_obj->delete();
     api_alerts_add(api_text("cCountersCounterMeasurement-alert-deleted"),"warning");
     break;
    case "undelete":
     $measurement_obj->undelete();
     api_alerts_add(api_text("cCountersCounterMeasurement-alert-undeleted"),"warning");
     break;
    case "remove":
     $measurement_obj->remove();
     api_alerts_add(api_text("cCountersCounterMeasurement-alert-removed"),"warning");
     break;
    default:
     throw new Exception("Counter measurement action \"".$action."\" was not defined..");   /** @todo rivedere url di return */
   }
   // redirect
   api_redirect(api_return_url(["scr"=>"counters_view","tab"=>"measurements","idCounter"=>$measurement_obj->fkCounter,"idMeasurement"=>$measurement_obj->id]));
  }catch(Exception $e){
   // dump, alert and redirect
   api_redirect_exception($e,api_url(["scr"=>"counters_view","tab"=>"measurements","idCounter"=>$measurement_obj->fkCounter,"idMeasurement"=>$measurement_obj->id]),"cCountersCounterMeasurement-alert-error");
  }
 }

?>