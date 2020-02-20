<?php
/**
 * Counters - Counters View (Informations)
 *
 * @package Coordinator\Modules\Counters
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    http://www.coordinator.it
 */

 // build informations description list
 $informations_dl=new strDescriptionList("br","dl-horizontal");
 $informations_dl->addElement(api_text("cCountersCounter-property-identifier"),api_tag("samp",$counter_obj->identifier));
 
?>