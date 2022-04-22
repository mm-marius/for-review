$j(document).on("_page_ready _page_update", function () {
  helper.initHelper();
  //   modalAppender();
  // initPills();
  // initLoader();
  // initLazy();
});
const helper = {
  tooltipInit: function () {
    $j(".services_element,.tooltipSeller").tooltip({
      show: { effect: "none", delay: 0 },
      classes: {
        "ui-tooltip": "highlight",
      },
    });
  },
  initHelper: function () {
    helper.tooltipInit();
    $j("#login_form__remember_me_box").attr("name", "login_form[_remember_me]");

    const $target = $j(
      ".seller-form-group input,.seller-form-group select,.seller-form-group textarea"
    ).not(".SAHelperSync");
    const $targetNumber = $j('.seller-form-group input[type="number"]').not(
      ".SAHelperSync"
    );
    $targetNumber.on("keydown", function (e) {
      var current = $j(this).val();
      var allowed = [8, 9, 13, 37, 38, 39, 40, 46, 110, 188, 190];
      if (
        !(
          allowed.includes(e.which) ||
          (e.which >= 48 && e.which <= 57) ||
          (e.which >= 96 && e.which <= 105)
        )
      ) {
        e.preventDefault();
        return false;
      }

      if (e.which == 110 || e.which == 188 || e.which == 190) {
        if (!current.includes(".")) {
          $j(this).val(current + ".");
        }
        e.preventDefault();
        return false;
      }
    });
    $targetNumber.on("keyup", function (e) {
      var current = $j(this).val();
      var decimal = parseInt($j(this).attr("data-decimal"));
      if (
        !isNaN(decimal) &&
        current.includes(".") &&
        ((e.which >= 48 && e.which <= 57) || (e.which >= 96 && e.which <= 105))
      ) {
        var multiplier = Math.pow(10, decimal);
        $j(this).val(parseInt(current * multiplier) / multiplier);
      }
    });

    $target.on("focus", function () {
      $j(this).parents(".group").addClass("focused");
    });
    $target.on("change", function () {
      helper.formFocusClass($j(this));
    });
    $target.on("blur", function () {
      helper.formFocusClass($j(this));
    });
    $targetNumber.addClass("SAHelperSync");
    $target.addClass("SAHelperSync");
    helper.clearLabelsForm();
    // if (typeof websalePrivacyHandler != "undefined")
    //   websalePrivacyHandler.init();
  },
  formFocusClass: ($item) => {
    var $parent = $item.parents(".group");
    if ($item.val() === "") {
      $parent.removeClass("filled");
      $parent.removeClass("focused");
    } else {
      $parent.addClass("filled");
    }
  },
  showLoader: function (show) {
    if (show) {
      $j(".loadingData,.loadingDataFull").addClass("active");
    } else {
      $j(".loadingData,.loadingDataFull").removeClass("active");
    }
  },
  clearLabelsForm: function () {
    const $target = $j(
      ".seller-form-group input,.seller-form-group select,.seller-form-group textarea"
    );
    $target.each(function () {
      helper.formFocusClass($j(this));
    });
  },
  inputErrorToggle: function ($elem) {
    const $parent = $elem.parent();
    $parent.toggleClass("errorInput");
  },
  anafCall: function (elem) {
    let vatCode = elem.val();
    let url = anafApiRoute;
    $j.post(url, { vatCode }, function (result) {
      if (result.success && result.errorMsg == "") {
        $j.each(fieldsHelper.user(), function (k, v) {
          let $elem = $j("[id$='_" + v + "']");
          if ($elem.length) {
            $elem.val(eval("result.content." + v));
            $elem.closest(".group").addClass("filled");
          }
        });
        helper.errorInput("[id$='_vatCode']", result.errorMsg);
        helper.errorInput("[id$='_businessName']", result.errorMsg);
        helper.errorInput("[id$='_phone']", result.errorMsg);
      } else {
        helper.errorInput("[id$='_vatCode']", result.errorMsg);
      }
    });
  },
  errorInput: function (elem, message = "") {
    var $parent = $j(elem).parent();
    var $ul = $parent.find("ul");
    if ($ul.length == 0) {
      $ul = $j("<ul></ul>");
      $parent.append($ul);
    }
    if (message) {
      $parent.addClass("errorInput");
      $ul.html("<li>" + message + "</li>");
    } else {
      $parent.removeClass("errorInput");
      $ul.html("");
    }
  },
};

// function setMessageAnimation() {
//   $j(".messageBox").each(function (index) {
//     var $target = $j(this);
//     var time = 500 * index;
//     setTimeout(function () {
//       $target.addClass("msg-in");
//       setTimeout(function () {
//         if (!$target.hasClass("msg-out")) {
//           $target.addClass("msg-out");
//         }
//       }, 4000);
//     }, time);
//   });
// }
// function removeMessage(item) {
//   target = $j(item).parent();
//   target.addClass("msg-out");
// }

// function modalAppender() {
//   $j(".sellerModal").appendTo("body");
// }
// function initLoader() {
//   $j("#hotel_form_submit, #package_form_submit").click(function () {
//     showLoader(true);
//   });
// }

// function getParentAtLevel($element, cycleNumber) {
//   for (var i = 0; i < cycleNumber; i++) {
//     $element = $element.parent();
//   }
//   return $element;
// }

