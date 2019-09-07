/* jshint ignore:start */
/**
 * jQuery Once Plugin v1.2
 * http://plugins.jquery.com/project/once
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function ($) {
  var cache = {}, uuid = 0;

  /**
   * Filters elements by whether they have not yet been processed.
   *
   * @param id
   *   (Optional) If this is a string, then it will be used as the CSS class
   *   name that is applied to the elements for determining whether it has
   *   already been processed. The elements will get a class in the form of
   *   "id-processed".
   *
   *   If the id parameter is a function, it will be passed off to the fn
   *   parameter and the id will become a unique identifier, represented as a
   *   number.
   *
   *   When the id is neither a string or a function, it becomes a unique
   *   identifier, depicted as a number. The element's class will then be
   *   represented in the form of "jquery-once-#-processed".
   *
   *   Take note that the id must be valid for usage as an element's class name.
   * @param fn
   *   (Optional) If given, this function will be called for each element that
   *   has not yet been processed. The function's return value follows the same
   *   logic as $.each(). Returning true will continue to the next matched
   *   element in the set, while returning false will entirely break the
   *   iteration.
   */
  $.fn.once = function (id, fn) {
    if (typeof id != 'string') {
      // Generate a numeric ID if the id passed can't be used as a CSS class.
      if (!(id in cache)) {
        cache[id] = ++uuid;
      }
      // When the fn parameter is not passed, we interpret it from the id.
      if (!fn) {
        fn = id;
      }
      id = 'jquery-once-' + cache[id];
    }
    // Remove elements from the set that have already been processed.
    var name = id + '-processed';
    var elements = this.not('.' + name).addClass(name);

    return $.isFunction(fn) ? elements.each(fn) : elements;
  };

  /**
   * Filters elements that have been processed once already.
   *
   * @param id
   *   A required string representing the name of the class which should be used
   *   when filtering the elements. This only filters elements that have already
   *   been processed by the once function. The id should be the same id that
   *   was originally passed to the once() function.
   * @param fn
   *   (Optional) If given, this function will be called for each element that
   *   has not yet been processed. The function's return value follows the same
   *   logic as $.each(). Returning true will continue to the next matched
   *   element in the set, while returning false will entirely break the
   *   iteration.
   */
  $.fn.removeOnce = function (id, fn) {
    var name = id + '-processed';
    var elements = this.filter('.' + name).removeClass(name);

    return $.isFunction(fn) ? elements.each(fn) : elements;
  };
})(jQuery);

