/**
 * @file
 * Displays a map of countries/portfolios for the country landing page.
 */
(function ($, Drupal) {

  "use strict";

  Drupal.behaviors.landportalCountryLandingMap = {
    attach: function (context, settings) {
      var LBV = new lbvis({prefix: '/sparql?query='}, $);
      LBV.ready().done(function () {
        var countryRegionMap = new lbvisMap(map_data, LBV, {
            "series": [
              {
                "name": Drupal.t("Click to visit"),
                "data": settings.data,
              }
            ],
            "colors": {
              "background": "#fff",
              "hover": "#39c",
              "select": "#f5a623",
              "borders": "#fff",
              "min": "#bbd6d8",
              "max": "#e58517",
              "na": "#bbd6d8"
            },
            "map": {
              "selectable": true,
              "legend": true,
              "nav": true,
              "tooltip": function () {
                return this.name + (this.value ? ' portfolio' : ' overview page');
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
