const loginHandler = {
  $email: $j("#login_form_email"),
  $password: $j("#login_form_password"),
  $container: $j(".bgImg"),
  initImage: function () {
    const imgUrl = this.$container.data("image");
    $j("body").css({
      background: "url('" + imgUrl + "')  no-repeat center center fixed",
      "background-size": "cover",
    });
  },
};
$j(document).on("_page_ready _page_update", function () {
  if ($j(".resultMessagesContainer").length > 0) {
    helper.inputErrorToggle(loginHandler.$email);
    helper.inputErrorToggle(loginHandler.$password);
  }
  loginHandler.initImage();
});
