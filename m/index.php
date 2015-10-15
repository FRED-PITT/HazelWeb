<?php
require_once 'util/ConstantsMobile.inc';

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/index.css" media="screen" />   
	<title>HAZEL Web Mobile</title>
</head>
<body>
<form>
    <input id="api_url" type="hidden" value="<?php echo ConstantsMobile::WHERE_IS_FREDWEB_API; ?>" />
</form>

<div id="main_view" data-dojo-type="dojox/mobile/View">

    <div id="home_view" data-dojo-type="dojox/mobile/SwapView">
        <h1 data-dojo-type="dojox/mobile/Heading" data-dojo-props="fixed:'top'">HAZEL Web Mobile</h1>
        <!-- Header Panel -->
        <!-- This is the Pitt Banner. The right-hand links are listed at the bottom of the HTML page. -->
        <!-- ======================================================================================== -->
        <div id="pitt_header" class="white">
            <!-- PITT HEADER RIGHT-HAND LINKS -->
            <!-- ======================================================================================== -->
            <ul id="hazel_links">
                <li id="p_home">
                    <a href="http://www.pitt.edu/" title="Pitt Home">Pitt Home</a> | 
                    <a href="<?php echo ConstantsMobile::getSiteBase(); ?>" title="HAZEL Home">Home</a> | 
                    <a href="<?php echo ConstantsMobile::getSiteBase() . '/contact.php'; ?>" title="Contact Us">Contact Us</a>
                </li>
            </ul>
        </div><!-- end pitt-header -->
    
        <h2 data-dojo-type="dojox/mobile/RoundRectCategory">Graphs</h2>
        <ul data-dojo-type="dojox/mobile/RoundRectList">
            <li data-dojo-type="dojox/mobile/ListItem" data-dojo-props="icon:'image/Line_Graph_1.png', moveTo:'demand_view', label:'Demand'"></li>
            <li data-dojo-type="dojox/mobile/ListItem" data-dojo-props="icon:'image/Line_Graph_2.png', moveTo:'practices_view', label:'Practices'"></li>
            <li data-dojo-type="dojox/mobile/ListItem" data-dojo-props="icon:'image/Line_Graph_3.png', moveTo:'patient_view', label:'Patients'"></li>
        </ul>
        
        <div data-dojo-type="dojox/mobile/ContentPane">
            <h3 style="text-align: right;">Current Scenario: <span id="home_view_scenario">Baseline</span></h3>
            <h4 style="text-align: right;">Swipe left to change ...</h4>
        </div>  
    </div><!-- End of home_view -->

    <div id="change_scenario_view" data-dojo-type="dojox/mobile/SwapView">
        <h1 data-dojo-type="dojox/mobile/Heading">Change Scenario</h1> 
        <div data-dojo-type="dojox/mobile/FormLayout" data-dojo-props="columns:'one', rightAlign:true">
            <h3>Choose a scenario:</h3>
            <fieldset>   
                <input type="radio" id="rb_baseline" data-dojo-type="dojox/mobile/RadioButton" name="mobileRadio" value="FRRN1" checked="true">
                <label for="rb_baseline">Baseline</label><br />
                <input type="radio" id="rb_50pct" data-dojo-type="dojox/mobile/RadioButton" name="mobileRadio" value="FRRN2">
                <label for="rb_50pct">Add&nbsp;Capacity&nbsp;50&#37;</label><br />
                <input type="radio" id="rb_6vans_actual" data-dojo-type="dojox/mobile/RadioButton" name="mobileRadio" value="FRRN3">
                <label for="rb_6vans_actual">Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Actual Locations)</span></label><br />
                <input type="radio" id="rb_6vans_deficit_based" data-dojo-type="dojox/mobile/RadioButton" name="mobileRadio" value="FRRN4">
                <label for="rb_6vans_deficit_based">Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Deficit-based)</span></label><br />
                <input type="radio" id="rb_6vans_population_based" data-dojo-type="dojox/mobile/RadioButton" name="mobileRadio" value="FRRN5">
                <label for="rb_6vans_population_based">Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Population-based)</span></label>
            </fieldset>
        </div>
    </div><!-- End of change_scenario_view -->


    <div id="results_view" data-dojo-type="dojox/mobile/SwapView">
      <h1 data-dojo-type="dojox/mobile/Heading">Results</h1>
      <h3>Raw JSON</h3>
      <hr />
      <div data-dojo-type="dojox/mobile/ScrollablePane"
           data-dojo-props="height:'100%',
                            roundCornerMask:true,
                            radius:'5'">
          <div id="resultDiv" data-dojo-type="dojox/mobile/ContentPane"
               style="margin:5px 9px 7px 9px; padding:8px;">
               Results will go here
          </div>
      </div>
      
<!--       <h2 data-dojo-type="dojox/mobile/RoundRectCategory">Results (Raw JSON)</h2> -->
<!--       <div id="resultDiv">Results will go here</div> -->
    </div><!-- End of results_view -->

    <div data-dojo-type="dojox/mobile/PageIndicator"
         data-dojo-props='fixed:"bottom"'></div>
</div><!-- End main_view -->

