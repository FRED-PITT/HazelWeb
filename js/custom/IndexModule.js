/*
 * Copyright (C) 2015 University of Pittsburgh
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * IndexModule.js 
 * 
 * This is a custom module that will be used for the page index.php
 */


//Do the initial AJAX request and fill the graphs initially
require([
    "dojo/ready",    
    "dojo/dom",
    "dijit/registry",
    "dojo/query",
    "dijit/layout/ContentPane",
    "dijit/form/Button",
    "dijit/ProgressBar",
    "dojo/on",
    "dojo/request", 
    "dojo/json",
    "dojo/_base/array",
    "dojox/charting/Chart",
    "dojox/charting/axis2d/Default",
    "dojox/charting/plot2d/Lines",
    "dojox/charting/themes/Claro",
    "dojox/charting/action2d/Magnify",
    "dojox/charting/action2d/Tooltip",
    "dojox/charting/widget/Legend",
    "dojo/store/Observable", 
    "dojo/store/Memory",
    "dojox/charting/StoreSeries",
    "dojo/sniff",
    "dojo/dom-style",
    "dojo/domReady!"
  ], 
  function(ready, dom, registry, query, ContentPane, Button, ProgressBar, on, request, JSON, arrayUtil,
          Chart, Default, Lines, theme, Magnify, Tooltip, Legend, ObservableStore, MemoryStore, StoreSeries, has, domStyle) {
  
    /*
     * These are variables that are static throughout the program execution. They might be set on an initial load, but they will not change 
     * after that
     */
    var glConst = {
      chartVarArray: ["Seek_hc", "Primary_hc_unav", "Hc_unav", "Hc_accep_ins_unav", "Tot_res_stayed", "Tot_res_evac", "Open_hosp", "Closed_hosp", "Open_hosp_cap",
                      "HTN_hc_unav", "Diabetes_hc_unav", "Asthma_hc_unav", "Medicaid_unav", "Medicare_unav", "Private_unav", "Uninsured_unav"],
      glApiURL: "",
      glMouseWheelEvtName: (!has("mozilla") ? "mousewheel" : "DOMMouseScroll"),
      glGraph: {
        hc_avail_chrt: {
          y_min: -100,
          y_max: 1500
        },
        patnt_evac_chrt: {
          y_min: -10000,
          y_max: 120000
        },
        practices_chrt: {
          y_min: -10,
          y_max: 70
        },
        open_practice_cap_chrt: {
          y_min: -100,
          y_max: 1200
        },
        chronic_cndtn_patnt_chrt: {
          y_min: -50,
          y_max: 600
        },
        hc_insrnc_patnt_chrt: {
          y_min: -50,
          y_max: 400
        }
      }
    };
    
    var glIdToLabelMap = {
      rb_baseline: "Baseline",
      rb_50pct: "Add&nbsp;Capacity&nbsp;50&#37;",
      rb_6vans_actual: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Actual Locations)</span>",
      rb_6vans_deficit_based: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Deficit-based)</span>",
      rb_6vans_population_based: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Population-based)</span>"
    }
    
    //Global chart data
    var glDataArr = {
      Seek_hc: [], 
      Primary_hc_unav: [], 
      Hc_unav: [], 
      Hc_accep_ins_unav: [],
      Tot_res_stayed: [],
      Tot_res_evac: [], 
      Open_hosp: [], 
      Closed_hosp: [], 
      Open_hosp_cap: [],
      HTN_hc_unav: [], 
      Diabetes_hc_unav: [],
      Asthma_hc_unav: [], 
      Medicaid_unav: [], 
      Medicare_unav: [], 
      Private_unav: [], 
      Uninsured_unav: []
    }
    
    var glDataStore = {
      Seek_hc: null,
      Primary_hc_unav: null,
      Hc_unav: null,
      Hc_accep_ins_unav: null,
      Tot_res_stayed: null,
      Tot_res_evac: null,
      Open_hosp: null,
      Closed_hosp: null, 
      Open_hosp_cap: null,
      HTN_hc_unav: null, 
      Diabetes_hc_unav: null,
      Asthma_hc_unav: null, 
      Medicaid_unav: null, 
      Medicare_unav: null, 
      Private_unav: null, 
      Uninsured_unav: null
    };
    
    // Create the data stores
    // Store information in data stores on the client side
    arrayUtil.forEach(glConst.chartVarArray, function(chartVar, i) {
      glDataStore[chartVar] = new ObservableStore(new MemoryStore({
        data: {
          identifier: "day",
          idAttribute: "day",
          label: "Count",
          items: glDataArr[chartVar]
        }
      }));
    });
    
    var glHcAvailChart;
    
    var glIsTabHealthcareDemandDrawn = false;
    var glIsTabPracticesDrawn = false;
    var glIsAdditionalInformationDrawn = false
    var glApiURL = "";
  
    var getCheckedRadioButtonId = function(formId) {
      var chkBoxes = query("#" + formId + " input:checked"); // <- need to use CSS decendent selector  
      var id;
      for(var i = 0; i < chkBoxes.length; ++i) {  
        id = chkBoxes[i].id;
        break;
      };  
      return id;
    }
    
    var getArrayKeys = function(obj) {
      var r = [];
      for(var k in obj) {
        if(!obj.hasOwnProperty(k)) {
          continue;
        }
        r.push(k);
      }
      return r;
    };
    
    var findWebRunIdAndGetDataFromAJAXRequest = function(jsonParams) {
      // Request the JSON data from the server
      request.post(glConst.glApiURL + "submission", {
        data: JSON.stringify(jsonParams),
        method: "POST",
        handleAs: "json"        
      }).then(
        function(data) {         
          if(data && data.submission) {
            //Now use the returned web_run_id to get the AJAX data
            getDataFromAJAXRequest(data.submission.web_run_id, glConst.chartVarArray);
          } else if(data.error) {
            console.log(JSON.stringify(data.error));
          } 
        },
        function(error) {
          // Display the error returned
          console.log("Error [" + error + "]");
        }
      )
    };
    
    var getDataFromAJAXRequest = function(runKey, varArr) {
      
      var myProgressBar;          
      var accrdnCntnr = registry.byId("left_accrdn_cntnr");
      var temp = accrdnCntnr.get("selectedChildWidget");

      if(temp === registry.byId("cntnt_pn_slct_scnrio")) {
        myProgressBar = registry.byId("prgrss_bar_selected_scenario");
      } else if(temp === registry.byId("cntnt_pn_chs_scnrio")) {
        myProgressBar = registry.byId("prgrss_bar_selected_chsn_scenario");  
      } else {
        myProgressBar = null;
      }

      if(myProgressBar) {
        myProgressBar.set("value", 0);
        myProgressBar.set("style", "visibility: visible;");       
      }
           
      var varString = "";
      for(var i = 0; i < varArr.length; ++i) {
        if(i + 1 < varArr.length) {
          varString += (varArr[i] + ",");
        } else {
          varString += varArr[i]
        }
      }
           
      // Results will be displayed in resultDiv
      var resultDiv = dom.byId("resultDiv");

      // Request the JSON data from the server
      request.get(glConst.glApiURL + "result/line_plot_data/" + runKey + "?v=" + varString, {
        // Parse data from JSON to a JavaScript object
        handleAs: "json"
      }).then(
        function(data) {
          var html = "<h2>JSON Data</h2>";
          if(data.result && data.result.line_plot_data) {
            // Display the data sent from the server
            html += "<p><pre>" + JSON.stringify(data.result.line_plot_data, null, 2) + "</pre></p>"; 
            resultDiv.innerHTML = html;
            arrayUtil.forEach(getArrayKeys(data.result.line_plot_data), function(varName, index) {                              
              arrayUtil.forEach(data.result.line_plot_data[varName], function(item, i) {
                var obj = new Object();
                obj.day = parseInt(item.day);
                obj.mean = parseFloat(item.mean);                
                glDataStore[varName].put(obj, {overwrite: true});
              });
            });

            //Update the data on the chart on the current tab page
            var tabContainer = registry.byId('center_tab_cntnr');
            
            if(tabContainer.selectedChildWidget === registry.byId("tab_panel_hc_demand")) {
              makeHealthcareDemandTabCharts();
            } else if(tabContainer.selectedChildWidget === registry.byId("tab_panel_practices")) {
              makePracticeTabCharts();
            } else if(tabContainer.selectedChildWidget === registry.byId("tab_panel_individual")) {
              makeAdditionalInformationTabCharts();
            }  
            
            // Another Request the JSON data from the server for the video url
            request.get(glConst.glApiURL + "result/movie/" + runKey + "?v=hc_deficit", {
              // Parse data from JSON to a JavaScript object
              handleAs: "json"
            }).then(
              function(data) {
                if(data.result && data.result.video_url) {
                  var videoTag = dom.byId("simulation_video");
                  videoTag.src = data.result.video_url;
                } else if(data.error) {
                  console.log(JSON.stringify(data.error, null, 2));
                }          
              },
              function(error) {
                console.log(error);
              }
            );
          } else if(data.error) {
            console.log(JSON.stringify(data.error, null, 2));
          }          
        },
        function(error) {
          // Display the error returned
          resultDiv.innerHTML = error;
        }
      ).then(
        function(data) {
          var maxVal = 100;
          function updateProgressBar() {
            var curVal = myProgressBar.get("value");
            curVal += 10;
            if(curVal <= maxVal) {
              myProgressBar.set({value: curVal});
            } else {
              clearInterval(intervalId);
              myProgressBar.set("style", "visibility: hidden;");
            }         
          }
          var intervalId = setInterval(updateProgressBar, 100);
        }
      );  
    }; 
    
    var makeHealthcareDemandTabCharts = function() {
      if(!glIsTabHealthcareDemandDrawn) {        
        /*
         * Patient Healthcare Availability Chart
         */
        hcAvailChart = new Chart("hc_avail_chrt");
        hcAvailChart.setTheme(theme);
        hcAvailChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        hcAvailChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away",
        });
        hcAvailChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.hc_avail_chrt.y_min,
          max: glConst.glGraph.hc_avail_chrt.y_max
        });
      
        hcAvailChart.addSeries("Seeking Healthcare", new StoreSeries(glDataStore.Seek_hc, { query: {} }, "mean"));
        //glHcAvailChart.addSeries("Primary Care Unavailable", new StoreSeries(glDataStore.Primary_hc_unav, { query: {} }, "mean"));
        hcAvailChart.addSeries("Healthcare Unavailable", new StoreSeries(glDataStore.Hc_accep_ins_unav, { query: {} }, "mean"));
        
        new Magnify(hcAvailChart);
        new Tooltip(hcAvailChart); 
        
        hcAvailChart.render();
        var hcAvailChartLegend = new Legend({chart: hcAvailChart}, "hc_avail_chrt_legend");        
              
        /*
         * Patient Evacuation Status Chart
         */
        var patntEvacChart = new Chart("patnt_evac_chrt");
        patntEvacChart.setTheme(theme);
        patntEvacChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        patntEvacChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away"
        });
        patntEvacChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.patnt_evac_chrt.y_min,
          max: glConst.glGraph.patnt_evac_chrt.y_max
        });      
        
        patntEvacChart.addSeries("Residents Staying", new StoreSeries(glDataStore.Tot_res_stayed, { query: {} }, "mean"));
        patntEvacChart.addSeries("Residents Evacuating", new StoreSeries(glDataStore.Tot_res_evac, { query: {} }, "mean"));
        
        new Magnify(patntEvacChart);
        new Tooltip(patntEvacChart);      
        patntEvacChart.render();

        var patntEvacChartLegend = new Legend({chart: patntEvacChart}, "patnt_evac_chrt_legend");
        glIsTabHealthcareDemandDrawn = true;
      }
    };  
    
    makePracticeTabCharts = function() {
      if(!glIsTabPracticesDrawn) {           
        /*
         * Practices Chart
         */
        var practicesChart = new dojox.charting.Chart("practices_chrt");
        practicesChart.setTheme(theme);
        practicesChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        practicesChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away"
        });
        practicesChart.addAxis("y", {
          title: "Practices",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.practices_chrt.y_min,
          max: glConst.glGraph.practices_chrt.y_max
        });
        
        practicesChart.addSeries("Open", new StoreSeries(glDataStore.Open_hosp, { query: {} }, "mean"));
        practicesChart.addSeries("Closed", new StoreSeries(glDataStore.Closed_hosp, { query: {} }, "mean"));
        
        new Magnify(practicesChart);
        new Tooltip(practicesChart);      
        practicesChart.render();
        var practicesChartLegend = new Legend({chart: practicesChart}, "practices_chrt_legend");        
       
        /*
         * Open Practices Capacity Chart
         */
        var practicesCapChart = new dojox.charting.Chart("open_practice_cap_chrt");
        practicesCapChart.setTheme(theme);
        practicesCapChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        practicesCapChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away"
        });
        practicesCapChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.open_practice_cap_chrt.y_min,
          max: glConst.glGraph.open_practice_cap_chrt.y_max
        });
        
        practicesCapChart.addSeries("Open Practice Capacity", new StoreSeries(glDataStore.Open_hosp_cap, { query: {} }, "mean"));
        practicesCapChart.addSeries("Seeking Healthcare", new StoreSeries(glDataStore.Seek_hc, { query: {} }, "mean"));
        
        new Magnify(practicesCapChart);
        new Tooltip(practicesCapChart);      
        practicesCapChart.render();
        var practicesCapChartLegend = new Legend({chart: practicesCapChart}, "open_practice_cap_chrt_legend");  
        
        glIsTabPracticesDrawn = true;
      }   
    }; 
    
    makeAdditionalInformationTabCharts = function() {
      if(!glIsAdditionalInformationDrawn) {           
         
        /*
         * Chronic Condition Patient Chart
         */
        var chronicCndtnPatntChart = new dojox.charting.Chart("chronic_cndtn_patnt_chrt");
        chronicCndtnPatntChart.setTheme(theme);
        chronicCndtnPatntChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        chronicCndtnPatntChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away"
        });
        chronicCndtnPatntChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.chronic_cndtn_patnt_chrt.y_min,
          max: glConst.glGraph.chronic_cndtn_patnt_chrt.y_max
        });
        
        chronicCndtnPatntChart.addSeries("HTN", new StoreSeries(glDataStore.HTN_hc_unav, { query: {} }, "mean"));
        chronicCndtnPatntChart.addSeries("Diabetes", new StoreSeries(glDataStore.Diabetes_hc_unav, { query: {} }, "mean"));
        chronicCndtnPatntChart.addSeries("Asthma", new StoreSeries(glDataStore.Asthma_hc_unav, { query: {} }, "mean"));
        
        new Magnify(chronicCndtnPatntChart);
        new Tooltip(chronicCndtnPatntChart);      
        chronicCndtnPatntChart.render();
        var chronicCndtnPatntChartLegend = new Legend({chart: chronicCndtnPatntChart}, "chronic_cndtn_patnt_chrt_legend");
        
        /*
         * Patient Insurance HC Defecit Chart
         */
        var hcInsrncPatntChart = new dojox.charting.Chart("hc_insrnc_patnt_chrt");
        hcInsrncPatntChart.setTheme(theme);
        hcInsrncPatntChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        hcInsrncPatntChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away"
        });
        hcInsrncPatntChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
          min: glConst.glGraph.hc_insrnc_patnt_chrt.y_min,
          max: glConst.glGraph.hc_insrnc_patnt_chrt.y_max
        });
        
        hcInsrncPatntChart.addSeries("Medicaid", new StoreSeries(glDataStore.Medicaid_unav, { query: {} }, "mean"));
        hcInsrncPatntChart.addSeries("Medicare", new StoreSeries(glDataStore.Medicare_unav, { query: {} }, "mean"));
        hcInsrncPatntChart.addSeries("Private", new StoreSeries(glDataStore.Private_unav, { query: {} }, "mean"));
        hcInsrncPatntChart.addSeries("None", new StoreSeries(glDataStore.Uninsured_unav, { query: {} }, "mean"));
        
        new Magnify(hcInsrncPatntChart);
        new Tooltip(hcInsrncPatntChart);      
        hcInsrncPatntChart.render();
        var hcInsrncPatntChartLegend = new Legend({chart: hcInsrncPatntChart}, "hc_insrnc_patnt_chrt_legend");
        
        glIsAdditionalInformationDrawn = true;
      }   
    };  
  
    /*
     * Main startup code
     */
    ready(function() {      
      //Figure out where the fredweb API is located from the hidden input
      var ApiURLInput = dom.byId("api_url");
      glConst.glApiURL = ApiURLInput.value;
      
      var btnChngScenario = registry.byId("btn_chng_scenario");    
      on(btnChngScenario, "click", function() {
        var checkedRadioBtnId = getCheckedRadioButtonId("frm_selected_scenario");
        var checkedRadioBtn = registry.byId(checkedRadioBtnId);
        if(checkedRadioBtn) {
          getDataFromAJAXRequest(checkedRadioBtn.get("value"),  glConst.chartVarArray); 

          var tmpId = checkedRadioBtn.get("id");
          dom.byId("tab_splash_page_scenario").innerHTML = glIdToLabelMap[tmpId];
          dom.byId("tab_hc_demand_scenario").innerHTML = glIdToLabelMap[tmpId];
          dom.byId("tab_practices_scenario").innerHTML = glIdToLabelMap[tmpId];
          dom.byId("tab_patient_scenario").innerHTML = glIdToLabelMap[tmpId];
          dom.byId("tab_about_page_scenario").innerHTML = glIdToLabelMap[tmpId];  
          dom.byId("tab_team_page_scenario").innerHTML = glIdToLabelMap[tmpId];
        } else {
          console.log("Error: unable to find checked radio button on form, frm_selected_scenario.");
        }
      });
      
      var btnChooseScenario = registry.byId("btn_chng_chsn_scenario");    
      on(btnChooseScenario, "click", function() {
        var vanCount = 0;
        var capacityMultiplier = 1.0;
        var radBtn = registry.byId("rb_mobile_van_true");
        if(radBtn.checked) {
          vanCount = parseInt(registry.byId("num_spn_mobile_van_cnt").get("value"));
        }
        radBtn = registry.byId("rb_exceed_cap_true");
        if(radBtn.checked) {
          capacityMultiplier = (parseInt(registry.byId("num_spn_exceed_cap_pct").get("value")) / 100.0) + 1.0;
        }
        
        //base_param_id = 2 is for the HAZEL baseline parameters
        var jsonObj = {params: {HAZEL_mobile_van_max: vanCount, HAZEL_disaster_capacity_multiplier: capacityMultiplier}, base_param_id: 2};
        
        findWebRunIdAndGetDataFromAJAXRequest(jsonObj);
        
        var innerHtml = "<span style=\"font-weight: bold;\">Custom:</span>&nbsp;{";
        if(vanCount > 0) {
          innerHtml += (vanCount +"&nbsp;Mobile&nbsp;Van" + (vanCount > 1 ? "s" : "") + "&nbsp;Active");
        }
        
        if(capacityMultiplier > 1.0) {
          spinner = registry.byId("num_spn_exceed_cap_pct");
          innerHtml += (",&nbsp;" + spinner.get("value") + "&#37;&nbsp;Additional&nbsp;Capacity");
        }
        innerHtml += "}";
        dom.byId("tab_splash_page_scenario").innerHTML = innerHtml;
        dom.byId("tab_hc_demand_scenario").innerHTML = innerHtml;
        dom.byId("tab_practices_scenario").innerHTML = innerHtml;
        dom.byId("tab_patient_scenario").innerHTML = innerHtml;
        dom.byId("tab_about_page_scenario").innerHTML = innerHtml; 
        dom.byId("tab_team_page_scenario").innerHTML = innerHtml;
      });
      
      var radBtnMobileVanTrue = registry.byId("rb_mobile_van_true");
      on(radBtnMobileVanTrue, "change", function() {
        if(radBtnMobileVanTrue.checked) {
          registry.byId("num_spn_mobile_van_cnt").set("disabled", false);
        }
      });
      
      radBtnMobileVanFalse = registry.byId("rb_mobile_van_false");
      on(radBtnMobileVanFalse, "change", function() {
        if(radBtnMobileVanFalse.checked) {
          registry.byId("num_spn_mobile_van_cnt").set("disabled", true);
        }
      });  
      
      var radBtnExceedCapTrue = registry.byId("rb_exceed_cap_true");
      on(radBtnExceedCapTrue, "change", function() {
        if(radBtnExceedCapTrue.checked) {
          registry.byId("num_spn_exceed_cap_pct").set("disabled", false);
        }
      });
      
      radBtnExceedCapFalse = registry.byId("rb_exceed_cap_false");
      on(radBtnExceedCapFalse, "change", function() {
        if(radBtnExceedCapFalse.checked) {
          registry.byId("num_spn_exceed_cap_pct").set("disabled", true);
        }
      });      
    
      var centerTabCntnr = registry.byId("center_tab_cntnr");
      centerTabCntnr.watch("selectedChildWidget", function(name, oval, nval) {
        if(nval === registry.byId("tab_panel_hc_demand")) {
          makeHealthcareDemandTabCharts();
        } else if(nval === registry.byId("tab_panel_practices")) {
          makePracticeTabCharts();
        } else if(nval === registry.byId("tab_panel_patient"))  {
          makeAdditionalInformationTabCharts();
        }        
      });
    
      //Load initial data
      getDataFromAJAXRequest("FRRN1", glConst.chartVarArray); 

    });
  }
);



