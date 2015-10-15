<?php
require_once 'vendor/Mobile-Detect/Mobile_Detect.php';
require_once 'util/Constants.inc';
require_once('util/FileUtilities.inc');

$device_type = 'computer';

if(isset($_GET['site_type'])) {
  $device_type = $_GET['site_type'];
} else {
  $detect = new Mobile_Detect();
  $device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
  $script_version = $detect->getScriptVersion();
}

if($device_type == 'phone') {
  header('Location: ' . Constants::getSiteBase() . '/m/index.php');
} elseif($device_type == 'tablet') {
  header('Location: ' . Constants::getSiteBase() . '/m/index.php');
} else {

}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>HAZEL Web</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php echo Constants::WHERE_IS_DOJO . 'dijit/themes/claro/claro.css'; ?>" media="screen">
</head>
<body class="claro">
<form>
    <input id="api_url" type="hidden" value="<?php echo Constants::WHERE_IS_FREDWEB_API; ?>" />
</form>
<div id="border_cntnr_main" class="demoLayout" data-dojo-type="dijit/layout/BorderContainer" 
     data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
    <!-- Header Panel -->
    <div id="header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	    <!-- This is the Pitt Banner. The right-hand links are listed at the bottom of the HTML page. -->
        <!-- ======================================================================================== -->
        <div id="pitt_header" class="white">
            <!-- PITT HEADER RIGHT-HAND LINKS -->
            <!-- ======================================================================================== -->
            <ul id="hazel_links">
	            <li id="p_home">
	                <a href="http://www.pitt.edu/" title="Pitt Home">Pitt Home</a> | 
	                <a href="<?php echo Constants::getSiteBase(); ?>" title="HAZEL Home">Home</a> | 
	                <a href="<?php echo Constants::getSiteBase() . '/contact.php'; ?>" title="Contact Us">Contact Us</a>
	            </li>
            </ul>
        </div><!-- end pitt-header -->
	</div><!-- end header_content_pane -->
	
    <!-- Begin Tab Container -->
	<div id="center_tab_cntnr" class="centerPanel" data-dojo-type="dijit/layout/TabContainer" data-dojo-props="region: 'center', tabPosition: 'bottom'">

        <!-- Begin Splash Tab -->
        <div id="tab_panel_splash_page" data-dojo-type="dijit/layout/ContentPane" title="Welcome">
            <div id="border_cntnr_splash_page" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_splash_page_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
                    <h1>Welcome to HAZEL</h1>
                    Current scenario is <span id="tab_splash_page_scenario">Baseline</span>
                </div>
                <div id="tab_splash_page_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <video id="simulation_video" width="640" height="640" controls="controls">
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>               
                </div> 
            </div>              
        </div> <!-- End Splash Tab -->
	    
        <!-- Begin Healthcare Demand Tab -->
	    <div id="tab_panel_hc_demand" data-dojo-type="dijit/layout/ContentPane" title="Healthcare Demand">
		    <div id="border_cntnr_hc_demand" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_hc_demand_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Healthcare Demand</h1>
                    Current scenario is <span id="tab_hc_demand_scenario">Baseline</span>
                </div>
                <div id="tab_hc_demand_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_title">Healthcare Deficit</div>
                    <div class="chart_area"> 
                        <div id="hc_avail_chrt"></div>
                        <div id="hc_avail_chrt_legend"></div>
                    </div>
                    <div class="chart_title">Patients</div>
                    <div class="chart_area"> 
                        <div id="patnt_evac_chrt"></div>
                        <div id="patnt_evac_chrt_legend"></div>
                    </div>                   
                </div> 
            </div>				
		</div> <!-- End Healthcare Demand Tab -->
		
		<!-- Begin Practice Tab -->
	    <div id="tab_panel_practices" data-dojo-type="dijit/layout/ContentPane" title="Practices">
			<div id="border_cntnr_practices" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_practices_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Practice Information</h1>
                    Current scenario is <span id="tab_practices_scenario">Baseline</span>
                </div>
                
                <div id="tab_practices_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_title">Practices</div>
                    <div class="chart_area" > 
                        <div id="practices_chrt"></div>
                        <div id="practices_chrt_legend"></div>
                    </div>
                    <div class="chart_title">Open Practice Capacity</div>
                    <div class="chart_area"> 
                        <div id="open_practice_cap_chrt"></div>
                        <div id="open_practice_cap_chrt_legend"></div>
                    </div>
                    
                </div> 
            </div>		
		</div>

		<div id="tab_panel_patient" data-dojo-type="dijit/layout/ContentPane" title="Additional Patient Information">
            <div id="border_cntnr_patient" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_patient_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Patient Information</h1>
                    Current scenario is <span id="tab_patient_scenario">Baseline</span>
                </div>              
                <div id="tab_patient_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_title">Healthcare Deficit for Patients by Chronic Condition</div>
                    <div class="chart_area"> 
                        <div id="chronic_cndtn_patnt_chrt"></div>
                        <div id="chronic_cndtn_patnt_chrt_legend"></div>
                    </div>
                    <div class="chart_title">Healthcare Deficit for Patients by Health Insurance</div>
                    <div class="chart_area"> 
                        <div id="hc_insrnc_patnt_chrt"></div>
                        <div id="hc_insrnc_patnt_chrt_legend"></div>
                    </div>                
                </div>  
            </div>				
		</div><!-- End Practice Tab -->
        
        <!-- Begin About Tab -->
        <div id="tab_panel_about_page" data-dojo-type="dijit/layout/ContentPane" title="About">
            <div id="border_cntnr_about_page" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_about_page_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
                    <h1>About HAZEL</h1>
                    Current scenario is <span id="tab_about_page_scenario">Baseline</span>
                </div>
                <div id="tab_about_page_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div>
                        <figure style="float: right; display: inline-block;" >
                            <img src="image/access_deficit.tiff" 
                                 alt="Visual representation of the access deficit post-Sandy (gap between population need and healthcare provider capacity)"
                                 title="Visual representation of the access deficit post-Sandy (gap between population need and healthcare provider capacity)"/>
                            <figcaption>
                                Conceptual representation of relationship between provider capacity<br /> 
                                and population need for primary care before and after Superstorm Sandy.
                            </figcaption>
                        </figure>
                        <p class="about">
                        Disasters can disrupt primary care services, resulting in a gap between the ability of healthcare providers to deliver
                        care, and the increased healthcare needs of the population. <span class="h3_about">This gap is called the access deficit.</span>
                        The impact of Superstorm Sandy on primary care capacity in the Rockaway Peninsula, Queens, New York provides a unique case 
                        study for understanding the access deficit and how to reduce it.
                        </p>
                    </div>
                    <p class="about">
                        <span class="h3_about">HAZEL (hazard-area primary care locator)</span> is a mod­eling tool designed to simulate primary care strategies 
                        implemented and/or considered during and after Su­perstorm Sandy in the Rockaways; with further devel­opment, HAZEL can become widely 
                        available to help in restoring post-disaster primary care access for other locales.
                    </p>
                    <p class="about">
                        <span class="h3_about">HAZEL parameters</span> describe the <span class="h3_about">access deficit</span> for pri­mary care services 
                        resulting from the dynamics of pro­vider capacity and population need during and after a disaster. Data include: population demographics,
                        evac­uation patterns, health insurance source and status, healthcare utilization, local infrastructure, primary care sites, pharmacies, 
                        topology, and relevant laws, policies, and emergency orders. The HAZEL model for Superstorm Sandy in the Rockaways can be adapted for 
                        other geographic settings and other future disasters.
                    </p>
                    <hr />
                    <p class="disclaimer">
                        This project is funded through the University of Pittsburgh Center for Public Health Practice by the Assistant Secretary for Preparedness
                        and Response Cooperative Agreement Number 1 HITEP130004-01-00.<br />                        
                        Data for this study was provided by the New York City Department of Health and Mental Hygiene (NYC DOHMH). The opinions, results, 
                        findings and/or interpretations of data contained herein are solely the responsibility of the authors and do not represent the opinions,
                        interpretation, or policy of NYC DOHMH or the City of New York.
                    </p>                        
                </div> 
            </div>              
        </div> <!-- End About Tab -->
        
        <!-- Begin Team Tab -->
        <div id="tab_panel_team_page" data-dojo-type="dijit/layout/ContentPane" title="Team">
            <div id="border_cntnr_team_page" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_team_page_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
                    <h1>HAZEL Team</h1>
                    Current scenario is <span id="tab_team_page_scenario">Baseline</span>
                </div>
                <div id="tab_team_page_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                   <img class="centered" src="image/hazel_team.png" alt="HAZEL Team" />               
                </div> 
            </div>              
        </div> <!-- End Team Tab -->
	</div> 
    
	<div id="left_accrdn_cntnr" data-dojo-id="left_accrdn_cntnr" data-dojo-type="dijit/layout/AccordionContainer" data-dojo-props="minSize: 20, region: 'left', splitter: true">
        <div data-dojo-type="dijit/layout/ContentPane" id="cntnt_pn_slct_scnrio" data-dojo-id="cntnt_pn_slct_scnrio" title="Select Scenario"  selected="true">
            <form id="frm_selected_scenario">
                <fieldset>
                    <legend>Selected Scenarios</legend>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_baseline" checked="checked" value="FRRN1"/> 
                    <label for="rb_baseline">Baseline</label><br />
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_50pct" value="FRRN2"/> 
                    <label for="rb_50pct">Add&nbsp;Capacity&nbsp;50&#37;</label><br />
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_6vans_actual" value="FRRN3"/> 
                    <label for="rb_6vans_actual">Activate&nbsp;6&nbsp;Vans<br /><span style="padding-left: 1em; font-style: italic;">(Actual Locations)</span></label><br />
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_6vans_deficit_based" value="FRRN4"/> 
                    <label for="rb_6vans_deficit_based">Activate&nbsp;6&nbsp;Vans<br /><span style="padding-left: 1em; font-style: italic;">(Deficit-based)</span></label><br />
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_6vans_population_based" value="FRRN5"/> 
                    <label for="rb_6vans_population_based">Activate&nbsp;6&nbsp;Vans<br /><span style="padding-left: 1em; font-style: italic;">(Population-based)</span></label>
                </fieldset>
                <button id="btn_chng_scenario" data-dojo-type="dijit/form/Button" type="button">
                    Change Scenario
                </button>    
            </form>
            <div data-dojo-type="dijit/ProgressBar" style="width:120px; height:10px; font-size:x-small;" data-dojo-props="maximum:100"
                 data-dojo-id="prgrss_bar_selected_scenario" id="prgrss_bar_selected_scenario"></div>   
        </div>
        
        <div data-dojo-type="dijit/layout/ContentPane" id="cntnt_pn_chs_scnrio" data-dojo-id="cntnt_pn_chs_scnrio" title="Custom Scenario">
            <form id="frm_choose_scenario">
                <fieldset>
                    <legend>Add Mobile Vans</legend>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_mobile_van" id="rb_mobile_van_true" value="true"/> 
                    <label for="rb_mobile_van_true">Yes</label>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_mobile_van" id="rb_mobile_van_false" checked="checked" value="false"/> 
                    <label for="rb_mobile_van_false">No</label><br />                       
                </fieldset>
                <fieldset>
                    <legend>Van Count</legend>                
                    <input data-dojo-type="dijit/form/NumberSpinner"
                           id="num_spn_mobile_van_cnt"
                           value="1"
                           style="width: 10em;"
                           data-dojo-props="maxLength:1, disabled:true, smallDelta:1, constraints:{min:1, max:6, places:0}"
                           name="num_spn_mobile_van_cnt" />
                </fieldset>      
                <fieldset>
                    <legend>Exceed Capacity</legend>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_exceed_cap" id="rb_exceed_cap_true" value="true"/> 
                    <label for="rb_exceed_cap_true">Yes</label>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_exceed_cap" id="rb_exceed_cap_false" checked="checked" value="false"/> 
                    <label for="rb_exceed_cap_false">No</label><br />                       
                </fieldset>
                <fieldset>
                    <legend>&#37;&nbsp;Addtnl&nbsp;Cap</legend>                
                    <input data-dojo-type="dijit/form/NumberSpinner"
                           id="num_spn_exceed_cap_pct"
                           value="10"
                           style="width: 10em;"
                           data-dojo-props="maxLength:2, disabled:true, smallDelta:10, constraints:{min:10, max:90, places:0}"
                           name="num_spn_exceed_cap_pct" />
                </fieldset>
                <span style="text-align:center;">
                <button id="btn_chng_chsn_scenario" data-dojo-type="dijit/form/Button" type="button">
                    Change Scenario
                </button> 
                </span>    
            </form>
            <div data-dojo-type="dijit/ProgressBar" style="width:120px; height:10px; font-size:x-small; visibility: hidden;" data-dojo-props="maximum:100"
                 data-dojo-id="prgrss_bar_selected_chsn_scenario" id="prgrss_bar_selected_chsn_scenario"></div> 
        </div>
                
        <div data-dojo-type="dijit/layout/ContentPane" id="cntnt_pn_results" data-dojo-id="cntnt_pn_results" title="Results Data">
            <div id="resultDiv">Results will go here</div>
        </div>
    </div><!-- end AccordionContainer -->
   