<!-- Healthcare Demand Accordion Page -->
<div id="demand_view" data-dojo-type="dojox/mobile/View">
    <h1 data-dojo-type="dojox/mobile/Heading" data-dojo-props="back:'Home', moveTo:'home_view'">Healthcare Demand</h1>
    
    <div id="hazel_demand_accrdn" data-dojo-type="dojox/mobile/Accordion" data-dojo-props='singleOpen:true'>
        <!-- Begin Healthcare Demand Content -->
        <div id="accrdn_pnl_hc_avail" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Availability', height:'400px', selected:true">
            <div class="chart_area"> 
                <div id="hc_avail_chrt"></div>
                <div id="hc_avail_chrt_legend"></div>
            </div> 
        </div>    
        <div id="accrdn_pnl_patnt_evac" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Evacuation', height:'400px'">
            <div data-dojo-type="dojox/mobile/ContentPane">
                <div id="patnt_evac_chrt"></div>
                <div id="patnt_evac_chrt_legend"></div>
            </div>             
        </div>
    </div>
</div>

<!-- Practice Information Accordion Page -->
<div id="practices_view" data-dojo-type="dojox/mobile/View">
    <h1 data-dojo-type="dojox/mobile/Heading" data-dojo-props="back:'Home', moveTo:'home_view'">Practice Information</h1>
    
    <div id="hazel_practices_accrdn" data-dojo-type="dojox/mobile/Accordion" data-dojo-props='singleOpen:true'>
        <!-- Begin Healthcare Demand Content -->
        <div id="accrdn_pnl_prctc" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Practices', height:'400px', selected:true">
            <div class="chart_area" > 
                <div id="practices_chrt"></div>
                <div id="practices_chrt_legend"></div>
            </div>
        </div>    
        <div id="accrdn_pnl_opn_prctc" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Open Practice Capacity', height:'400px'">
            <div class="chart_area"> 
                <div id="open_practice_cap_chrt"></div>
                <div id="open_practice_cap_chrt_legend"></div>
            </div>           
        </div>
    </div>
</div>

<!-- Additional Patient Information Accordion Page -->
<div id="patient_view" data-dojo-type="dojox/mobile/View">
    <h1 data-dojo-type="dojox/mobile/Heading" data-dojo-props="back:'Home', moveTo:'home_view'">Additional Patient Information</h1>
    
    <!-- This is the main Hazel Accordion Page -->
    <div id="hazel_patient_accrdn" data-dojo-type="dojox/mobile/Accordion" data-dojo-props='singleOpen:true'>
        <!-- Begin Healthcare Demand Content -->
        <div id="accrdn_pnl_emrgnc_vst" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Emergency Room Visits', height:'400px', selected:true">
            <div class="chart_area" > 
                <div id="emrgnc_vst_chrt"></div>
                <div id="emrgnc_vst_chrt_legend"></div>
            </div>
        </div>    
        <div id="accrdn_pnl_chronic_cndtn_patnt" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Chronic Condition Patients', height:'400px'">
            <div class="chart_area"> 
                <div id="chronic_cndtn_patnt_chrt"></div>
                <div id="chronic_cndtn_patnt_chrt_legend"></div>
            </div>       
        </div>
        <div id="accrdn_pnl_hc_insrnc_patnt" data-dojo-type="dojox/mobile/ContentPane" data-dojo-props="label:'Health Insurance Patients', height:'400px'">
            <div class="chart_area"> 
                <div id="hc_insrnc_patnt_chrt"></div>
                <div id="hc_insrnc_patnt_chrt_legend"></div>
            </div>     
        </div>
    </div>
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
      {//http://localhost/HazelWeb/m/index.php
        //http://localhost/HazelWeb/m/js/custom/IndexModuleMobile.js
        //.replace(/m\/|m\/index.php/, '') 
        name: 'custom',
        location: location.pathname.replace(/\/[^/]+$/, '').replace(/\/m|\/m\//, '') + '/js/custom'
      }
    ]
  };
</script>

<!-- load dojo -->
<script type="text/javascript" src="<?php echo ConstantsMobile::WHERE_IS_DOJO . 'dojo/dojo.js'; ?>"></script>
<script>
  require(["dojox/mobile/parser",  // (Optional) This mobile app uses declarative programming with fast mobile parser
           "dojox/mobile/deviceTheme",
           "dojox/mobile/RoundRectList",
           "dojox/mobile/View",
           "dojox/mobile/Heading",
           "dojox/mobile/ListItem",
           "dojox/mobile/Accordion",
           "dojox/mobile/ContentPane",
           "dojox/mobile/SwapView",
           "dojox/mobile/PageIndicator",
           "dojox/mobile/ScrollablePane",
           "dojox/mobile/FormLayout",
           "dojox/mobile/RadioButton",
           
           "dojox/mobile",         // (Required) This is a mobile app.
           "dojox/mobile/compat",  // (Optional) This mobile app supports running on desktop browsers
           "custom/IndexModuleMobile",
           "dojo/domReady!"],
    function(parser, deviceTheme, RoundRectList, View, Heading, ListItem, Accordion, ContentPane, SwapView, PageIndicator, 
        ScrollablePane, FormLayout, RadioButton, mobile, compat, IndexModuleMobile) { 
      parser.parse();
    }
  );
</script>

</body>
</html>