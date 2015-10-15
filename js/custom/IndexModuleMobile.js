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
 * IndexModuleMobile.js 
 * 
 * This is a custom module that will be used for the page index.php
 */


//Do the initial AJAX request and fill the graphs initially
require([
    "dojo/ready",    
    "dojo/dom",
    "dojo/_base/window",
    "dijit/registry",
    "dojo/query",
    "dijit/layout/ContentPane",
    "dijit/form/Button",
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
    "dojox/mobile/ProgressIndicator",
    "dojo/domReady!"
  ], 
  function(ready, dom, win, registry, query, ContentPane, Button, on, request, JSON, arrayUtil,
          Chart, Default, Lines, theme, Magnify, Tooltip, Legend, ObservableStore, MemoryStore, StoreSeries, ProgressIndicator) {
  
    /*
     * These are variables that are static throughout the program execution. They might be set on an initial load, but they will not change 
     * after that
     */
    var glConst = {
      chartVarArray: ["Seek_hc", "Primary_hc_unav", "Hc_unav", "Tot_res_stayed", "Tot_res_evac", "Open_hosp", "Closed_hosp", "Open_hosp_cap",
                      "ER_visit", "HTN_hc_unav", "Diabetes_hc_unav", "Asthma_hc_unav", "Medicaid_unav", "Medicare_unav", "Private_unav", "Uninsured_unav"],
      glApiURL: ""
    };
    
    var glRadBtnIdArray = ["rb_baseline", "rb_50pct", "rb_6vans_actual", "rb_6vans_deficit_based", "rb_6vans_population_based"];
    
    var glIdToLabelMap = {
      rb_baseline: "Baseline",
      rb_50pct: "Add&nbsp;Capacity&nbsp;50&#37;",
      rb_6vans_actual: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Actual Locations)</span>",
      rb_6vans_deficit_based: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Deficit-based)</span>",
      rb_6vans_population_based: "Activate&nbsp;6&nbsp;Vans&nbsp;<span style=\"font-style: italic;\">(Population-based)</span>"
    };
    
    //Global chart data
    var glDataArr = {
      Seek_hc: [], 
      Primary_hc_unav: [], 
      Hc_unav: [], 
      Tot_res_stayed: [],
      Tot_res_evac: [], 
      Open_hosp: [], 
      Closed_hosp: [], 
      Open_hosp_cap: [],
      ER_visit: [],
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
      Tot_res_stayed: null,
      Tot_res_evac: null,
      Open_hosp: null,
      Closed_hosp: null, 
      Open_hosp_cap: null,
      ER_visit: null,
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
    
    var glIsHealthcareDemandDrawn = false;
    var glIsPracticesDrawn = false;
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
    
    var getDataFromAJAXRequest = function(runKey, varArr) {
      var prog = ProgressIndicator.getInstance();
      
      win.body().appendChild(prog.domNode);
      prog.start(); // start the progress indicator
            
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
          var html = "";
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
            
            makeHealthcareDemandCharts();
            makePracticeCharts();
            makeAdditionalInformationCharts();
            
          } else if(data.error) {
            console.log(JSON.stringify(data.error));
          } 
          prog.stop();
        },
        function(error) {
          // Display the error returned
          resultDiv.innerHTML = error;
          prog.stop();
        }
      ); 
    }; 
    
 
    var makeHealthcareDemandCharts = function() {
      if(!glIsHealthcareDemandDrawn) {        
        /*
         * Patient Healthcare Availability Chart
         */
        glHcAvailChart = new Chart("hc_avail_chrt");
        glHcAvailChart.setTheme(theme);
        glHcAvailChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        glHcAvailChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away",
        });
        glHcAvailChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
        });
      
        glHcAvailChart.addSeries("Seeking Healthcare", new StoreSeries(glDataStore.Seek_hc, { query: {} }, "mean"));
        glHcAvailChart.addSeries("Primary Care Unavailable", new StoreSeries(glDataStore.Primary_hc_unav, { query: {} }, "mean"));
        glHcAvailChart.addSeries("Healthcare Unavailable", new StoreSeries(glDataStore.Hc_unav, { query: {} }, "mean"));
        
        new Magnify(glHcAvailChart);
        new Tooltip(glHcAvailChart);      
        glHcAvailChart.render();
        
        var hcAvailChartLegend = new Legend({chart: glHcAvailChart}, "hc_avail_chrt_legend");
        
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
          titleOrientation: "away",
        });
        patntEvacChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
        });      
        
        patntEvacChart.addSeries("Residents Staying", new StoreSeries(glDataStore.Tot_res_stayed, { query: {} }, "mean"));
        patntEvacChart.addSeries("Residents Evacuating", new StoreSeries(glDataStore.Tot_res_evac, { query: {} }, "mean"));
        
        new Magnify(patntEvacChart);
        new Tooltip(patntEvacChart);      
        patntEvacChart.render();
        
        var patntEvacChartLegend = registry.byId("patnt_evac_chrt_legend");
        if(patntEvacChartLegend != undefined) {
          patntEvacChartLegend.destroyRecursive(true);
        }
        var patntEvacChartLegend = new Legend({chart: patntEvacChart}, "patnt_evac_chrt_legend");
        glIsHealthcareDemandDrawn = true;
      }
    };  
    
    makePracticeCharts = function() {
      if(!glIsPracticesDrawn) {           
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
          titleOrientation: "away",
        });
        practicesChart.addAxis("y", {
          title: "Practices",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
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
          titleOrientation: "away",
        });
        practicesCapChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
        });
        
        practicesCapChart.addSeries("Open Practice Capacity", new StoreSeries(glDataStore.Open_hosp_cap, { query: {} }, "mean"));
        practicesCapChart.addSeries("Seeking Healthcare", new StoreSeries(glDataStore.Seek_hc, { query: {} }, "mean"));
        
        new Magnify(practicesCapChart);
        new Tooltip(practicesCapChart);      
        practicesCapChart.render();
        var practicesCapChartLegend = new Legend({chart: practicesCapChart}, "open_practice_cap_chrt_legend");
        glIsPracticesDrawn = true;
      }   
    }; 
    
    makeAdditionalInformationCharts = function() {
      if(!glIsAdditionalInformationDrawn) {           
        /*
         * Emergency Visits Chart
         */
        var emergencyVisitChart = new dojox.charting.Chart("emrgnc_vst_chrt");
        emergencyVisitChart.setTheme(theme);
        emergencyVisitChart.addPlot("default", {
          type: Lines,
          markers: true,
          tension: "X",
          gap: 1
        });
        emergencyVisitChart.addAxis("x", {
          title: "Day",
          leftBottom: true,
          natural: true,
          fixed: true,
          titleOrientation: "away",
        });
        emergencyVisitChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
        });
        
        emergencyVisitChart.addSeries("ER Visits", new StoreSeries(glDataStore.ER_visit, { query: {} }, "mean"));
        
        new Magnify(emergencyVisitChart);
        new Tooltip(emergencyVisitChart);      
        emergencyVisitChart.render();
        var emergencyVisitChartLegend = new Legend({chart: emergencyVisitChart}, "emrgnc_vst_chrt_legend");
   
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
          titleOrientation: "away",
        });
        chronicCndtnPatntChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
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
          titleOrientation: "away",
        });
        hcInsrncPatntChart.addAxis("y", {
          title: "Individuals",
          vertical: true,
          leftBottom: true,
          natural: false,
          fixed: true,
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
      
      arrayUtil.forEach(glRadBtnIdArray, function(radBtnId, i) {
        var radBtn = registry.byId(radBtnId);
        on(radBtn, "change", function() {
          if(radBtn.checked) {
            getDataFromAJAXRequest(radBtn.get("value"), glConst.chartVarArray);
            dom.byId("home_view_scenario").innerHTML = glIdToLabelMap[radBtnId];
          }
        });
      });
      
      //Load initial data
      getDataFromAJAXRequest("FRRN1", glConst.chartVarArray); 

    });
  }
);
