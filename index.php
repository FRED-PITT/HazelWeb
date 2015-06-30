<?php
require_once 'vendor/Mobile-Detect/Mobile_Detect.php';
require_once 'util/Constants.inc';
require_once('util/FileUtilities.inc');

$device_type = 'computer';

if(isset($_GET['site_type'])) {
  $device_type = $_GET['site_type'];
} else {
  $detect = new Mobile_Detect;
  $device_type = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
  $script_version = $detect->getScriptVersion();
}

if($device_type == 'phone') {
  header('Location: ' . Constants::getSiteBase() . '/mobile/index.php');
} elseif($device_type == 'tablet') {
  header('Location: ' . Constants::getSiteBase() . '/mobile/index.php');
} else {
  
//   //Get the Experiment Space files
//   $exp_space_files = FileUtilities::get_files_in_dir(Constants::RESULTS_DIR . 'EXPERIMENT_SPACES/', '*.json');
  
//   $exp_space_title_arr = array();
//   $exp_space_info_arr = array();
  
//   if($exp_space_files !== NULL) {
//     foreach($exp_space_files as $exp_space_file) {
//       $str_data = file_get_contents(Constants::RESULTS_DIR . 'EXPERIMENT_SPACES/' . $exp_space_file);
//       $data = json_decode($str_data, true);
//       $id = $data['fred_experiment']['id'];
//       $exp_space_title_arr[$id] = $data['fred_experiment']['title'];
//       $exp_space_info_arr[$id] = $data['fred_experiment']['info'];
//     }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>HAZEL Web</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />	
	<link rel="stylesheet" type="text/css" href="<?php echo Constants::WHERE_IS_DOJO . 'dijit/themes/claro/claro.css'; ?>" media="screen">
	<style>




button {
	-webkit-transition: background-color 0.2s linear;
	border-radius:4px;
	-moz-border-radius: 4px 4px 4px 4px;
	-moz-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
	background-color: #E4F2FF;
	background-image: url("//ajax.googleapis.com/ajax/libs/dojo/1.7.4/dijit/themes/claro/form/images/button.png");
	background-position: center top;
	background-repeat: repeat-x;
	border: 1px solid #769DC0;
	padding: 2px 8px 4px;
	font-size:1em;
}

button:hover {
	background-color: #AFD9FF;
	color: #000000;
}

h1 {
	font-size:1.5em;
}

.break
{
	float:none;
	clear:both;
}

	</style>
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
	                <a href="<?php echo Constants::getSiteBase(); ?> title="HAZEL Home"">Home</a> | 
	                <a href="<?php echo Constants::getSiteBase() . '/contact.php'?>" title="Contact Us">Contact Us</a>
	            </li>
            </ul>
        </div><!-- end pitt-header -->
	</div><!-- end header_content_pane -->
	
	<div id="center_tab_cntnr" class="centerPanel" data-dojo-type="dijit/layout/TabContainer" data-dojo-props="region: 'center', tabPosition: 'bottom'">
	    <!-- Begin Healthcare Demand Tab -->
	    <div id="tab_panel_hc_demand" data-dojo-type="dijit/layout/ContentPane" title="Healthcare Demand">
		    <div id="border_cntnr_tab1" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab1_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Healthcare Demand</h1>
                </div>
                <div id="tab1_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_area"> 
                        <div id="hc_avail_chrt"></div>
                        <div id="hc_avail_chrt_legend"></div>
                    </div>
                    <div class="chart_area"> 
                        <div id="patnt_evac_chrt"></div>
                        <div id="patnt_evac_chrt_legend"></div>
                    </div>
                    
                </div> 
                <div id="tab1_footer_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'bottom'">
                    Current scenario is <span id="tab1_footer_scenario">Baseline</span>
                </div>  
            </div>				
		</div> <!-- End Healthcare Demand Tab -->
		
		<!-- Begin Practice Tab -->
	    <div id="tab_panel_practices" data-dojo-type="dijit/layout/ContentPane" title="Practices">
			<div id="border_cntnr_tab2" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab2_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Practice Information</h1>
                </div>
                
                <div id="tab2_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_area" > 
                        <div id="practices_chrt"></div>
                        <div id="practices_chrt_legend"></div>
                    </div>
                    <div class="chart_area"> 
                        <div id="open_practice_cap_chrt"></div>
                        <div id="open_practice_cap_chrt_legend"></div>
                    </div>
                    
                </div> 
                <div id="tab2_footer_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'bottom'">
                    Current scenario is <span id="tab2_footer_scenario">Baseline</span>
                </div>  
            </div>		
		</div>

		<div id="tab_panel_patient" data-dojo-type="dijit/layout/ContentPane" title="Additional Patient Information">
            <div id="border_cntnr_tab3" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab3_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
	                <h1>Patient Information</h1>
                </div>
                
                <div id="tab3_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <div class="chart_area_3_sideby_side" > 
                        <div id="emrgnc_vst_chrt"></div>
                        <div id="emrgnc_vst_chrt_legend"></div>
                    </div>
                    <div class="chart_area_3_sideby_side"> 
                        <div id="chronic_cndtn_patnt_chrt"></div>
                        <div id="chronic_cndtn_patnt_chrt_legend"></div>
                    </div>
                    <div class="chart_area_3_sideby_side"> 
                        <div id="hc_insrnc_patnt_chrt"></div>
                        <div id="hc_insrnc_patnt_chrt_legend"></div>
                    </div>
                    
                </div> 
                <div id="tab3_footer_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'bottom'">
                    Current scenario is <span id="tab3_footer_scenario">Baseline</span>
                </div>  
            </div>	
			
		</div>
	</div> <!-- End Practice Tab -->

	<div id="left_accrdn_cntnr" data-dojo-type="dijit/layout/AccordionContainer" data-dojo-props="minSize: 20, region: 'left', splitter: true">
        <div data-dojo-type="dijit/layout/AccordionPane" title="Select Scenario"  selected="true">
        <form id="frm_select_scenario">
            <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_baseline" checked="checked" value="FRRN1"/> 
            <label for="rb_baseline">Baseline</label><br />
            <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_20pct" value="FRRN2"/> 
            <label for="rb_20pct">Add&nbsp;Capacity&nbsp;20&#37;</label><br />
            <input type="radio" data-dojo-type="dijit/form/RadioButton" name="rb_scenario" id="rb_3vans" value="FRRN3"/> 
            <label for="rb_3vans">Activate&nbsp;3&nbsp;Vans</label><br />
            <hr />
            <button id="btn_chng_scenario" data-dojo-type="dijit/form/Button" type="button">
                Change Scenario
            </button>
        </form>     
        </div>
        
        <div data-dojo-type="dijit/layout/AccordionPane" title="Raw Data (Testing)">
            <div id="resultDiv">Results will go here</div>
        </div>
        <div data-dojo-type="dijit/layout/AccordionPane" data-dojo-props="disabled: true" title="Submitted Jobs">
            No jobs submitted during this session
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
<script src="<?php echo Constants::WHERE_IS_DOJO . 'dojo/dojo.js'; ?>"></script>
<script>
  require(["dojo/parser",   	
           "dijit/layout/BorderContainer",
           "dijit/layout/TabContainer", 
	       "dijit/layout/ContentPane",
	       "dijit/layout/AccordionContainer",
	       "dijit/layout/AccordionPane",
	       "dijit/form/RadioButton",
	       "dijit/form/Button",
	       "dijit/form/Form",
	       "custom/IndexModule",
	       
	       "dojo/domReady!"], 
	function(parser, BorderContainer, TabContainer, ContentPane, AccordionContainer, AccordionPane, RadioButton, Button, Form, IndexModule) {
      parser.parse();
    }
  );
</script>
</body>
</html>