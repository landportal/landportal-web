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
/******/ 	return __webpack_require__(__webpack_require__.s = "./components/07-projects/Dataset-portfolio/dataset-portfolio.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./components/07-projects/Dataset-portfolio/dataset-portfolio.js":
/*!***********************************************************************!*\
  !*** ./components/07-projects/Dataset-portfolio/dataset-portfolio.js ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(".new_page_node_dataset li.landportal-menu__item--data").addClass("landportal-menu__item--active"),jQuery(".dataset-portfolio .dataset-accordian > div > div .primary-underline").click(function(){var a=jQuery(this).parent().children(":not(.primary-underline)");a.toggleClass("hidden"),a.slideToggle(),jQuery(this).toggleClass("open")});var datasetPartner=jQuery(".dataset-portfolio .section__sidebar__aside .dataset-porfolio-partner .views-field-field-orgref .field-content").text();""==datasetPartner&&jQuery(".dataset-portfolio .section__sidebar__aside .dataset-porfolio-partner").css("display","none");var datasetDonors=jQuery(".dataset-portfolio .section__sidebar__aside .dataset-porfolio-donor .views-row").text();""==datasetDonors&&jQuery(".dataset-portfolio .section__sidebar__aside .dataset-porfolio-donor").css("display","none");var datasetImage=jQuery(".dataset .layout--onecol .layout--image-full img").attr("src");datasetImage==null&&jQuery(".dataset-portfolio").addClass("no-banner");var startopt="",navigate_select=Drupal.t("Choose other indicator from this dataset"),select="<select id=\"dataset-jump-menu-ct\"><option value=\"\">"+navigate_select+"</option>";jQuery(".dataset-portfolio-jump-menu-view-ct .views-field-name .field-content a").each(function(){var a=jQuery(this).text(),b=jQuery(this).attr("href");startopt+="<option value=\""+b+"\">"+a+"</option>"});var selectEnd="</select>",full=select+startopt+"</select>";jQuery(".dataset-portfolio-jump-menu-view-ct").append(full),jQuery("#dataset-jump-menu-ct").on("change",function(){var a=jQuery(this).val();return a&&(window.location=a),!1});

/***/ })

/******/ });
//# sourceMappingURL=dataset-portfolio.js.map