/**
 * @file
 * Displays a map of countries/portfolios for the country landing page.
 */
(function ($, Drupal) {
//console.log("hello2");
//alert("hi");
  "use strict";

  Drupal.behaviors.landportalProgrammeLandingMap = {
    attach: function (context, settings) {
      //alert(settings.data);
      
      var LBV = new lbvis({prefix: '/sparql?query='}, $);
      //alert(JSON.stringify(settings.data));
      LBV.ready().done(function () {
        var countryRegionMap = new lbvisMap(map_data, LBV, {
            "series": [
              {
                "name": Drupal.t("Click to visit"),
                "data": settings.data,
              }
            ],
            "colors": {
              "background": "#39c",
              "hover": "#fff",
              "select": "#f5a623",
              "borders": "#fff",
              "min": "#bbd6d8",
              "max": "#004764",
              "na": "#bbd6d8"
            },
            "map": {
              "selectable": true,
              "legend": true,
              "nav": true,
              "tooltip": function () {
                //alert("hee" + this.countryName);
                return this.countryName + " " + Drupal.t("has") + " " + this.value + " " + Drupal.t("Project(s)");
              },
              "events": {
                click: function () {
                  window.location.href = this.url;
                }
              }
            },
            "indicators":[],
            "cache":[],
            "target": '#' + settings.target,
          }, $
        );
        countryRegionMap.init();
      });
    }
  };

})(jQuery, Drupal);
