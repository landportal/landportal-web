/**
 * @file
 * Displays a map of countries/portfolios for the country landing page.
 */
  (function ($, Drupal) {
  "use strict";
  
  var downloadIndicatorLoaded = false; // Add a flag to track if the function has been loaded
  var downloadDatasetLoaded = false; // Add a flag to track if the function has been loaded

  Drupal.behaviors.landportalProgrammeLandingMap = {
    attach: async function (context, settings) { // function
      var my_library = drupalSettings.my_library;
      var indicator_map_id = my_library.indicator_map_id;
      var hide_chart_title = my_library.hide_chart_title;
      var selected_year = my_library.selected_year;      
      var legend_status = my_library.legend_status;  
      var nav_status = my_library.nav_status;

      var indicator_table_id = my_library.indicator_table_id;
      var indicator_download_id = my_library.indicator_download_id;
      var dataset_field_id = my_library.dataset_field_id;
      var check_vid = my_library.check_vid;
      var check_bundle = my_library.check_bundle;
      var dataset_portfolio_field_id = my_library.dataset_portfolio_field_id;

      var indicator_ranking_id = my_library.indicator_ranking_id;
      var indicator_portfolio_ranking_id = my_library.indicator_portfolio_ranking_id;
      var iso3 = my_library.iso3;

      var visualization_type = my_library.visualization_type;
      var visualization_type_arr = my_library.visualization_type_arr; 
       console.log('visualization_type | ' + visualization_type ); 
      console.log('visualization_type_arr | ' + visualization_type_arr ); 
      // console.log('dataset_field_id | ' + dataset_field_id );

       console.log('indicator_ranking_id | ' + indicator_ranking_id ); 
       console.log('iso3 | ' + iso3 ); 

      // settings.data = [{"id":"ABW","value":3,"countryName":"Aruba","url":"/search-in-projects?key=&f[0]=by_geographical_focus:549&f[1]=programme:8974"},{"id":"AFG","value":0,"countryName":"Afghanistan","url":"/search-in-projects?key=&f[0]=by_geographical_focus:550&f[1]=programme:8974"}]
      
      if(visualization_type_arr != null){
      visualization_type_arr.forEach(async function(visualization_type) {
        console.log('Visualization type: ' + visualization_type);
      // var LBV = new lbvis({prefix: '/sparql?query='}, $);

      if(visualization_type == 'map'){
        var LBV = new lbvis({prefix: '/sparql?query='}, $);
        await LBV.ready().done(function () {
        console.log("indicator_id_map|" + indicator_map_id + "| type |" + visualization_type);
          var countryRegionMap = new lbvisMap(map_data, LBV, {        
              "colors": {
                "background": "transparent",
                "hover": "#F5A623",
                "select": "#F5A623",
                "borders": "#FFFFFF",
                "min": "#D9ED7E",
                "max": "#45551A",
                "na": "#BBD6D8"
              },
              "map": {
                "selectable": true,
                "legend": legend_status,
                "nav": nav_status,
                "tooltip": function () {
                  return this.name +': '+ this.value;
                },
                "events": {
                  click: function () {
                    window.location.href = this.url;
                  }
                }
              },
              "indicators":indicator_map_id,
              "loadIndicators": true,
              "loadYears": true,
              "hideTitle": hide_chart_title,
              "year":selected_year,            
              "cache":[],
              "target": '#container',
            }, $
          );
          countryRegionMap.init();
        });  //end of LBV.ready.done 

       //Apply 'select2' library on select lists
           $('#-indicator').select2();
           $('#-year').select2();

      }// end if of map js


      if(visualization_type == 'htmlTable'){
        var LBV = new lbvis({prefix: '/sparql?query='}, $);        
        await LBV.ready().done(function () {
        console.log("indicator_id_table|||" + indicator_table_id + "|type |" + visualization_type);
          var htmlTable = new lbvisTable(LBV, {        
              "columns": {
                "max": 0,
                "min": 0,
                "nbcountries": 0,
                "obs": 0,
                "years": 0
              },
              "indicators":indicator_table_id,          
              "cache":{
                "id":indicator_table_id,
              },
              "target": '#html-table',
            }, $
          );
          htmlTable.init();
        });
       }// end if of htmlTable js
      }); // end of for each loop of array
    }// End of If to check  visualization_type_arr


      //for download Indicator 
      // Check if the "Download Indicator" function has already been loaded
      if(check_vid == 'indicators'){
        if (!downloadIndicatorLoaded) {
          downloadIndicatorLoaded = true; // Set the flag to true to indicate that it has been loaded
          var LBV = new lbvis({prefix: '/sparql?query='}, $);        
           LBV.ready().done(function () {
            console.log("Download Indicator log 1",indicator_download_id);
            var DL = new dl(LBV, {
              "debug": true,
              "cache": {
                indicator_download_id:{
                  "id": indicator_download_id,
                },
              },
              "tree" : {
                indicator_download_id : [],
              },
              "lbid": indicator_download_id,
              "target": "#block-landbook-view-coda-lbvc-download-ind",
              "type": "indicator",
              }, $
            );
            DL.init();
          });
         } //End of  Check if the "Download Indicator"
       }

      //for download DataSets 
      // Check if the "Download Datasets" function has already been loaded
      if(check_vid == 'datasets'){
        if (!downloadDatasetLoaded) {
          downloadDatasetLoaded = true; // Set the flag to true to indicate that it has been loaded
          var LBV = new lbvis({prefix: '/sparql?query='}, $);        
           LBV.ready().done(function () {
            console.log("Download dataset log 1");
            var DL = new dl(LBV, {
              "debug": true,
              "lbid": [dataset_field_id],
              "target": "#block-landbook-view-coda-lbvc-download-cat",
              "type": "dataset",
              }, $
            );
            DL.init();
          });
         } //End of  Check if the "Download Indicator"  LM-LANDMATRIX
       }

      //for download DataSets Portfolio 
      // Check if the "Download Datasets" function has already been loaded
       if(check_bundle == 'dataset'){
        // if (!downloadDatasetLoaded) {
          // downloadDatasetLoaded = true; // Set the flag to true to indicate that it has been loaded
          var LBV = new lbvis({prefix: '/sparql?query='}, $);        
           LBV.ready().done(function () {
            console.log("Download dataset log 1");
            var DL = new dl(LBV, {
              "debug": true,
              "lbid": [dataset_portfolio_field_id],
              "target": "#block-landbook-view-coda-lbvc-download-cat",
              "type": "dataset",
              }, $
            );
            DL.init();
          });
         // } //End of  Check if the "Download Indicator"  LM-LANDMATRIX
        }


      if(visualization_type == 'ranking'){
        console.log("Hello From  ranking->its id is  ",indicator_ranking_id );
        var LBV = new lbvis({prefix: '/sparql?query='}, $);        
         LBV.ready().done(function () {
          var lbvisRank = new lbvisRanking(LBV, {        
              "indicators":indicator_ranking_id,          
              "cache": {
                indicator_ranking_id: {
                  "id": indicator_ranking_id,
                  "lable": 'this is example Lable in view_coda_extra.js',
                  "description":'this is example Description in view_coda_extra.js',
                }
              },
              "iso3" : iso3,
              "target": '#indicator-ranking-wrapper',
            }, $
          );
          lbvisRank.init();
           });
          }// end if of Ranking js

        } // end of function
      };

     




})(jQuery, Drupal);
