/*
 * JS View CODA Library
 *
 * A visualization library for the Land Portal Land Book / LOD
 *
 * MIT License
 * Copyright (c) 2016 - Land Portal Foundation - http://www.landportal.info
 * Copyright (c) 2016-2017 - Jules Clement <jules@ker.bz>
 *
 * Author: Jules Clement <jules@ker.bz>
 *
 * Plugin: Map
 *
 */

'use strict';

var lbvisMap = (function (MAP, LBV, args, $ = jQuery.noConflict()) {
    // console.log(args);
    // console.log(JSON.stringify(args));
    var JSONMAP = MAP; // Geo-JSON map with countries obj 'id' as iso3
    var LBVIS = LBV;
    var _options = {
        target:         '#map',
        indicators:     [],     // ex: 'WB-SP.RUR.TOTL.ZS'
        main:           null,   // indicator selected by default
        series:         null,   // for static series
        title:          null,
        subtitle:       null,
        iso3:           null,   // iso3 of the country to select
        year:           null,
        colors: {
            background: 'transparent',
            hover: '#F5A623',
            select: '#F5A623',
            borders: '#FFFFFF',
            min: '#D9ED7E',
            max: '#45551A',
            na: '#BBD6D8'
        },
        // Map options
        map: {
            width:      1004, // print hack, for wkhtmltopdf
            height:     500,  // print hack, for wkhtmltopdf
            cursor:     null,
            events:     {},
            legend:     true,
            nav:        false,
            selectable: false,
            tooltip:    true,
            zoom:       false
        },
    };
    $.extend(true, _options, args); // true = deep merge
     //     console.log(_options);
     // console.log(JSON.stringify(_options));
     //      console.log(args);
     // console.log(JSON.stringify(args));
    // Internal data
    var _data = {
        chart: null,            // Projection (ex: highchart)
        chartOptions: {},
        cache: {},              // by indicators / year / iso3
        years: {},              // by indicators
        series: [],
        seriesAxis: [],
    };



    /*
     * Data
     */
    // indicators = ["LM-HA10"];
    var _loadData = function (indicators) {
     // console.log(indicators);
     // console.log(JSON.stringify(indicators));        
        return LBVIS.loadData(indicators).done(function () {
            _data.cache = LBVIS.cache('data');
            // console.log('Hello1'+'='+_data.cache);
            // console.log(JSON.stringify(_data.cache))            
            $.each(_data.cache, function (lbid, data) {
                _data.years[lbid] = Object.keys(data);//.map(function (d) { return d; });
                _data.years[lbid].sort(function (a, b) { return b - a });
            });
            //console.log('GOTCHA', indicators, _data.cache, _data.years);
        });
    };

    var _mapSerie = function (indicator, year, data) {
        var serie = {
            id: indicator.id, // + '-' + year,
            name: indicator.label, // + ' (' + year + ')',
            data: Object.keys(data).map(function (iso3) {
                return {
                    colorIndex: 0,
                    id: data[iso3].country,
                    value: parseFloat(data[iso3].value),
                };
            }),
        };
        return serie;
    };

    // Re-process all series (cached in _data.cache)
    // returns HightChart map series
    var _mapSeries = function () {
        if (!_options.year && _options.main) {
            _options.year = _data.years[_options.main][0];
            //alert(_options.year);
        }
        if (Object.keys(_data.cache).length == 0) {
            console.warn('No series in cache');
            return null;
        }
        _data.series = []; // only keep 1 serie (for now)
        // should be _data.indicators (not cache)?
        $.each(_data.cache, function (lbid, dataset) {
            var indicator = LBV.cache('indicators').filter(function (i) { return i.id == lbid; })[0];

            $.each(dataset, function (year, data) {
                var serie = _mapSerie(indicator, year, data);

                if (lbid == _options.main && year == _options.year) {
                    // serie.visible = true;
                    // serie.selected = true;
                    if (_options.map.legend) serie.showInLegend = true;
                    _data.series.push(serie);
                }
            });
        });
        //console.log('Series', _data.series);
        return _data.series;
    };



    /*
     * Drawing & Highchart
     */
    var _mapOptions = function () {
        var chartOptions = LBVIS.chartOptions(_options, {
            chart: {
                //width: _options.map.width,
                height: _options.map.height,
                backgroundColor: _options.colors.background,
                margin: [0, 0, 0, 0]
            },
            legend: _chartLegend(null),
            colors: [ _options.colors.min, _options.colors.max ],
            // Map-specific
            mapNavigation: {
                enabled: _options.map.nav,
                enableMouseWheelZoom: false,
                enableDoubleClickZoom: true,
                enableTouchZoom: false,
                buttonOptions: { align: 'right' },
            },
            tooltip: { enabled: (_options.map.tooltip ? true : false) },
            plotOptions: {
                map: {
                    mapData: JSONMAP,
                    joinBy: 'id',
                    allowPointSelect: _options.map.selectable,
                    nullColor: _options.colors.na,
                    borderColor: _options.colors.borders,
                    states: {
                        hover:  { color: _options.colors.hover },
                        select: { color: _options.colors.select }
                    },
                    point: { events: _options.map.events },
                    showInLegend: false,
                }
            },
            colorAxis: (_options.map.legend ? {} : null),
        });
        if (_options.map.tooltip && _options.map.tooltip.constructor.prototype !== Boolean.prototype) {
            chartOptions.plotOptions.map.tooltip = { pointFormatter: _options.map.tooltip }
        }
        return chartOptions;
    };

    var _mapDraw = function(series=null) {
        var chartOptions = _mapOptions();
        if (series) {
            chartOptions.series = series;
            if (_options.map.legend) chartOptions.colorAxis = _chartAxis(chartOptions.series[0]);
        }
        _data.chart = new Highcharts.mapChart(chartOptions);
        _data.chart.hideLoading();

        if (series) {
            if (_options.iso3) {
                _data.chart.get(_options.iso3).select();
                // If we have an init zoom option + iso3, zoom on that country
                if (_options.map.zoom) {
                    _data.chart.get(_options.iso3).zoomTo();
                    _data.chart.mapZoom(_options.map.zoom);
                }
            }
        }
        return _data.chart;
    };

    var _chartTitle = function  () {
        if (_options.hideTitle) return false;
        var ind = LBVIS.indicators().find(i => i.id == _options.main);
        var title = (ind ? ind.render : _options.main);
        var subtitle = (_options.year ? _options.year : null);
        LBVIS.setTitle(_data.chart, title, subtitle);
    };

    var _chartLegend = function (text) {
        if (!_options.map.legend || !text) return { enable: false };
        var legend = {
            verticalAlign: 'bottom',
            floating: true,
            text: text
        };
        return legend;
    };

    var _chartAxis = function (serie=null) {
        var axis = {
            maxColor: _options.colors.max,
            minColor: _options.colors.min
        };
        if (serie) {
          // console.log(serie);
            var data = serie.data.map(function (i) { return i.value; });
            axis.min = Math.min.apply(Math, data);
            axis.max = Math.max.apply(Math, data);
            if (axis.min > 0) {
                axis.type = 'logarithmic';
            }
        }
        return axis;
    };

    var _mapUpdate = function () {
        _data.chart.showLoading();
        _mapSeries();
        _data.chart.series.forEach(function (cs) {
            cs.remove();
        });
        if (_options.map.legend) _data.chart.colorAxis[0].update(_chartAxis(_data.series[0]));
        _data.chart.addSeries(_data.series[0]);
        _chartTitle();
        // Should be in the serie builder?
        if (_options.iso3) {
            _data.chart.get(_options.iso3).select();
        }
        _data.chart.hideLoading();
    };


    /*
     * UI related
     */
    var _bindUI = function () {
        // Indicators + year selection
        if (_options.loadIndicators) {
            //$(_options.target + '-form').delegate('select', "change", function(e) {
            //$('.form-item-year').delegate('select', "change", function(e) {
            $('.indicator-highChart').delegate('select', "change", function(e) {
                e.preventDefault();
                if (e.target.name == 'year' && e.target.value) {
                   // alert('hello');
                    _options.year = e.target.value; 
                    _mapUpdate();
                    $(e.target).val('').change();
                }
                if (e.target.name == 'indicator' && e.target.value) {

                    _options.main = e.target.value;
                    _options.year = null;

                    // alert(_options.year);
                    // console.log("hi123");
                    _loadData([e.target.value]).done(function () {
                        // alert('done');
                        _mapUpdate();
                        _setOptionsYears();
                        $(e.target).val('').change();
                    });
                }
            });
            /*New code is added here -- start
            $('.form-item-indicator').delegate('select', "change", function(e){
                e.preventDefault();
                if (e.target.name == 'indicator' && e.target.value) {
                    _options.main = e.target.value;
                    _options.year = null;
                    _loadData([e.target.value]).done(function () {
                        _mapUpdate();
                        _setOptionsYears();
                        $(e.target).val('').change();
                    });
                }  
            }); New code is added here -- End*/
        } 
    };

    var _setOptionsYears = function () {
        //alert('year--1');
        //alert(JSON.stringify(_data.years));
        var opts = LBVIS.generateSelect(_data.years[_options.main]);
        //alert('year--2 = '+opts);
        if(opts) {
            //var el = $(_options.target + '-form select[name="year"]');
            var el = $("#-year");
            // alert(el);
            //alert('year--3 = '+JSON.stringify(el));
            el.find('option:gt(0)').remove();
            el.append(opts);
            el.prop( "disabled", false );
        }
    };

    var _setOptionsIndicators = function () {
        
        if (_options.iso3) {
            _data.indicators = LBVIS.cache('indicatorsByCountry')[_options.iso3];
        } else {
            _data.indicators = LBVIS.cache('indicators');
             //console.log(_data.indicators);
        }
        //var opts = LBVIS.generateSelect(_data.indicators, 'dataset', LBVIS.cache('datasets'));
        var opts = LBVIS.generateSelect(_data.indicators, 'dataset');
        // console.log(opts);
        if (opts) {
            //var el = $(_options.target + '-form select[name="indicator"]');
            var el = $('#-indicator');

            el.find('option:gt(0)').remove();
            el.append(opts);
            el.prop( "disabled", false );
        }
    };



    /*
     * Public methods
     */
    return {
        debug: function () {
            return {options: _options, data: _data};
        },
        //draw: _mapDraw,
        init: function () {
            // console.log("test123 == " + _options.indicators);
            // If we have a 'static' serie(s) provided, show it
            if (_options.series) {
                _mapDraw(_options.series);
            } else {
                _mapDraw();
                // If there is no 'main' indicator set, pick the first one from the list
                if (!_options.main && _options.indicators) _options.main = _options.indicators[0];
                // Fetch data
                // console.log(_options);
                _loadData(_options.indicators).done(function () {
                    //console.log('Map init', _options, _data);
                    _mapUpdate();
                    //_chartTitle();
                    if (_options.loadYears) _setOptionsYears();
                    //console.log('show ' + _options.main + '-' +_options.year, visible);
                });
            }
            // if (_options.iso3 && _data.chart) {
            //     _data.chart.get(_options.iso3).select();
            // }
            // // If we have an init zoom option + iso3, zoom on that country
            // if (_options.map.zoom) {
            //     _data.chart.get(_options.iso3).zoomTo();
            //     _data.chart.mapZoom(_options.map.zoom);
            // }
            // Fills up the Indicators select menu
            if (_options.loadIndicators) {
                // Only with indicators that have data for this country...
                LBVIS.getIndicators(_options.iso3).done(function () {
                    _setOptionsIndicators();
                });
            }
            _bindUI();
        }
    };
});