/* jshint ignore:start */
/*! jQuery Validation Plugin - v1.19.0 - 11/28/2018
 * https://jqueryvalidation.org/
 * Copyright (c) 2018 Jörn Zaefferer; Licensed MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof module&&module.exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){a.extend(a.fn,{validate:function(b){if(!this.length)return void(b&&b.debug&&window.console&&console.warn("Nothing selected, can't validate, returning nothing."));var c=a.data(this[0],"validator");return c?c:(this.attr("novalidate","novalidate"),c=new a.validator(b,this[0]),a.data(this[0],"validator",c),c.settings.onsubmit&&(this.on("click.validate",":submit",function(b){c.submitButton=b.currentTarget,a(this).hasClass("cancel")&&(c.cancelSubmit=!0),void 0!==a(this).attr("formnovalidate")&&(c.cancelSubmit=!0)}),this.on("submit.validate",function(b){function d(){var d,e;return c.submitButton&&(c.settings.submitHandler||c.formSubmitted)&&(d=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(a(c.submitButton).val()).appendTo(c.currentForm)),!(c.settings.submitHandler&&!c.settings.debug)||(e=c.settings.submitHandler.call(c,c.currentForm,b),d&&d.remove(),void 0!==e&&e)}return c.settings.debug&&b.preventDefault(),c.cancelSubmit?(c.cancelSubmit=!1,d()):c.form()?c.pendingRequest?(c.formSubmitted=!0,!1):d():(c.focusInvalid(),!1)})),c)},valid:function(){var b,c,d;return a(this[0]).is("form")?b=this.validate().form():(d=[],b=!0,c=a(this[0].form).validate(),this.each(function(){b=c.element(this)&&b,b||(d=d.concat(c.errorList))}),c.errorList=d),b},rules:function(b,c){var d,e,f,g,h,i,j=this[0],k="undefined"!=typeof this.attr("contenteditable")&&"false"!==this.attr("contenteditable");if(null!=j&&(!j.form&&k&&(j.form=this.closest("form")[0],j.name=this.attr("name")),null!=j.form)){if(b)switch(d=a.data(j.form,"validator").settings,e=d.rules,f=a.validator.staticRules(j),b){case"add":a.extend(f,a.validator.normalizeRule(c)),delete f.messages,e[j.name]=f,c.messages&&(d.messages[j.name]=a.extend(d.messages[j.name],c.messages));break;case"remove":return c?(i={},a.each(c.split(/\s/),function(a,b){i[b]=f[b],delete f[b]}),i):(delete e[j.name],f)}return g=a.validator.normalizeRules(a.extend({},a.validator.classRules(j),a.validator.attributeRules(j),a.validator.dataRules(j),a.validator.staticRules(j)),j),g.required&&(h=g.required,delete g.required,g=a.extend({required:h},g)),g.remote&&(h=g.remote,delete g.remote,g=a.extend(g,{remote:h})),g}}}),a.extend(a.expr.pseudos||a.expr[":"],{blank:function(b){return!a.trim(""+a(b).val())},filled:function(b){var c=a(b).val();return null!==c&&!!a.trim(""+c)},unchecked:function(b){return!a(b).prop("checked")}}),a.validator=function(b,c){this.settings=a.extend(!0,{},a.validator.defaults,b),this.currentForm=c,this.init()},a.validator.format=function(b,c){return 1===arguments.length?function(){var c=a.makeArray(arguments);return c.unshift(b),a.validator.format.apply(this,c)}:void 0===c?b:(arguments.length>2&&c.constructor!==Array&&(c=a.makeArray(arguments).slice(1)),c.constructor!==Array&&(c=[c]),a.each(c,function(a,c){b=b.replace(new RegExp("\\{"+a+"\\}","g"),function(){return c})}),b)},a.extend(a.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",pendingClass:"pending",validClass:"valid",errorElement:"label",focusCleanup:!1,focusInvalid:!0,errorContainer:a([]),errorLabelContainer:a([]),onsubmit:!0,ignore:":hidden",ignoreTitle:!1,onfocusin:function(a){this.lastActive=a,this.settings.focusCleanup&&(this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass),this.hideThese(this.errorsFor(a)))},onfocusout:function(a){this.checkable(a)||!(a.name in this.submitted)&&this.optional(a)||this.element(a)},onkeyup:function(b,c){var d=[16,17,18,20,35,36,37,38,39,40,45,144,225];9===c.which&&""===this.elementValue(b)||a.inArray(c.keyCode,d)!==-1||(b.name in this.submitted||b.name in this.invalid)&&this.element(b)},onclick:function(a){a.name in this.submitted?this.element(a):a.parentNode.name in this.submitted&&this.element(a.parentNode)},highlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).addClass(c).removeClass(d):a(b).addClass(c).removeClass(d)},unhighlight:function(b,c,d){"radio"===b.type?this.findByName(b.name).removeClass(c).addClass(d):a(b).removeClass(c).addClass(d)}},setDefaults:function(b){a.extend(a.validator.defaults,b)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",equalTo:"Please enter the same value again.",maxlength:a.validator.format("Please enter no more than {0} characters."),minlength:a.validator.format("Please enter at least {0} characters."),rangelength:a.validator.format("Please enter a value between {0} and {1} characters long."),range:a.validator.format("Please enter a value between {0} and {1}."),max:a.validator.format("Please enter a value less than or equal to {0}."),min:a.validator.format("Please enter a value greater than or equal to {0}."),step:a.validator.format("Please enter a multiple of {0}.")},autoCreateRanges:!1,prototype:{init:function(){function b(b){var c="undefined"!=typeof a(this).attr("contenteditable")&&"false"!==a(this).attr("contenteditable");if(!this.form&&c&&(this.form=a(this).closest("form")[0],this.name=a(this).attr("name")),d===this.form){var e=a.data(this.form,"validator"),f="on"+b.type.replace(/^validate/,""),g=e.settings;g[f]&&!a(this).is(g.ignore)&&g[f].call(e,this,b)}}this.labelContainer=a(this.settings.errorLabelContainer),this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm),this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer),this.submitted={},this.valueCache={},this.pendingRequest=0,this.pending={},this.invalid={},this.reset();var c,d=this.currentForm,e=this.groups={};a.each(this.settings.groups,function(b,c){"string"==typeof c&&(c=c.split(/\s/)),a.each(c,function(a,c){e[c]=b})}),c=this.settings.rules,a.each(c,function(b,d){c[b]=a.validator.normalizeRule(d)}),a(this.currentForm).on("focusin.validate focusout.validate keyup.validate",":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox'], [contenteditable], [type='button']",b).on("click.validate","select, option, [type='radio'], [type='checkbox']",b),this.settings.invalidHandler&&a(this.currentForm).on("invalid-form.validate",this.settings.invalidHandler)},form:function(){return this.checkForm(),a.extend(this.submitted,this.errorMap),this.invalid=a.extend({},this.errorMap),this.valid()||a(this.currentForm).triggerHandler("invalid-form",[this]),this.showErrors(),this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);return this.valid()},element:function(b){var c,d,e=this.clean(b),f=this.validationTargetFor(e),g=this,h=!0;return void 0===f?delete this.invalid[e.name]:(this.prepareElement(f),this.currentElements=a(f),d=this.groups[f.name],d&&a.each(this.groups,function(a,b){b===d&&a!==f.name&&(e=g.validationTargetFor(g.clean(g.findByName(a))),e&&e.name in g.invalid&&(g.currentElements.push(e),h=g.check(e)&&h))}),c=this.check(f)!==!1,h=h&&c,c?this.invalid[f.name]=!1:this.invalid[f.name]=!0,this.numberOfInvalids()||(this.toHide=this.toHide.add(this.containers)),this.showErrors(),a(b).attr("aria-invalid",!c)),h},showErrors:function(b){if(b){var c=this;a.extend(this.errorMap,b),this.errorList=a.map(this.errorMap,function(a,b){return{message:a,element:c.findByName(b)[0]}}),this.successList=a.grep(this.successList,function(a){return!(a.name in b)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){a.fn.resetForm&&a(this.currentForm).resetForm(),this.invalid={},this.submitted={},this.prepareForm(),this.hideErrors();var b=this.elements().removeData("previousValue").removeAttr("aria-invalid");this.resetElements(b)},resetElements:function(a){var b;if(this.settings.unhighlight)for(b=0;a[b];b++)this.settings.unhighlight.call(this,a[b],this.settings.errorClass,""),this.findByName(a[b].name).removeClass(this.settings.validClass);else a.removeClass(this.settings.errorClass).removeClass(this.settings.validClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b,c=0;for(b in a)void 0!==a[b]&&null!==a[b]&&a[b]!==!1&&c++;return c},hideErrors:function(){this.hideThese(this.toHide)},hideThese:function(a){a.not(this.containers).text(""),this.addWrapper(a).hide()},valid:function(){return 0===this.size()},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(b){}},findLastActive:function(){var b=this.lastActive;return b&&1===a.grep(this.errorList,function(a){return a.element.name===b.name}).length&&b},elements:function(){var b=this,c={};return a(this.currentForm).find("input, select, textarea, [contenteditable]").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter(function(){var d=this.name||a(this).attr("name"),e="undefined"!=typeof a(this).attr("contenteditable")&&"false"!==a(this).attr("contenteditable");return!d&&b.settings.debug&&window.console&&console.error("%o has no name assigned",this),e&&(this.form=a(this).closest("form")[0],this.name=d),this.form===b.currentForm&&(!(d in c||!b.objectLength(a(this).rules()))&&(c[d]=!0,!0))})},clean:function(b){return a(b)[0]},errors:function(){var b=this.settings.errorClass.split(" ").join(".");return a(this.settings.errorElement+"."+b,this.errorContext)},resetInternals:function(){this.successList=[],this.errorList=[],this.errorMap={},this.toShow=a([]),this.toHide=a([])},reset:function(){this.resetInternals(),this.currentElements=a([])},prepareForm:function(){this.reset(),this.toHide=this.errors().add(this.containers)},prepareElement:function(a){this.reset(),this.toHide=this.errorsFor(a)},elementValue:function(b){var c,d,e=a(b),f=b.type,g="undefined"!=typeof e.attr("contenteditable")&&"false"!==e.attr("contenteditable");return"radio"===f||"checkbox"===f?this.findByName(b.name).filter(":checked").val():"number"===f&&"undefined"!=typeof b.validity?b.validity.badInput?"NaN":e.val():(c=g?e.text():e.val(),"file"===f?"C:\\fakepath\\"===c.substr(0,12)?c.substr(12):(d=c.lastIndexOf("/"),d>=0?c.substr(d+1):(d=c.lastIndexOf("\\"),d>=0?c.substr(d+1):c)):"string"==typeof c?c.replace(/\r/g,""):c)},check:function(b){b=this.validationTargetFor(this.clean(b));var c,d,e,f,g=a(b).rules(),h=a.map(g,function(a,b){return b}).length,i=!1,j=this.elementValue(b);"function"==typeof g.normalizer?f=g.normalizer:"function"==typeof this.settings.normalizer&&(f=this.settings.normalizer),f&&(j=f.call(b,j),delete g.normalizer);for(d in g){e={method:d,parameters:g[d]};try{if(c=a.validator.methods[d].call(this,j,b,e.parameters),"dependency-mismatch"===c&&1===h){i=!0;continue}if(i=!1,"pending"===c)return void(this.toHide=this.toHide.not(this.errorsFor(b)));if(!c)return this.formatAndAdd(b,e),!1}catch(k){throw this.settings.debug&&window.console&&console.log("Exception occurred when checking element "+b.id+", check the '"+e.method+"' method.",k),k instanceof TypeError&&(k.message+=".  Exception occurred when checking element "+b.id+", check the '"+e.method+"' method."),k}}if(!i)return this.objectLength(g)&&this.successList.push(b),!0},customDataMessage:function(b,c){return a(b).data("msg"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase())||a(b).data("msg")},customMessage:function(a,b){var c=this.settings.messages[a];return c&&(c.constructor===String?c:c[b])},findDefined:function(){for(var a=0;a<arguments.length;a++)if(void 0!==arguments[a])return arguments[a]},defaultMessage:function(b,c){"string"==typeof c&&(c={method:c});var d=this.findDefined(this.customMessage(b.name,c.method),this.customDataMessage(b,c.method),!this.settings.ignoreTitle&&b.title||void 0,a.validator.messages[c.method],"<strong>Warning: No message defined for "+b.name+"</strong>"),e=/\$?\{(\d+)\}/g;return"function"==typeof d?d=d.call(this,c.parameters,b):e.test(d)&&(d=a.validator.format(d.replace(e,"{$1}"),c.parameters)),d},formatAndAdd:function(a,b){var c=this.defaultMessage(a,b);this.errorList.push({message:c,element:a,method:b.method}),this.errorMap[a.name]=c,this.submitted[a.name]=c},addWrapper:function(a){return this.settings.wrapper&&(a=a.add(a.parent(this.settings.wrapper))),a},defaultShowErrors:function(){var a,b,c;for(a=0;this.errorList[a];a++)c=this.errorList[a],this.settings.highlight&&this.settings.highlight.call(this,c.element,this.settings.errorClass,this.settings.validClass),this.showLabel(c.element,c.message);if(this.errorList.length&&(this.toShow=this.toShow.add(this.containers)),this.settings.success)for(a=0;this.successList[a];a++)this.showLabel(this.successList[a]);if(this.settings.unhighlight)for(a=0,b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass);this.toHide=this.toHide.not(this.toShow),this.hideErrors(),this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return a(this.errorList).map(function(){return this.element})},showLabel:function(b,c){var d,e,f,g,h=this.errorsFor(b),i=this.idOrName(b),j=a(b).attr("aria-describedby");h.length?(h.removeClass(this.settings.validClass).addClass(this.settings.errorClass),h.html(c)):(h=a("<"+this.settings.errorElement+">").attr("id",i+"-error").addClass(this.settings.errorClass).html(c||""),d=h,this.settings.wrapper&&(d=h.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()),this.labelContainer.length?this.labelContainer.append(d):this.settings.errorPlacement?this.settings.errorPlacement.call(this,d,a(b)):d.insertAfter(b),h.is("label")?h.attr("for",i):0===h.parents("label[for='"+this.escapeCssMeta(i)+"']").length&&(f=h.attr("id"),j?j.match(new RegExp("\\b"+this.escapeCssMeta(f)+"\\b"))||(j+=" "+f):j=f,a(b).attr("aria-describedby",j),e=this.groups[b.name],e&&(g=this,a.each(g.groups,function(b,c){c===e&&a("[name='"+g.escapeCssMeta(b)+"']",g.currentForm).attr("aria-describedby",h.attr("id"))})))),!c&&this.settings.success&&(h.text(""),"string"==typeof this.settings.success?h.addClass(this.settings.success):this.settings.success(h,b)),this.toShow=this.toShow.add(h)},errorsFor:function(b){var c=this.escapeCssMeta(this.idOrName(b)),d=a(b).attr("aria-describedby"),e="label[for='"+c+"'], label[for='"+c+"'] *";return d&&(e=e+", #"+this.escapeCssMeta(d).replace(/\s+/g,", #")),this.errors().filter(e)},escapeCssMeta:function(a){return a.replace(/([\\!"#$%&'()*+,.\/:;<=>?@\[\]^`{|}~])/g,"\\$1")},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},validationTargetFor:function(b){return this.checkable(b)&&(b=this.findByName(b.name)),a(b).not(this.settings.ignore)[0]},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(b){return a(this.currentForm).find("[name='"+this.escapeCssMeta(b)+"']")},getLength:function(b,c){switch(c.nodeName.toLowerCase()){case"select":return a("option:selected",c).length;case"input":if(this.checkable(c))return this.findByName(c.name).filter(":checked").length}return b.length},depend:function(a,b){return!this.dependTypes[typeof a]||this.dependTypes[typeof a](a,b)},dependTypes:{"boolean":function(a){return a},string:function(b,c){return!!a(b,c.form).length},"function":function(a,b){return a(b)}},optional:function(b){var c=this.elementValue(b);return!a.validator.methods.required.call(this,c,b)&&"dependency-mismatch"},startRequest:function(b){this.pending[b.name]||(this.pendingRequest++,a(b).addClass(this.settings.pendingClass),this.pending[b.name]=!0)},stopRequest:function(b,c){this.pendingRequest--,this.pendingRequest<0&&(this.pendingRequest=0),delete this.pending[b.name],a(b).removeClass(this.settings.pendingClass),c&&0===this.pendingRequest&&this.formSubmitted&&this.form()?(a(this.currentForm).submit(),this.submitButton&&a("input:hidden[name='"+this.submitButton.name+"']",this.currentForm).remove(),this.formSubmitted=!1):!c&&0===this.pendingRequest&&this.formSubmitted&&(a(this.currentForm).triggerHandler("invalid-form",[this]),this.formSubmitted=!1)},previousValue:function(b,c){return c="string"==typeof c&&c||"remote",a.data(b,"previousValue")||a.data(b,"previousValue",{old:null,valid:!0,message:this.defaultMessage(b,{method:c})})},destroy:function(){this.resetForm(),a(this.currentForm).off(".validate").removeData("validator").find(".validate-equalTo-blur").off(".validate-equalTo").removeClass("validate-equalTo-blur").find(".validate-lessThan-blur").off(".validate-lessThan").removeClass("validate-lessThan-blur").find(".validate-lessThanEqual-blur").off(".validate-lessThanEqual").removeClass("validate-lessThanEqual-blur").find(".validate-greaterThanEqual-blur").off(".validate-greaterThanEqual").removeClass("validate-greaterThanEqual-blur").find(".validate-greaterThan-blur").off(".validate-greaterThan").removeClass("validate-greaterThan-blur")}},classRuleSettings:{required:{required:!0},email:{email:!0},url:{url:!0},date:{date:!0},dateISO:{dateISO:!0},number:{number:!0},digits:{digits:!0},creditcard:{creditcard:!0}},addClassRules:function(b,c){b.constructor===String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)},classRules:function(b){var c={},d=a(b).attr("class");return d&&a.each(d.split(" "),function(){this in a.validator.classRuleSettings&&a.extend(c,a.validator.classRuleSettings[this])}),c},normalizeAttributeRule:function(a,b,c,d){/min|max|step/.test(c)&&(null===b||/number|range|text/.test(b))&&(d=Number(d),isNaN(d)&&(d=void 0)),d||0===d?a[c]=d:b===c&&"range"!==b&&(a[c]=!0)},attributeRules:function(b){var c,d,e={},f=a(b),g=b.getAttribute("type");for(c in a.validator.methods)"required"===c?(d=b.getAttribute(c),""===d&&(d=!0),d=!!d):d=f.attr(c),this.normalizeAttributeRule(e,g,c,d);return e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)&&delete e.maxlength,e},dataRules:function(b){var c,d,e={},f=a(b),g=b.getAttribute("type");for(c in a.validator.methods)d=f.data("rule"+c.charAt(0).toUpperCase()+c.substring(1).toLowerCase()),""===d&&(d=!0),this.normalizeAttributeRule(e,g,c,d);return e},staticRules:function(b){var c={},d=a.data(b.form,"validator");return d.settings.rules&&(c=a.validator.normalizeRule(d.settings.rules[b.name])||{}),c},normalizeRules:function(b,c){return a.each(b,function(d,e){if(e===!1)return void delete b[d];if(e.param||e.depends){var f=!0;switch(typeof e.depends){case"string":f=!!a(e.depends,c.form).length;break;case"function":f=e.depends.call(c,c)}f?b[d]=void 0===e.param||e.param:(a.data(c.form,"validator").resetElements(a(c)),delete b[d])}}),a.each(b,function(d,e){b[d]=a.isFunction(e)&&"normalizer"!==d?e(c):e}),a.each(["minlength","maxlength"],function(){b[this]&&(b[this]=Number(b[this]))}),a.each(["rangelength","range"],function(){var c;b[this]&&(a.isArray(b[this])?b[this]=[Number(b[this][0]),Number(b[this][1])]:"string"==typeof b[this]&&(c=b[this].replace(/[\[\]]/g,"").split(/[\s,]+/),b[this]=[Number(c[0]),Number(c[1])]))}),a.validator.autoCreateRanges&&(null!=b.min&&null!=b.max&&(b.range=[b.min,b.max],delete b.min,delete b.max),null!=b.minlength&&null!=b.maxlength&&(b.rangelength=[b.minlength,b.maxlength],delete b.minlength,delete b.maxlength)),b},normalizeRule:function(b){if("string"==typeof b){var c={};a.each(b.split(/\s/),function(){c[this]=!0}),b=c}return b},addMethod:function(b,c,d){a.validator.methods[b]=c,a.validator.messages[b]=void 0!==d?d:a.validator.messages[b],c.length<3&&a.validator.addClassRules(b,a.validator.normalizeRule(b))},methods:{required:function(b,c,d){if(!this.depend(d,c))return"dependency-mismatch";if("select"===c.nodeName.toLowerCase()){var e=a(c).val();return e&&e.length>0}return this.checkable(c)?this.getLength(b,c)>0:void 0!==b&&null!==b&&b.length>0},email:function(a,b){return this.optional(b)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(a)},url:function(a,b){return this.optional(b)||/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[\/?#]\S*)?$/i.test(a)},date:function(){var a=!1;return function(b,c){return a||(a=!0,this.settings.debug&&window.console&&console.warn("The `date` method is deprecated and will be removed in version '2.0.0'.\nPlease don't use it, since it relies on the Date constructor, which\nbehaves very differently across browsers and locales. Use `dateISO`\ninstead or one of the locale specific methods in `localizations/`\nand `additional-methods.js`.")),this.optional(c)||!/Invalid|NaN/.test(new Date(b).toString())}}(),dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(a)},number:function(a,b){return this.optional(b)||/^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},minlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d},maxlength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e<=d},rangelength:function(b,c,d){var e=a.isArray(b)?b.length:this.getLength(b,c);return this.optional(c)||e>=d[0]&&e<=d[1]},min:function(a,b,c){return this.optional(b)||a>=c},max:function(a,b,c){return this.optional(b)||a<=c},range:function(a,b,c){return this.optional(b)||a>=c[0]&&a<=c[1]},step:function(b,c,d){var e,f=a(c).attr("type"),g="Step attribute on input type "+f+" is not supported.",h=["text","number","range"],i=new RegExp("\\b"+f+"\\b"),j=f&&!i.test(h.join()),k=function(a){var b=(""+a).match(/(?:\.(\d+))?$/);return b&&b[1]?b[1].length:0},l=function(a){return Math.round(a*Math.pow(10,e))},m=!0;if(j)throw new Error(g);return e=k(d),(k(b)>e||l(b)%l(d)!==0)&&(m=!1),this.optional(c)||m},equalTo:function(b,c,d){var e=a(d);return this.settings.onfocusout&&e.not(".validate-equalTo-blur").length&&e.addClass("validate-equalTo-blur").on("blur.validate-equalTo",function(){a(c).valid()}),b===e.val()},remote:function(b,c,d,e){if(this.optional(c))return"dependency-mismatch";e="string"==typeof e&&e||"remote";var f,g,h,i=this.previousValue(c,e);return this.settings.messages[c.name]||(this.settings.messages[c.name]={}),i.originalMessage=i.originalMessage||this.settings.messages[c.name][e],this.settings.messages[c.name][e]=i.message,d="string"==typeof d&&{url:d}||d,h=a.param(a.extend({data:b},d.data)),i.old===h?i.valid:(i.old=h,f=this,this.startRequest(c),g={},g[c.name]=b,a.ajax(a.extend(!0,{mode:"abort",port:"validate"+c.name,dataType:"json",data:g,context:f.currentForm,success:function(a){var d,g,h,j=a===!0||"true"===a;f.settings.messages[c.name][e]=i.originalMessage,j?(h=f.formSubmitted,f.resetInternals(),f.toHide=f.errorsFor(c),f.formSubmitted=h,f.successList.push(c),f.invalid[c.name]=!1,f.showErrors()):(d={},g=a||f.defaultMessage(c,{method:e,parameters:b}),d[c.name]=i.message=g,f.invalid[c.name]=!0,f.showErrors(d)),i.valid=j,f.stopRequest(c,j)}},d)),"pending")}}});var b,c={};return a.ajaxPrefilter?a.ajaxPrefilter(function(a,b,d){var e=a.port;"abort"===a.mode&&(c[e]&&c[e].abort(),c[e]=d)}):(b=a.ajax,a.ajax=function(d){var e=("mode"in d?d:a.ajaxSettings).mode,f=("port"in d?d:a.ajaxSettings).port;return"abort"===e?(c[f]&&c[f].abort(),c[f]=b.apply(this,arguments),c[f]):b.apply(this,arguments)}),a});
// App
(function($){

	// Get Parameters from string
	var getParamsFromString = function(a) {
		if (a == "") a = window.location.search;
		a = a.substr(1).split('&');
	    if (a == "") return {};
	    var b = {};
	    for (var i = 0; i < a.length; ++i)
	    {
	        var p=a[i].split('=', 2);
	        if (p.length == 1)
	            b[p[0]] = "";
	        else
	            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
	    }
	    return b;
	};

	// Set AJAX Header with csrf token
	var AjaxTokenSetup = function(){

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': csrf_token
	        }
		});

		$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
			if(window.ajaxDebug) console.log([event, jqxhr, settings, thrownError]);
		});

	}

	// Open parent menu items
	var setupSidebar = function(){

		$('.main-sidebar .treeview-menu li.active').closest('li.treeview').addClass('active').addClass('menu-open');

	}

	// Init tables list
	var DataTables = function(){

		$('table[data-table="basic"]').each(function(){
			var _this = $(this);
			var table = _this.DataTable({
				'dom': (_this.attr('data-filters')?'<"row"<"col-sm-12"t>><"row"<"col-sm-5"i><"col-sm-7"p>>':''),
				'paging'      : _this.attr('data-paging') || true,
				'lengthChange': _this.attr('data-lengthChange') || true,
				'searching'   : _this.attr('data-searching') || true,
				'ordering'    : _this.attr('data-ordering') || true,
				'info'        : _this.attr('data-info') || true,
				'autoWidth'   : _this.attr('data-autoWidth') || false,
				"order": [[ _this.attr('data-orderIndex') || 1, _this.attr('data-orderASC') || "desc" ]],
				'columnDefs'  : [ { "targets": "no-sort", "orderable": false } ],
				'initComplete': function(){
					var dataTable = this;

					if(_this.attr('data-filters')) {


						$( _this.attr('data-filters') ).find('input[data-type],select[data-type]').on('change input keydown',function(){
							switch ( $(this).attr('data-type') ) {
								case 'lengthChange':
									dataTable.api().page.len( $(this).val() ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);
									break;

								case 'lengthRange':
									var val = $(this).val();
									var colIndex = $(this).attr('data-col'),
										column = dataTable.api().column(colIndex);

									switch (val) {
										case String(val.match(/7days/)):
										case String(val.match(/30days/)):
											console.log('Log message');
											var length = /7days/.test(val) ? 7 : 30;
											var start = new Date();
											var dates = {};
											for(let n = 0; n < length; n++){
												var date = new Date(new Date().setDate(new Date().getDate() - n));
												var yyyy = date.getFullYear().toString();
												var mm = (date.getMonth()+1).toString();
												var dd = date.getDate().toString();

												if( !dates[yyyy] ) dates[yyyy] = {};
												if( !dates[yyyy][mm] ) dates[yyyy][mm] = [];
												dates[yyyy][mm][n] = dd;
											}
											var regex = [];
											$.each(dates,function(year, months) {
												$.each(months,function(month, days) {
													regex.push( '^0?(' +days.join('|')+ ')\\/0?(' +month+ ')\\/' +year );
												});
											});
											val = regex.join('|');
											break;
										default:
											val = '\\d{2}\\/'+val;
											break;
									}

									column.search( val, true ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);

									break;
							}
						});

						$( _this.attr('data-filters') ).find('input[data-col],select[data-col]').not('[data-type]').on('change input keydown',function(){

							var colIndex = $(this).attr('data-col'),
								column = dataTable.api().column(colIndex);

							if( $(this).is('[type="checkbox"]') ){
	                        	var val = $.fn.dataTable.util.escapeRegex( $(this).is(':checked') ? $(this).val() : '' );
	                        } else {
	                        	var val = $.fn.dataTable.util.escapeRegex( $(this).val() );
	                        }

			                column.search( val ).draw();

							var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
			                history.replaceState(null,null,'#'+hash);

						});

						var params = getParamsFromString( window.location.hash );

						$.each(params,function(k,v){
							$( _this.attr('data-filters') ).find('[name="'+k+'"]').val(v).trigger('change');
						});
					}
				}
			});
	    });

	    $('table[data-table="ajax"]').each(function(){
			var _this = $(this);
			var columnNames = function(){
				var names = [];
				_this.find('thead th').each(function(){
					names.push( { name: $(this).attr('data-name') } );
				});
				return names;
			}
			var columnDefaults = function(){
				var cols = new Array( _this.find('thead th').length );

				$( _this.attr('data-filters') ).find('[data-name]').each(function(){
					var name = $(this).attr('data-name');
					var i = _this.find('thead th[data-name="'+name+'"]').index();
					$(this).attr('data-col',i);
					var v = null;
					if( $(this).is('[type="checkbox"],[type="radio"]') && $(this).is(':checked') || !$(this).is('[type="checkbox"],[type="radio"]') ) {
						v = $(this).val() || $(this).attr('value');
					} else if( !$(this).is('[type="checkbox"],[type="radio"]') ) {
						v = $(this).val() || $(this).attr('value');
					}
					cols[i] = { 'search' : v };
				});

				return cols;
			}
			var table = _this.DataTable({
				"processing": false,
				"serverSide": true,
				'dom': (_this.attr('data-filters')?'<"table-responsive"t><"row"<"col-sm-5"i><"col-sm-7"p>>':''),
				'ajax'		  : {
					'url': _this.attr('data-url'),
					'data': function(data) {
						if(_this.data('loaded')) return;

						var params = getParamsFromString( window.location.hash );

						$.each(params,function(k,v){
							var filter =  $( _this.attr('data-filters') ).find('[name="'+k+'"]');

							if( filter.is('[data-type]') ) {
								switch ( filter.attr('data-type') ) {
									case 'lengthChange':
										data.length = v;
										break;
									case 'lengthRange':
										var colIndex = $( _this.attr('data-filters') ).find('[name="'+k+'"]').attr('data-col');
										if( data.columns[colIndex] ) data.columns[colIndex].search.value = v;
										break;
								}
							} else {
								var colIndex = $( _this.attr('data-filters') ).find('[name="'+k+'"]').attr('data-col');
								if( data.columns[colIndex] ) data.columns[colIndex].search.value = v;
							}

							if(filter.is('[type="checkbox"],[type="radio"]')){
								filter.filter('[value="'+v+'"]').prop('checked',1);
							} else {
								filter.val(v);
							}
						});

						return data;
					},
					complete: function(){
						InitTooltips();
					}
				},
				'paging'      : _this.attr('data-paging') || true,
				'lengthChange': _this.attr('data-lengthChange') || true,
				'searching'   : _this.attr('data-searching') || true,
				'ordering'    : _this.attr('data-ordering') || true,
				'info'        : _this.attr('data-info') || true,
				'autoWidth'   : _this.attr('data-autoWidth') || false,
				"order": [[ _this.attr('data-orderIndex') || 1, _this.attr('data-orderASC') || "desc" ]],
				'columnDefs'  : [ { "targets": "no-sort", "orderable": false } ],
				'columns' : columnNames(),
				'searchCols': columnDefaults(),
				'initComplete': function(){
					var dataTable = this;
					_this.data('loaded',true);

					if(dataTable.attr('data-filters')) {

						$( dataTable.attr('data-filters') ).find('input[data-type],select[data-type]').on('change keydown',function(e){

							if (e.originalEvent && e.originalEvent.key && e.originalEvent.key.length > 1) {
								return;
							}

							if(dataTable.DataTable().context[0].jqXHR) {
								dataTable.DataTable().context[0].jqXHR.abort();
							}

							switch ( $(this).attr('data-type') ) {
								case 'lengthChange':
									dataTable.api().page.len( $(this).val() ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);
									break;

								case 'lengthRange':
									var val = $(this).val();
									var colIndex = $(this).attr('data-col'),
										column = dataTable.api().column(colIndex);

									switch (val) {
										case String(val.match(/7days/)):
										case String(val.match(/30days/)):
											var length = /7days/.test(val) ? 7 : 30;
											var start = new Date();
											var dates = {};
											for(let n = 0; n < length; n++){
												var date = new Date(new Date().setDate(new Date().getDate() - n));
												var yyyy = date.getFullYear().toString();
												var mm = (date.getMonth()+1).toString();
												var dd = date.getDate().toString();

												if( !dates[yyyy] ) dates[yyyy] = {};
												if( !dates[yyyy][mm] ) dates[yyyy][mm] = [];
												dates[yyyy][mm][n] = dd;
											}
											var regex = [];
											$.each(dates,function(year, months) {
												$.each(months,function(month, days) {
													regex.push( '^0?(' +days.join('|')+ ')\\/0?(' +month+ ')\\/' +year );
												});
											});
											val = regex.join('|');
											break;
										default:
											val = '\\d{2}\\/'+val;
											break;
									}

									column.search( val, true ).draw();

									var hash = $( dataTable.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);

									break;
							}
						});

						$( dataTable.attr('data-filters') ).find('input[data-col],select[data-col]').not('[data-type]').on('change keydown',function(e){

							if (e.type == 'keydown' && e.originalEvent && e.originalEvent.key && e.originalEvent.keyCode !== 8 && e.originalEvent.key.length > 1) {
								return;
							}

							var _this = $(this);

							if(_this.is('select') && e.type !== 'change') return;


							if(dataTable.DataTable().context[0].jqXHR) {
								dataTable.DataTable().context[0].jqXHR.abort();
							}

							var _this = $(this);

							if(_this.is('select') && e.type !== 'change') return;
							if(_this.is('[type="text"]') && e.type !== 'keydown') return;
							// if(_this.is('[type="checkbox"],[type="radio"]') && e.type !== 'change') return;
							// if(!_this.is('[type="checkbox"],[type="radio"]') && e.type !== 'change') return;

							if( window.dataTableUpdate ) clearTimeout(window.dataTableUpdate);

							window.dataTableUpdate = setTimeout(function(){

								var colIndex = _this.attr('data-col'),
									column = dataTable.api().column(colIndex);

								if( _this.is('[type="checkbox"]') ){
		                        	var val = _this.is(':checked') ? _this.val() : '';
		                        } else if( _this.is('select') ){
		                        	var val = _this.val();
		                        } else {
		                        	var val = _this.val() || _this.attr('value') || '';
		                        }

				                column.search( val ).draw();

								var hash = $( dataTable.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
				                history.replaceState(null,null,'#'+hash);

				            }, 300);

						});
					}

					dataTable.on('processing.dt', function (e, settings, processing) {
						if(processing) {
							dataTable.find('tbody').html('<tr><td colspan="'+columnNames().length+'" style="padding:0;"><div class="progress active" style="margin:0;"><div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span>Loading...</span></div></div></td></tr>');
						}
					});
				}
			});
		});

	}

	// Setup Datepikers
	var DatePickers = function(){
		$('.datepicker').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		});

		$('.btn-clear').on('click',function(){
			var id = $(this).attr('data-id');
			$('#'+id).val('').trigger('change');
		});
	}

	// Setup CKEditor WYWIWYG
	var InitCKEditor = function(){

		$('textarea.editor').each(function(){
	  		var id = $(this).attr('id');
	  		CKEDITOR.replace(id, {
	  			toolbarGroups: [
					{ name: 'styles', groups: [ 'styles' ] },
					{ name: 'colors', groups: [ 'colors' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
					{ name: 'forms', groups: [ 'forms' ] },
					{ name: 'paragraph', groups: [ 'align', 'blocks', 'list', 'indent', 'bidi', 'paragraph' ] },
					{ name: 'links', groups: [ 'links' ] },
					{ name: 'insert', groups: [ 'insert' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
					{ name: 'tools', groups: [ 'tools' ] },
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
					{ name: 'others', groups: [ 'others' ] },
					{ name: 'about', groups: [ 'about' ] }
				],

				removeButtons: 'Save,Templates,NewPage,Preview,Print,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,ImageButton,PasteFromWord,PasteText,Subscript,Superscript,Strike,CopyFormatting,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,SpecialChar,PageBreak,Iframe,Maximize,About,BGColor,ShowBlocks',
				removeFormatAttributes: "lang,width,height,align,hspace,valign",

				extraPlugins: 'uploadfile,uploadwidget,justify',
				removePlugins: 'dragdrop,basket',
				filebrowserUploadUrl: fb_upload,
				filebrowserImageUploadUrl: fb_imageupload,
				filebrowserBrowseUrl: '/admin/files/browse?type=Files',
				filebrowserImageBrowseUrl: '/admin/files/browse?type=Images',
				filebrowserUploadMethod: 'form',
	  		});
	  	});

	}

	// File Upload for Media
	var UploadFile = function(){

		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		        	var id = $(input).attr('id');
		            $('#'+id+'-preview').attr('src', e.target.result);
		        }

		        reader.readAsDataURL(input.files[0]);
		    }
		}

		function CSVImportGetHeaders(input,modal) {
			$(modal).find('select').html('<option value="">- Select -</option>');

			if (input.files && input.files[0]) {
			    // Get our CSV file from upload
			    var file = input.files[0];

			    // Instantiate a new FileReader
			    var reader = new FileReader();

			    // Read our file to an ArrayBuffer
			    reader.readAsArrayBuffer(file);

			    // Handler for onloadend event.  Triggered each time the reading operation is completed (success or failure) 
			    reader.onloadend = function (evt) {
			        // Get the Array Buffer
			        var data = evt.target.result;

			        // Grab our byte length
			        var byteLength = data.byteLength;

			        // Convert to conventional array, so we can iterate though it
			        var ui8a = new Uint8Array(data, 0);

			        // Used to store each character that makes up CSV header
			        var headerString = '';

			        // Iterate through each character in our Array
			        for (var i = 0; i < byteLength; i++) {
			            // Get the character for the current iteration
			            var char = String.fromCharCode(ui8a[i]);

			            // Check if the char is a new line
			            if (char.match(/[^\r\n]+/g) !== null) {

			                // Not a new line so lets append it to our header string and keep processing
			                headerString += char;
			            } else {
			                // We found a new line character, stop processing
			                break;
			            }
			        }

			        // Split our header string into an array
			        var headers = headerString.split(',');

			        $(modal).find('select').each(function(e){
				    	var _select = $(this);
				    	_select.html('<option value="">- Select -</option>');
				    	$.each(headers,function(i,e){
				    		e = e.replace(/\"/g,'');
				    		var selected = _select.attr('name').endsWith(e);
				    		_select.append('<option value="'+e+'" '+(selected?'selected':'')+'>'+e+'</option>');
				    	});
				    });
				    $(modal).modal('show');
			    };
			}
		}

		$(document).on('change', '.btn-file :file', function() {
			var input = $(this),
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;

		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
		});

		$(".show-preview").change(function(){
		    readURL(this);
		});

		$(".read-upvo").change(function(){
		    CSVImportGetHeaders(this,'#modal-upvo');
		});

		$("#sms_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-sms-upload');
		});

		$("#sms_file_upload_2").change(function(){
		    CSVImportGetHeaders(this,'#modal-sms-upload-2');
		});

		$("#eligible_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-eligible-upload');
		});

		$("#active_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-active-upload');
		});

		$("#tracking_codes").change(function(){
		    CSVImportGetHeaders(this,'#modal-tracking');
		});

		// $('#tracking_codes').on('hide.bs.modal', function () {
		// 	// var valid = true;
		// 	// $(this).find('select').each(function(){
		// 	// 	$(this).closest('.form-group').removeClass('has-error');
		// 	// 	if(!$(this).val()) {
		// 	// 		$(this).closest('.form-group').addClass('has-error');
		// 	// 		valid = false;
		// 	// 	}
		// 	// });
		//  //    return valid;
		// })

		$('.btn-clear[data-id]').on('click',function(){
			var id = $(this).attr('data-id');
			$('#'+id+'-preview').attr('src', '');
			$('input#'+id).val('');
			$(this).closest('.input-group').find(':text').val('');
		});

	};

	var CampaignFormBuilder = function(){
		var builderElem = document.getElementById('form_builder');
		var formContent = $('#form_content');

		var defaultFields = $(formContent).val() ? JSON.parse( $(formContent).val() ) : [{
                type: "text",
                label: "First name",
                className: "form-control",
                name: "first_name",
                required: true,
                wrapperColumns: 'col-xs-12 col-sm-6'
            }, {
                type: "text",
                label: "Last name",
                className: "form-control",
                name: "last_name",
                required: true,
                wrapperColumns: 'col-xs-12 col-sm-6'
            }];

		var formBuilder = $( builderElem ).formBuilder({
			showActionButtons: false,
			disableFields: ['autocomplete','button','header'],
			disabledFieldButtons: {
				row: ['edit'],
				recaptcha: ['edit']
			},
			disabledSubtypes: {
				text: ['password','color','email','tel']
			},
			disabledAttrs: ["access"],
			fields: [{
				label: "Email",
				type: "email",
				icon: "<i class=\"fa fa-envelope-o\"></i>",
			}, {
				label: "Phone",
				type: "tel",
				icon: "<i class=\"fa fa-phone\"></i>",
			}, {
				label: "Row break",
				type: "row",
				icon: "–",
			}, {
				label: "Conditional row",
				type: "crow",
				icon: "?",
			}, {
				label: "reCaptcha",
				type: "recaptcha",
				icon: "<i class=\"fa fa-google\"></i>",
			}, {
				label: "Autocomplete Address",
				type: "autocomplete-address",
				icon: "<i class=\"fa fa-map-marker\"></i>",
			}],
			typeUserAttrs: {
				paragraph: {
					subtype: {
						label: 'Wrapper',
						options: {
							'div': 'div',
							'p': 'p',
							'label': 'label',
							'h1': 'h1',
							'h2': 'h2',
							'h3': 'h3',
							'h4': 'h4',
							'h5': 'h5',
							'h6': 'h6',
						},
					},
				},
				email: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				tel: {
					className: {
						label: 'Class',
						value: 'form-control aumobile',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				text: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
					inputMask: {
						label: 'Input mask',
						value: '',
					},
				},
				textarea: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				number: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
				},
				'checkbox-group': {
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				'radio-group': {
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				date: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					datePicker: {
						label: 'Datepicker',
						options: {
							'false': 'No',
							'true': 'Yes'
						}
					},
					startDate: {
						label: 'Start Date',
						value: '',
						placeholder: 'DD-MM-YYYY',
					},
					endDate: {
						label: 'End Date',
						value: '',
						placeholder: 'DD-MM-YYYY',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				select: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				file: {
					accept: {
						label: 'Accept',
						value: '',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				'autocomplete-address': {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					addressFinder: {
						label: 'AddressFinder',
						options: {
							'AU': 'AU',
							'NZ': 'NZ',
						}
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
					label_street_1: {
						label: 'Address Label 1',
						value: '',
					},
					placeholder_street_1: {
						label: 'Address Placeholder 1',
						value: '',
					},
					label_street_2: {
						label: 'Address Label 2',
						value: '',
					},
					placeholder_street_2: {
						label: 'Address Placeholder 2',
						value: '',
					},
					label_suburb: {
						label: 'Suburb Label',
						value: '',
					},
					placeholder_suburb: {
						label: 'Suburb Placeholder',
						value: '',
					},
					label_state: {
						label: 'State Label',
						value: '',
					},
					placeholder_state: {
						label: 'State Placeholder',
						value: '',
					},
					label_postcode: {
						label: 'Postcode Label',
						value: '',
					},
					placeholder_postcode: {
						label: 'Postcode Placeholder',
						value: '',
					},
				},
				crow: {
					placeholder: {},
					description: {},
					className: {},
					label: {},
					name: {},
					value: {},
					conditions: {
						label: 'Conditions',
						value: '',
						help: 'xxx',
					}
				},
			},
			replaceFields: [{
				type: "text",
				label: "Textfield",
				icon: "<i class=\"fa fa-font\"></i>",
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Textarea',
				type: 'textarea',
				icon: '<i class="fa fa-edit"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				type: 'number',
				label: 'Number',
				icon: '<i class="fa fa-hashtag"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Checkbox group',
				type: 'checkbox-group',
				icon: '<i class="fa fa-check-square-o"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Radio group',
				type: 'radio-group',
				icon: '<i class="fa fa-dot-circle-o"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Date',
				type: 'date',
				icon: '<i class="fa fa-calendar"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'File',
				type: 'file',
				icon: '<i class="fa fa-file-o"></i>',
				accept: '',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Selectbox',
				type: 'select',
				icon: '<i class="fa fa-list-alt"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Markup',
				type: 'paragraph',
				icon: '<i class="fa fa-paragraph"></i>',
			}],
			templates: {
				email: function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="'+fieldData.type+'" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				},
				tel: function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="'+fieldData.type+'" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				},
				row: function(fieldData){
					return {
						field: '<div class="col-xs-12">&nbsp;</div>',
						layout: 'noLabel',
						onRender: function(){
							$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<p class="text-muted text-center no-margin"><small>–––––– Row Break ––––––</small></p>');
							$('.field-'+this.id).closest('.form-field').find('>.prev-holder').remove();
						}
					}
				},
				crow: function(fieldData){
					return {
						field: '<div class="col-xs-12">&nbsp;</div>',
						layout: 'noLabel',
						onRender: function(){
							$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<p class="text-muted text-center no-margin"><small>–––––– Conditional Row ––––––</small></p>');
							$('.field-'+this.id).closest('.form-field').find('>.frm-holder>.form-elements>.form-group:not(.conditions-wrap):not(.required-wrap)').remove();
						}
					}
				},
				recaptcha: function(fieldData){
					return {
						field: '<div class="g-recaptcha"></div>',
						layout: 'noLabel',
						onRender: function(){
							if( $('.field-'+this.id).closest('.frmb.stage-wrap').find('li[type="recaptcha"]').length > 1 ) {
								$('.field-'+this.id).closest('.form-field').remove();
								alert('Only one reCaptcha is allowed.');
							} else {
								$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<img src="/assets/images/recaptcha_pl.png" alt="reCaptcha" />');
								$('.field-'+this.id).closest('.form-field').find('>.prev-holder').remove();
							}
						}
					}
				},
				'autocomplete-address': function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="text" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				}
			},
			defaultFields: defaultFields,
			inputSets: [{
				label: "First name / Last name",
				icon: "<i class=\"fa fa-user-o\"></i>",
				name: "user-details",
				showHeader: false,
				fields: [{
                    type: "text",
                    label: "First name",
                    className: "form-control",
                    name: "first_name",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "text",
                    label: "Last name",
                    className: "form-control",
                    name: "last_name",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			},
			{
				label: "Email / Confirm Email",
				icon: "<i class=\"fa fa-envelope-o\"></i>",
				name: "email-confirm",
				showHeader: false,
				fields: [{
                    type: "email",
                    label: "Email Address",
                    className: "form-control",
                    name: "emai",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "email",
                    label: "Confirm Email Address",
                    className: "form-control",
                    name: "email_confirm",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			},
			{
				label: "Address / Suburb / State / Postcode",
				icon: "<i class=\"fa fa-map-marker\"></i>",
				name: "address-fields",
				showHeader: false,
				fields: [{
                    type: "text",
                    label: "Street address line 1",
                    className: "form-control",
                    name: "address_line_1",
                    required: true,
                    wrapperColumns: 'col-xs-12 small-12'
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "text",
                    label: "Street address line 2",
                    className: "form-control",
                    name: "address_line_2",
                    required: false,
                    wrapperColumns: 'col-xs-12 small-12'
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "text",
                    label: "Suburb",
                    className: "form-control",
                    name: "address_suburb",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "select",
                    label: "State",
                    className: "form-control",
                    name: "address_state",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6',
                    "values": [
				    {
						value: "NSW",
						label: "New South Wales",
					},
					{
						value: "QLD",
						label: "Queensland",
					},
					{
						value: "SA",
						label: "South Australia",
					},
					{
						value: "TAS",
						label: "Tasmania",
					},
					{
						value: "VIC",
						label: "Victoria",
					},
					{
						value: "WA",
						label: "Western Australia",
					},
					{
						value: "ACT",
						label: "Australian Capital Territory",
					},
					{
						value: "NT",
						label: "Northern Territory",
					}]
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "number",
                    label: "Postcode",
                    className: "form-control",
                    name: "address_postcode",
                    max: "9999",
                    min: "0",
                    step: "1",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			}],
			controlOrder: [
				'text',
				'tel',
				'email',
				'number',
				'textarea',
				'select',
				'date',
				'file',
				'checkbox-group',
				'radio-group',
				'user-details',
				'autocomplete-address',
		    ],
		    layoutTemplates: {
		    	default: function(field, label, help, fieldData){
		    		setTimeout(function(){
						$(field).closest('.form-field')
							.removeClass('col-xs-12')
							.removeClass('col-sm-6')
							.removeClass('col-sm-4')
							.removeClass('col-sm-3')
							.addClass(fieldData.wrapperColumns);
					}, 100 );
		    		return field;
		    	}
		    },
		    onAddField: function(id,fieldData){
		    	$(window).trigger('updateFBFields', [ '#'+id, fieldData.wrapperColumns ] );
		    },
		    typeUserEvents: {
		    	text: {
		    		onadd: function(field){
		    			$('.fld-wrapperColumns', field).on('change',function(){
		    				$(window).trigger('updateFBFields', [ field, $(this).val() ] );
		    			});
		    		}
		    	}
		    }
		});

		$(window).on('updateFBFields',function(e,field,wrapperColumns){
			setTimeout(function(){
				$(field)
					.removeClass('col-xs-12')
					.removeClass('col-sm-6')
					.removeClass('col-sm-4')
					.removeClass('col-sm-3')
					.addClass(wrapperColumns);
			}, 100 );
		});


		$('[data-toggle="tab"][href="#form"]').on('click',function(){
			$( builderElem ).toggle().show();
		});

		$('#campaign_form').on('submit',function(e){
			if( !$(this).data('valid') ) {
				e.preventDefault();

				if(builderElem) {
					var data = JSON.stringify(JSON.parse(formBuilder.actions.getData('json', true)));
					$('#form_content').val(data).trigger('change');
				}

				$('#form_builder').find('[name]').attr('disabled',true);

				$(this).data('valid',true);
				$(this).submit();
			}
		});
	}


	var VenueEditButtons = function() {
		$('#modal-tracking .modal-confirm').on('click',function(e){

			var form = $('#modal-tracking form');
			var valid = true;

			$(form).find('select').each(function(){
				$(this).closest('.form-group').removeClass('has-error');
				if(!$(this).val()) {
					$(this).closest('.form-group').addClass('has-error');
					valid = false;
				}
			});
		    if( valid ) {
		    	form.submit();
		    } else {
		    	return false;
		    }
		});
	}


	var InitTooltips = function() {
		$('[data-toggle="tooltip"]').tooltip({
			container: 'body',
		});

		$(document).ready(function(){
		    $('[data-toggle="popover"]').popover();
		});

		$('[data-toggle="popover"]').on('click', function(e) {
			e.preventDefault();
		});
	}


	var ActionDeleteButton = function() {
		$(document).on('click','.delete-button',function(e){
			e.preventDefault();
			var href = {
				url: $(this).attr('href'),
				redirect: $(this).attr('data-redirect')
			};
			$('#modal-delete').data('href',href).modal('show');

		});

		$('#modal-delete .modal-confirm').on('click',function(e){

			var href = $('#modal-delete').data('href');

			var form = $('<form>').attr({
					'method' : 'POST',
					'action' : href.url,
				}),
				token = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_token',
					'value' : csrf_token,
				}),
				method = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_method',
					'value' : 'DELETE',
				})


			form.append( token ).append( method );

			form.appendTo('body').submit();
		});
	}

	var ViewSubmissionButton = function() {

		function buildHtmlTable(myList, selector) {
		  $(selector).html('');

		  var columns = addAllColumnHeaders(myList, selector);

		  for (var i = 0; i < myList.length; i++) {
		    var row$ = $('<tr/>');
		    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
		      var cellValue = myList[i][columns[colIndex]];
		      if (cellValue == null) cellValue = "";
		      row$.append($('<td/>').html(cellValue));
		    }
		    $(selector).append(row$);
		  }

		  $(selector).find('>tr:first-child').wrap('<thead/>');
		  $(selector).find('>tr').wrapAll('<tbody/>');
		}

		function addAllColumnHeaders(myList, selector) {
		  var columnSet = [];
		  var headerTr$ = $('<tr/>');

		  for (var i = 0; i < myList.length; i++) {
		    var rowHash = myList[i];
		    for (var key in rowHash) {
		      if ($.inArray(key, columnSet) == -1) {
		        columnSet.push(key);
		        headerTr$.append($('<th/>').html(key));
		      }
		    }
		  }
		  $(selector).append(headerTr$);

		  return columnSet;
		}


		$('.view-submission').on('click',function(e){
			e.preventDefault();
			var href = $(this).attr('href');

			$.ajax({
				url: href,
				type: 'GET',
				data: {
					_token: csrf_token,
				},
				success: function(data, status, xhr){
					var data = JSON.parse(data);

					buildHtmlTable([data], '#table-view-submission');

					$('#modal-view').modal('show');
				}
			});

			// $('#modal-delete').data('href',href).modal('show');

		});
	};


	var UpdateSubmissionButtons = function() {
		$('#modal-reject .modal-confirm').on('click',function(e){

			if(!$('#modal-reject textarea').val()) {
				alert('Comment is required');
				return;
			}

			var href = $('#modal-reject').data('href');

			var form = $('<form>').attr({
					'method' : 'POST',
					'action' : href.url,
				}),
				token = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_token',
					'value' : csrf_token,
				}),
				method = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_method',
					'value' : 'PUT',
				}),
				id = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'id',
					'value' : href.id,
				}),
				status = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'status',
					'value' : '3',
				}),
				comment = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'comment',
					'value' : $('#modal-reject textarea').val(),
				}),
				hash = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_hash',
					'value' : window.location.hash,
				})


			form.append( token ).append( method ).append( id ).append( status ).append( comment ).append( hash );

			form.appendTo('body').submit();
		});
	}


	var DownloadSubmissions = function() {
		$('.download-submissions').on('click',function(e){
			e.preventDefault();
			var params = [];
			var table = $( $(this).attr('data-table') );


			$(this).closest('.filters').find(':input[data-col]').each(function(){
				var i = $(this).attr('data-col');
				var name = table.find('thead th').eq(i).attr('data-name');
				var a = $(this).serializeArray();

				if(a.length) {
					params.push( name + '=' + encodeURIComponent( a[0].value ) );
				}
			});
			window.location.href = $(this).attr('href')+'?'+params.join('&');
		});
	}


	BulkTrackingCodes = function() {
		$('#modal-tracking').modal('show');
	}

	UploadWinners = function() {
		$('#modal-winners-upload').modal('show');
	}

	// SubmitModal = function(el) {
	// 	$(el).closest('.modal').find('form .has-error').removeClass('has-error');
	// 	if( $(el).closest('.modal').find('form').valid() ) {
	// 		$(el).closest('.modal').find('form').submit();
	// 		// console.log( $(el).closest('.modal').find('form').serializeArray() );
	// 	} else {
	// 		$(el).closest('.modal').find('form .error').closest('.form-group').addClass('has-error');
	// 	}
	// }

	BulkDeliveryCSV = function(el) {
		$('#modal-delivery-upload').modal('show');
	}

	ModalMapCSV = function(input) {
		var modal = $(input).closest('.modal');

		if (input.files && input.files[0]) {
		    // Get our CSV file from upload
		    var file = input.files[0];

		    // Instantiate a new FileReader
		    var reader = new FileReader();

		    // Read our file to an ArrayBuffer
		    reader.readAsArrayBuffer(file);

		    // Handler for onloadend event.  Triggered each time the reading operation is completed (success or failure) 
		    reader.onloadend = function (evt) {
		        // Get the Array Buffer
		        var data = evt.target.result;

		        // Grab our byte length
		        var byteLength = data.byteLength;

		        // Convert to conventional array, so we can iterate though it
		        var ui8a = new Uint8Array(data, 0);

		        // Used to store each character that makes up CSV header
		        var headerString = '';

		        // Iterate through each character in our Array
		        for (var i = 0; i < byteLength; i++) {
		            // Get the character for the current iteration
		            var char = String.fromCharCode(ui8a[i]);

		            // Check if the char is a new line
		            if (char.match(/[^\r\n]+/g) !== null) {

		                // Not a new line so lets append it to our header string and keep processing
		                headerString += char;
		            } else {
		                // We found a new line character, stop processing
		                break;
		            }
		        }

		        // Split our header string into an array
		        var headers = headerString.split(',');

		        $(modal).find('select').each(function(e){
			    	var _select = $(this);
			    	_select.html('<option value="">- Select -</option>');
			    	$.each(headers,function(i,e){
			    		e = e.replace(/\"/g,'');
			    		var selected = _select.attr('name').endsWith(e);
			    		_select.append('<option value="'+e+'" '+(selected?'selected':'')+'>'+e+'</option>');
			    	});
			    });
		    };
		}
	}

	BulkSubmissionApprove = function(el){
		var ids;
		var href = {
			url: $(el).attr('data-href'),
		};

		if( $(el).attr('data-approve') == 'all' ) {
			ids = 'all';
		} else {
			ids = $( $(el).attr('data-table') ).find('[name="bulkcheck"]:checked').map(function(){
				return $(this).val();
			}).get();
		}

		if(!ids || !ids.length) return;

		var form = $('<form>').attr({
				'method' : 'POST',
				'action' : href.url,
			}),
			token = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_token',
				'value' : csrf_token,
			}),
			method = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_method',
				'value' : 'PUT',
			}),
			ids = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'ids',
				'value' : ids
			}),
			status = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'status',
				'value' : '2',
			}),
			comment = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'comment',
				'value' : '',
			}),
			hash = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_hash',
				'value' : window.location.hash,
			});

		form.append( token ).append( method ).append( ids ).append( status ).append( comment ).append( hash );

		form.appendTo('body').submit();
	}

	SubmissionApprove = function(el){
		var href = {
			url: $(el).attr('data-href'),
		};

		var id = $(el).attr('data-id');

		if(!id) return;

		var form = $('<form>').attr({
				'method' : 'POST',
				'action' : href.url,
			}),
			token = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_token',
				'value' : csrf_token,
			}),
			method = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_method',
				'value' : 'PUT',
			}),
			ids = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'ids',
				'value' : id
			}),
			status = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'status',
				'value' : '2',
			}),
			comment = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'comment',
				'value' : '',
			}),
			hash = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_hash',
				'value' : window.location.hash,
			});

		form.append( token ).append( method ).append( ids ).append( status ).append( comment ).append( hash );

		form.appendTo('body').submit();
	}

	// SubmissionReject = function(el){
	// 	var href = {
	// 		url: $(el).attr('data-href'),
	// 		id: $(el).attr('data-id'),
	// 	};

	// 	$('#modal-reject textarea').val('');

	// 	$('#modal-reject').data('href',href).modal('show');
	// }
	BulkSubmissionReject = function(el){
		var ids,s;

		if( $(el).attr('data-approve') == 'all' ) {
			ids = 'all';
			s = $(el).attr('data-status')||'1';
		} else {
			ids = $( $(el).attr('data-table') ).find('[name="bulkcheck"]:checked').map(function(){
				return $(this).val();
			}).get();
		}

		if(!ids || !ids.length) return;

		$(el).data('id',ids);
		$(el).data('s',s);

		SubmissionReject(el);
	}

	SubmissionReject = function(el){
		$('#modal-reject textarea').val('');
		$('#modal-reject [name="ids"]').val( $(el).data('id') );
		$('#modal-reject [name="status"]').val( $(el).data('s') );

		$(el).data({
			modal: '#modal-reject'
		});

		openModal(el);
	}

	openModal = function(el){
		var data = $(el).data();
		$(data.modal).data(data).modal('show');
	}

	submitModal = function(el){

		var form = $(el).closest('.modal').find('form');
		var valid = true;

		$(form).find(':input[required]').each(function(){
			$(this).closest('.form-group').removeClass('has-error');
			if(!$(this).val()) {
				$(this).closest('.form-group').addClass('has-error');
				valid = false;
			}
		});
	    if( valid ) {
	    	form.submit();
	    } else {
	    	return false;
	    }

	}

	openOCRModal = function(el){
		var data = $(el).data();
		$('#modal-ocr pre.ocr-data').text(data.ocr);
		$('#modal-ocr').modal('show');
	}



	// Load Functions
	AjaxTokenSetup();
	setupSidebar();
	DataTables();
	DatePickers();
	InitCKEditor();
	UploadFile();
	CampaignFormBuilder();
	VenueEditButtons();
	InitTooltips();
	ActionDeleteButton();
	ViewSubmissionButton();
	// UpdateSubmissionButtons();
	// BulkSubmissionApprove();

	DownloadSubmissions();


})(jQuery);