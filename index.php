<?php
/**
* Geolib settings manager
 *
 * PHP Version 5.6
 *
 * @category GeolibFile
 * @package  Geolib
 * @author   Peter Pitchford <peter@geotonics.com>
 * @license  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @link     http://geotonics.com/#geolib
*/
require_once "geolib.php";

$title="GeoManager";
    $list=array(
        geoLink("../", "Home"),
        geoLink("session.php",'Session')
    );
    $body=h3($title);
   

if (isset($_POST['cancelDebuggingAuthorization'])) {
    GeoDebug::debugging(true);
    Geo::setSession('debugIsAuthorized');
}
    
if (isset($_POST['geoDebugPassword'])) {
    if ($_POST['geoDebugPassword']==GEO_DEBUG_PASSWORD) {
        Geo::setSession('debugIsAuthorized', true);
        GeoDebug::debugging();
    }
}

if (Geo::session('debugIsAuthorized')) {
    $action=Geo::val($_GET["action"]);
    
    if (isset($_GET["action"])) {
        $actionArr=explode("_", $_GET["action"]);
        
        switch($actionArr[0]){
            case "dbOut":
                if ($actionArr[1]) {
                    Geo::setSession("dbOut", $actionArr[1]);
                } else {
                    Geo::setSession("dbOut");
                }
                break;
            case "end":
                Geo::setSession($actionArr[1]);
                Geo::setSession("dbOut");
                break;
            case "start":
                Geo::setSession($actionArr[1], true);
                break;
        }
        Geo::redirect();
    }
    $itemClasses["link_".Geo::session("dbOut")]="selectedLink";
    
    $sLink=new GeoLink();
    $sLink->setAtt("target", "_self");
    if (GeoDebug::isOn()) {
        $dbOut=Geo::session("dbOut");
        $options[]=span("Debugging Session is ON", 'redbold');
        $options[]=$sLink->tag("?action=start_isDebugSession", "ReStart Debug Session");
        $options[]=$sLink->tag("?action=end_isDebugSession", "End Debug Session");
        $dbOptions["link_"]=$sLink->tag("?action=dbOut_0", "Save Debug For End");
        $dbOptions["link_1"]=$sLink->tag("?action=dbOut_1", "Echo debug variables");
        $dbOptions["link_2"]=$sLink->tag("?action=dbOut_2", "Return debug variables");
       
    } else {
        $options[]=span("Debugging Session is OFF", "redbold");
        $options[]=$sLink->tag("?action=start_isDebugSession", "Start Debug Session");
    }
    $body.=
    div(
        geoList($list).
        geoList($options).
        geoIf($dbOptions, geoList($dbOptions, $itemClasses)),
        "managerLists"
    );
    
    if (Geo::session('debugIsAuthorized')) {
        $body.=geoForm(geoSubmit("Cancel Debugging Authorization", 'cancelDebuggingAuthorization'));
    }
    
} else {
    $body.=h4("Please enter the debugging password").
      geoForm(geoText("geoDebugPassword").geoSubmit(), null, 'index.php');
}


$head=new GeoHead($title, "geolib.css");
echo geoHtml(div($body, "geoLibPage"), $head);
