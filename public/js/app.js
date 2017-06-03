/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

$(function () {
  $('[data-toggle="tooltip"]').tooltip();

  $('table.data-table').DataTable({
    info: false,
    pageLength: 30,
    language: __webpack_require__(6)
  });
});

/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(0);
module.exports = __webpack_require__(1);


/***/ }),
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var languages = ['en-US', 'zh-TW'];

var language = 'zh-TW';

var _iteratorNormalCompletion = true;
var _didIteratorError = false;
var _iteratorError = undefined;

try {
  for (var _iterator = navigator.languages[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
    var lan = _step.value;

    if (languages.includes(lan)) {
      language = lan;

      break;
    }
  }
} catch (err) {
  _didIteratorError = true;
  _iteratorError = err;
} finally {
  try {
    if (!_iteratorNormalCompletion && _iterator.return) {
      _iterator.return();
    }
  } finally {
    if (_didIteratorError) {
      throw _iteratorError;
    }
  }
}

module.exports = __webpack_require__(7)("./" + language + '.json');

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./en-US.json": 8,
	"./zh-TW.json": 9
};
function webpackContext(req) {
	return __webpack_require__(webpackContextResolve(req));
};
function webpackContextResolve(req) {
	var id = map[req];
	if(!(id + 1)) // check for number or string
		throw new Error("Cannot find module '" + req + "'.");
	return id;
};
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 7;

/***/ }),
/* 8 */
/***/ (function(module, exports) {

module.exports = {
	"sEmptyTable": "No data available in table",
	"sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
	"sInfoEmpty": "Showing 0 to 0 of 0 entries",
	"sInfoFiltered": "(filtered from _MAX_ total entries)",
	"sInfoPostFix": "",
	"sInfoThousands": ",",
	"sLengthMenu": "Show _MENU_ entries",
	"sLoadingRecords": "Loading...",
	"sProcessing": "Processing...",
	"sSearch": "Search:",
	"sZeroRecords": "No matching records found",
	"oPaginate": {
		"sFirst": "First",
		"sLast": "Last",
		"sNext": "Next",
		"sPrevious": "Previous"
	},
	"oAria": {
		"sSortAscending": ": activate to sort column ascending",
		"sSortDescending": ": activate to sort column descending"
	}
};

/***/ }),
/* 9 */
/***/ (function(module, exports) {

module.exports = {
	"sEmptyTable": "沒有資料",
	"sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
	"sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
	"sInfoFiltered": "(從 _MAX_ 項結果過濾)",
	"sInfoPostFix": "",
	"sInfoThousands": ",",
	"sLengthMenu": "顯示 _MENU_ 項結果",
	"sLoadingRecords": "載入中...",
	"sProcessing": "處理中...",
	"sSearch": "搜尋:",
	"sZeroRecords": "沒有符合的結果",
	"oPaginate": {
		"sFirst": "第一頁",
		"sLast": "最後一頁",
		"sNext": "下一頁",
		"sPrevious": "上一頁"
	},
	"oAria": {
		"sSortAscending": ": 升冪排列",
		"sSortDescending": ": 降冪排列"
	}
};

/***/ })
/******/ ]);