{"version":3,"sources":["vue.bitrix.bundle.js"],"names":["exports","main_polyfill_core","ui_vue_vendor_v2","BitrixVue","babelHelpers","classCallCheck","this","_components","_mutations","_clones","event","VueVendorV2","createClass","key","value","create","params","component","id","Object","assign","_registerCloneComponent","_getComponentParamsWithMutation","mutateComponent","mutations","_this","push","filter","element","cloneComponent","sourceId","isComponent","extend","options","nextTick","callback","context","set","target","_delete","delete","directive","definition","use","plugin","mixin","_mixin","compile","template","version","getFilteredPhrases","phrasePrefix","phrases","arguments","length","undefined","result","BX","message","hasOwnProperty","startsWith","freeze","componentId","_this2","componentParams","forEach","mutation","_applyMutation","_cloneObjectWithoutDuplicateFunction","_this3","components","cloneId","concat","objectParams","level","object","param","prototype","toString","call","typeof","toUpperCase","substr","clonedObject","replace","testNode","obj","i","j","len","tagName","RegExp","test","classList","contains","trim","className","getAttribute","Vue","VueVendor","window"],"mappings":"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","file":"vue.bitrix.bundle.map.js"}