const loginHandler = {
  $email: $j("#login_form_email"),
  $password: $j("#login_form_password"),
};
$j(document).on("_page_ready _page_update", function () {
  if ($j(".resultMessagesContainer").length > 0) {
    helper.inputErrorToggle(loginHandler.$email);
    helper.inputErrorToggle(loginHandler.$password);
  }
});
