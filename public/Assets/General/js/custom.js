/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split("#")[0].split("?")[0],
  $BODY = $j("body"),
  $MENU_TOGGLE = $j("#menu_toggle"),
  $SIDEBAR_MENU = $j("#sidebar-menu"),
  $SIDEBAR_FOOTER = $j(".sidebar-footer"),
  $LEFT_COL = $j(".left_col"),
  $RIGHT_COL = $j(".right_col"),
  $NAV_MENU = $j(".nav_menu"),
  $FOOTER = $j("footer");

// Panel toolbox
$j(document).ready(function () {
  $j(".collapse-link").on("click", function () {
    var $BOX_PANEL = $j(this).closest(".x_panel"),
      $ICON = $j(this).find("i"),
      $BOX_CONTENT = $BOX_PANEL.find(".x_content");

    // fix for some div with hardcoded fix class
    if ($BOX_PANEL.attr("style")) {
      $BOX_CONTENT.slideToggle(200, function () {
        $BOX_PANEL.removeAttr("style");
      });
    } else {
      $BOX_CONTENT.slideToggle(200);
      $BOX_PANEL.css("height", "auto");
    }

    $ICON.toggleClass("fa-chevron-up fa-chevron-down");
  });

  $j(".close-link").click(function () {
    var $BOX_PANEL = $j(this).closest(".x_panel");

    $BOX_PANEL.remove();
  });
});
// /Panel toolbox

// Tooltip
$j(document).ready(function () {
  $j('[data-toggle="tooltip"]').tooltip({
    container: "body",
  });
});
// /Tooltip

// Progressbar
$j(document).ready(function () {
  if ($j(".progress .progress-bar")[0]) {
    $j(".progress .progress-bar").progressbar();
  }
});
// /Progressbar

// Switchery
$j(document).ready(function () {
  if ($j(".js-switch")[0]) {
    var elems = Array.prototype.slice.call(
      document.querySelectorAll(".js-switch")
    );
    elems.forEach(function (html) {
      var switchery = new Switchery(html, {
        color: "#26B99A",
      });
    });
  }
});
// /Switchery

// iCheck
$j(document).ready(function () {
  if ($j("input.flat")[0]) {
    $j(document).ready(function () {
      $j("input.flat").iCheck({
        checkboxClass: "icheckbox_flat-green",
        radioClass: "iradio_flat-green",
      });
    });
  }
});
// /iCheck

// Table
$j("table input").on("ifChecked", function () {
  checkState = "";
  $j(this).parent().parent().parent().addClass("selected");
  countChecked();
});
$j("table input").on("ifUnchecked", function () {
  checkState = "";
  $j(this).parent().parent().parent().removeClass("selected");
  countChecked();
});

var checkState = "";

$j(".bulk_action input").on("ifChecked", function () {
  checkState = "";
  $j(this).parent().parent().parent().addClass("selected");
  countChecked();
});
$j(".bulk_action input").on("ifUnchecked", function () {
  checkState = "";
  $j(this).parent().parent().parent().removeClass("selected");
  countChecked();
});
$j(".bulk_action input#check-all").on("ifChecked", function () {
  checkState = "all";
  countChecked();
});
$j(".bulk_action input#check-all").on("ifUnchecked", function () {
  checkState = "none";
  countChecked();
});

function countChecked() {
  if (checkState === "all") {
    $j(".bulk_action input[name='table_records']").iCheck("check");
  }
  if (checkState === "none") {
    $j(".bulk_action input[name='table_records']").iCheck("uncheck");
  }

  var checkCount = $j(
    ".bulk_action input[name='table_records']:checked"
  ).length;

  if (checkCount) {
    $j(".column-title").hide();
    $j(".bulk-actions").show();
    $j(".action-cnt").html(checkCount + " Records Selected");
  } else {
    $j(".column-title").show();
    $j(".bulk-actions").hide();
  }
}

// Accordion
$j(document).ready(function () {
  $j(".expand").on("click", function () {
    $j(this).next().slideToggle(200);
    $expand = $j(this).find(">:first-child");

    if ($expand.text() == "+") {
      $expand.text("-");
    } else {
      $expand.text("+");
    }
  });
});
