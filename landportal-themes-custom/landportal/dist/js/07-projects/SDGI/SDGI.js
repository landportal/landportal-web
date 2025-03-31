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
/******/ 	return __webpack_require__(__webpack_require__.s = "./components/07-projects/SDGI/SDGI.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./components/07-projects/SDGI/SDGI.js":
/*!*********************************************!*\
  !*** ./components/07-projects/SDGI/SDGI.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var startOption="";startWord=Drupal.t("Jump to SDGs page...");var select="<select class=\"SDGI-select-list\"> <option value=\" \">"+startWord+"</option >";jQuery(".Jump-menu-SDGI .views-field-title a").each(function(){var a=jQuery(this).text(),b=jQuery(this).attr("href");startOption+="<option value=\""+b+"\">"+a+"</option>"});var selectEnd="</select>",nodeSdgiSelectList=select+startOption+"</select>";jQuery(".Jump-menu-SDGI").append(nodeSdgiSelectList),jQuery(".SDGI-select-list").each(function(){jQuery(this).on("change",function(){var a=jQuery(this).val();return a&&(window.location=a),!1})}),jQuery(".dataset-portfolio .dataset-accordian > div > div .primary-underline").click(function(){var a=jQuery(this).parent().children(":not(.primary-underline)");a.toggleClass("hidden"),a.slideToggle(),jQuery(this).toggleClass("open")}),jQuery(document).ajaxComplete(function(){jQuery(".News-and-Blog nav ul li:contains(\"Page\")").addClass("page-pager");var a=jQuery(".News-and-Blog footer").html();jQuery(".News-and-Blog nav ul .page-pager").html(a)}),jQuery(document).ready(function(){jQuery(".News-and-Blog .views-row .views-field-rendered-entity .field-content .layout--onecol .block:not(:nth-child(2))").each(function(){/\d/.test(jQuery(this).text())&&jQuery(this).addClass("contains-digit")})});

/***/ })

/******/ });
//# sourceMappingURL=SDGI.js.map