</div>
<!-- configure Dojo -->
<script>
  // Instead of using data-dojo-config, we're creating a dojoConfig
  // object *before* we load dojo.js; they're functionally identical,
  // it's just easier to read this approach with a larger configuration.
  var dojoConfig = {
    async: true,
    parseOnLoad: false,
    // This code registers the correct location of the "custom"
    // package
    packages: [ 
      {
        name: 'custom',
        location: location.pathname.replace(/\/[^/]+$/, '') + '/js/custom/'
      }
    ]
  };
</script>

<!-- load dojo -->
<script type="text/javascript" src="<?php echo Constants::WHERE_IS_DOJO . 'dojo/dojo.js'; ?>"></script>
<script>
  require(["dojo/parser",   	
           "dijit/layout/BorderContainer",
           "dijit/layout/TabContainer", 
	       "dijit/layout/ContentPane",
	       "dijit/layout/AccordionContainer",
           "dijit/ProgressBar",
	       "dijit/form/RadioButton",
	       "dijit/form/Button",
	       "dijit/form/NumberSpinner",
	       "dijit/form/Form",
	       "custom/IndexModule",      
	       "dojo/domReady!"], 
	function(parser, BorderContainer, TabContainer, ContentPane, AccordionContainer, ProgressBar, RadioButton, Button, NumberSpinner, Form, IndexModule) {
      parser.parse();
    }
  );
</script>
</body>
</html>