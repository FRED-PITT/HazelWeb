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
  header('Location: ' . Constants::getSiteBase() . '/m/contact.php');
} elseif($device_type == 'tablet') {
  header('Location: ' . Constants::getSiteBase() . '/m/contact.php');
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
        <div id="tab_panel_splash_page" data-dojo-type="dijit/layout/ContentPane" title="Contact Us">
            <div id="border_cntnr_splash_page" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_splash_page_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
                    <h1>HAZEL Contact Information</h1>                   
                </div>
                <div id="tab_splash_page_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                    <p><span class="subheading">For technical questions regarding this website, contact:</span></p>		
		            <p>
                        David Galloway<br/>
		                Graduate School of Public Health<br/>
		                University of Pittsburgh<br/>
		                130 DeSoto Street<br/>
		                717 Parran Hall<br/>
		                Pittsburgh, PA 15261<br/>
		                Tel: (412) 624-3695
		                Email: <a href="mailto:ddg5@pitt.edu?subject=HAZEL Website">ddg5@pitt.edu</a><br/><br/>
                    </p>

		            <p><span class="subheading">For any other questions related to the HAZEL project, please contact:</span></p>
		            <p>
                        Dr. Hasan Guclu, PhD<br/>
		                Graduate School of Public Health<br/>
		                University of Pittsburgh<br/>
		                130 DeSoto Street<br/>
		                705 Parran Hall<br/>
		                Pittsburgh, PA 15261<br/>
		                Email: <a href="mailto:gref@pitt.edu?subject=HAZEL Website">guclu@pitt.edu</a><br/>
		                Tel:  (412) 624-2178<br/>
		            </p>           
                </div> 
            </div>              
        </div> <!-- End Splash Tab -->	          
        
        <!-- Begin About Tab -->
        <div id="tab_panel_about_page" data-dojo-type="dijit/layout/ContentPane" title="About">
            <div id="border_cntnr_about_page" data-dojo-type="dijit/layout/BorderContainer" 
                 data-dojo-props="design: 'headline', gutters: true, liveSplitters: false">
                <div id="tab_about_page_header_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'top'">
                    <h1>About HAZEL</h1>
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
                </div>
                <div id="tab_team_page_center_content_pane" class="edgePanel" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region: 'center'">
                   <img class="centered" src="image/hazel_team.png" alt="HAZEL Team" />               
                </div> 
            </div>              
        </div> <!-- End Team Tab -->
	</div> 
</div>
<!-- configure Dojo -->
<script>
  // Instead of using data-dojo-config, we're creating a dojoConfig
  // object *before* we load dojo.js; they're functionally identical,
  // it's just easier to read this approach with a larger configuration.
  var dojoConfig = {
    async: true,
    parseOnLoad: false
  };
</script>

<!-- load dojo -->
<script type="text/javascript" src="<?php echo Constants::WHERE_IS_DOJO . 'dojo/dojo.js'; ?>"></script>
<script>
  require(["dojo/parser",   	
           "dijit/layout/BorderContainer",
           "dijit/layout/TabContainer", 
	       "dijit/layout/ContentPane",
	       "dojo/domReady!"], 
	function(parser, BorderContainer, TabContainer, ContentPane) {
      parser.parse();
    }
  );
</script>
</body>
</html>