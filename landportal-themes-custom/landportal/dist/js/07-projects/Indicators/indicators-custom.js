/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./components/07-projects/Indicators/indicators-custom.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./components/07-projects/Indicators/indicators-custom.js":
/*!****************************************************************!*\
  !*** ./components/07-projects/Indicators/indicators-custom.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var startopt="",navigate_select=Drupal.t("Choose other indicator from this dataset"),select="<select id=\"indicators-select-list\"><option value=\"\">"+navigate_select+"</option>";jQuery(".layout__region--second .block:nth-child(2) .views-field-field-name .field-content a").each(function(){var a=jQuery(this).text(),b=jQuery(this).attr("href");startopt+="<option value=\""+b+"\">"+a+"</option>"});var selectEnd="</select>",full=select+startopt+"</select>";jQuery(".indicator-jump-menu").append(full),jQuery("#indicators-select-list").on("change",function(){var a=jQuery(this).val();return a&&(window.location=a),!1}),jQuery(".layout__region--second .views-field-field-doc-licencing-1 div:nth-child(4) div:nth-child(2)").addClass("acronym");var login=jQuery("body").hasClass("toolbar-tray-open");if(!0==login){var acro1=jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 h2 a").attr("href");jQuery(document).ready(function(){var a=jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym").text(),b=jQuery("<a></a>").attr("href",acro1).text(a);jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym").html(b)})}else{jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 div:nth-child(3) div:nth-child(2)").addClass("acronym");var acro=jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 h2 a").attr("href");jQuery(document).ready(function(){var a=jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym").text(),b=jQuery("<a></a>").attr("href",acro).text(a);jQuery(".layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym").html(b)})}var lincse=jQuery(".layout__region--second .views-field-field-doc-licencing-1 .field-content").text();""==lincse&&jQuery(".layout__region--second .views-field-field-doc-licencing-1").css("display","none");var copyRight=jQuery(".layout__region--second .indicator-data-provider .views-field-field-license-text .field-content").text();""==copyRight&&jQuery(".layout__region--second .indicator-data-provider .views-field-field-license-text").css("display","none"),jQuery(".form-item--name-0-value").css("display","none"),jQuery("#edit-field-name-0-value").keyup(function(){var a=jQuery(this).val();jQuery("#edit-name-0-value").val(a)}),jQuery(document).ready(function(){var a=jQuery(".Indicator-data-definition .views-row .views-field-field-observations .field-content").text();""==a&&jQuery(".Indicator-data-definition").hide()}),jQuery(document).ready(function(){var a=jQuery("#block-showsourceanddisclaimerforprindexindicators").text();""!=a&&(jQuery(".social-media-lock").hide(),jQuery("#block-addtoanysharebuttons-2").addClass("indi-share-icon"))});

/***/ })

/******/ });
//# sourceMappingURL=indicators-custom.js.map