// var websaleStringHelper = {
//   randomSource:
//     "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",
//   capitalize: function (inputString) {
//     var ret = inputString.toLowerCase();
//     const words = ret.split(" ");
//     var capitalizedWords = [];
//     for (var i = 0; i < words.length; i++) {
//       capitalizedWords.push(this.ucFirst(words[i]));
//     }
//     return capitalizedWords.join(" ");
//   },
//   ucFirst: function (inputString) {
//     var ret = inputString.toLowerCase();
//     return ret.charAt(0).toUpperCase() + inputString.slice(1);
//   },
//   generateRandomString(size) {
//     var result = "";
//     var sourceLength = this.randomSource.length;
//     for (var i = 0; i < size; i++) {
//       result += this.randomSource.charAt(
//         Math.floor(Math.random() * sourceLength)
//       );
//     }
//     return result;
//   },
// };

// function convertPriceNumber(inputNumber) {
//   return inputNumber.toFixed(2).toString().replace(".", ",");
// }

// var websaleScrollHelper = {
//   scrollInDiv: function ($scrollableDiv, $scrollTo, animate = true) {
//     var scrollTo =
//       $scrollTo.offset().top +
//       $scrollableDiv.scrollTop() -
//       $scrollableDiv.offset().top;
//     if (animate)
//       $scrollableDiv.animate({
//         scrollTop: scrollTo,
//       });
//     else $scrollableDiv.scrollTop(scrollTo);
//   },
//   scrollInDivHorizontal: function (
//     $scrollableDiv,
//     $scrollTo,
//     animate = true,
//     center = false
//   ) {
//     var scrollTo =
//       $scrollTo.offset().left +
//       $scrollableDiv.scrollLeft() -
//       $scrollableDiv.offset().left;
//     if (center) scrollTo -= $scrollableDiv.width() / 2;
//     if (animate)
//       $scrollableDiv.animate({
//         scrollLeft: scrollTo,
//       });
//     else $scrollableDiv.scrollLeft(scrollTo);
//   },
// };

// var websaleDelayHelper = {
//   delayArray: {},
//   applyDelay: function (exec, delayKey, timeMillis) {
//     //It will stop the function executing multiple times before timeout
//     var myself = this;
//     //var delayValue = websaleStringHelper.generateRandomString(8);
//     if (this.delayArray.hasOwnProperty(delayKey))
//       clearTimeout(this.delayArray[delayKey]);
//     this.delayArray[delayKey] = setTimeout(function () {
//       exec();
//     }, timeMillis);
//   },
//   interruptExecution: function (delayKey) {
//     if (this.delayArray.hasOwnProperty(delayKey))
//       clearTimeout(this.delayArray[delayKey]);
//   },
// };

// var websaleSemaphoreHelper = {
//   SEMAPHORE_NONE: "semaphore-none",
//   STATUSES: {
//     V: "semaphore-green",
//     G: "semaphore-yellow",
//     R: "semaphore-red",
//   },
//   updateSemaphore: function ($semaphore, value) {
//     if (!value) {
//       $semaphore.addClass(this.SEMAPHORE_NONE);
//       return;
//     }
//     $semaphore.removeClass(this.SEMAPHORE_NONE);
//     for (var [status, className] of Object.entries(this.STATUSES)) {
//       if (status == value) {
//         if ($semaphore.data("ui-tooltip")) {
//           try {
//             $semaphore.tooltip("dispose");
//           } catch (e) {
//             $semaphore.tooltip("destroy");
//           }
//         }
//         $semaphore.addClass(className);
//         var statusTitle = $semaphore.attr(
//           "data-" + status.toLowerCase() + "-title"
//         );
//         //data-original-title is for engine, title is for plugin. In some Bootstrap versions you need to set data-title.
//         $semaphore.attr("data-original-title", statusTitle);
//         $semaphore.attr("data-title", statusTitle);
//         $semaphore.attr("title", statusTitle);
//         try {
//           $semaphore.tooltip({
//             show: { effect: "none", delay: 0 },
//             classes: {
//               "ui-tooltip": "highlight",
//             },
//           });
//         } catch (error) {
//           //Avoids loading loop in case of missing tooltip
//           console.error(error);
//         }
//       } else $semaphore.removeClass(className);
//     }
//   },
// };

var websaleLoader = {
  //TODO this is going to replace the current showLoader and maybe should be moved to other file
  $loaders: null,
  progressBarMax: 0,
  currentState: 0,
  init: function (progressBarMax = 0) {
    this.$loaders = $j(".loadingData, .loadingDataFull");
    this.progressBarMax = progressBarMax;
  },
  show: function () {
    this.changeState(true);
  },
  hide: function () {
    this.changeState(false);
  },
  changeState: function (show) {
    if (show) this.$loaders.addClass("active");
    else this.$loaders.removeClass("active");
  },
  addProgress(progression) {
    this.currentState += progression;
    this.changePercentage();
  },
  changeProgressBarState(progression) {
    this.currentState = progression;
    this.changePercentage();
  },
  changePercentage: function () {
    var percentage = ((100 / this.progressBarMax) * this.currentState).toFixed(
      2
    );
    this.$loaders
      .find(".progressBar .loaded")
      .css("width", percentage.toString() + "%");
  },
  getCurrentProgressBarState: function () {
    return this.currentState;
  },
  setTitle: function (title) {
    this.$loaders.find(".loadTitle").html(title);
  },
};
function initPills() {
  $j(".nav-pills > a,.webSalesPlugin .nav-tabs a").click(function () {
    var $element = $j(this);
    context = $element.closest(
      ".tabServicesContainer,.websale-form-tabs,.websale-tabs"
    );
    context.find(">.tab-content>.tab-pane").each(function () {
      $j(this).removeClass("active show in");
    });
    if (!$element.parent().parent().hasClass("websale-form-tab")) {
      $j($element.attr("href")).addClass("active show in");
    }
  });
}
