<?php

include_once("geolib.php");

$title="GeoManager";
    $list=array(geoLink("../","Home"));
    $body=h3($title);
    $body.=geoList($list,'glist');

if(isset($_POST['cancelDebuggingAuthorization'])){
    GeoDebug::debugging(true);
    unset($_SESSION['geoDebugIsAuthorized']);
}    
    
if(isset($_POST['geoDebugPassword'])){
    if($_POST['geoDebugPassword']==GEO_DEBUG_PASSWORD){
        $_SESSION['geoDebugIsAuthorized']=TRUE;
    }
}

if(isset($_SESSION['geoDebugIsAuthorized'])){
    $action=Geo::val($_GET["action"]);
    
    if(isset($_GET["action"])){
        
         $actionArr=explode("_",$_GET["action"]);
        
         GeoDebug::db($actionArr,'actionArr');
         switch($actionArr[0]){
            case "dbOut":
                if($actionArr[1]) {
                    Geo::setSession("geoDbOut",$actionArr[1]);
                } else {
                    
                    Geo::setSession("geoDbOut");
                }
                break;
            case "end":
               Geo::setSession($actionArr[1]);
               Geo::setSession("geoDbOut");
            break;
            case "start":
               Geo::setSession($actionArr[1],TRUE);
        
            break;
        
         } 
         
    
     //geo::Redirect();
    
    }
    
    $sLink=new GeoLink();
    $sLink->setAtt("target","_self");
    if(GeoDebug::isOn()){
       $options[]=div("Debugging Session is ON","redbold");
       $options[]=$sLink->tag("?action=start_geoIsDebugSession","ReStart Debug Session");
       $options[]=$sLink->tag("?action=end_geoIsDebugSession","End Debug Session");
       $dbOptions[]=$sLink->tag("?action=dbOut_0","Save Debug For End");
       $dbOptions[1]=$sLink->tag("?action=dbOut_1","Echo debug variables");
       $dbOptions[2]=$sLink->tag("?action=dbOut_2","Return debug variables");
      
    } else {
       $options[]=div("Debugging Session is OFF","redbold");
       $options[]=$sLink->tag("?action=start_geoIsDebugSession","Start Debug Session");
    }
    $body.=Geo::ifVal($_SESSION['geoMessages'],div($_SESSION["geoMessages"])).
   div(
      geoList($options,"vlist").
      Geo::ifVal($dbOptions,geoList($dbOptions,'vlist')),
   "managerLists")
   ;
    unset($_SESSION['geoMessages']);
    
    if($_SESSION['geoDebugIsAuthorized']){
        $body.=geoForm(geoSubmit("Cancel Debugging Authorization",'cancelDebuggingAuthorization'));
    }  
    
} else {
    
  $body.=h4("Please enter the debugging password").geoForm(geoText("geoDebugPassword").geoSubmit(),null,'index.php');   
    
    
}

$css[]="/geolib.css";
$head=new GeoHead($title, $css);
//$head->setBase("_blank");
echo geoHtml(div($body,"page"),$head);

?>























