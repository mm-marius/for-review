const registrationHandler = {
  $container: $j(".bgImg"),
  vatCodeController: function () {
    $j("[id$='_vatCode']").on("blur", function () {
      helper.anafCall($j(this));
    });
  },
  initImage: function () {
    const imgUrl = this.$container.data("image");
    $j("body").css({
      background: "url('" + imgUrl + "')  no-repeat center center fixed",
      "background-size": "cover",
    });
  },
};

$j(document).on("_page_ready _page_update", function () {
  registrationHandler.vatCodeController();
  registrationHandler.initImage();
  // if ($j(".resultMessagesContainer").length > 0) {
  //   helper.inputErrorToggle(loginHandler.$email);
  //   helper.inputErrorToggle(loginHandler.$password);
  // }
});
