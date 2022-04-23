const registrationHandler = {
  $container: $j(".bgImg"),
  vatCodeController: function () {
    $j("[id$='_vatCode']").on("blur", function () {
      $j(this).toggleClass("loadingInput");
      helper.anafCall($j(this));
    });
  },
};

$j(document).on("_page_ready _page_update", function () {
  registrationHandler.vatCodeController();
});
