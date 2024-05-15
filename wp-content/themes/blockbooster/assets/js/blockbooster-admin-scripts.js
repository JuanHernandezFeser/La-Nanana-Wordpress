(function ($) {
  "use strict";
  $("#blockbooster-dismiss-notice").on("click", ".notice-dismiss", function () {
    $.ajax({
      url: ajaxurl,
      data: {
        action: "blockbooster_dismissble_notice",
      },
    });
  });
  $("#blockbooster-dashboard-tabs-nav li:first-child").addClass("active");
  $(".tab-content").hide();
  $(".tab-content:first").show();
  $("#blockbooster-dashboard-tabs-nav li").click(function () {
    $("#blockbooster-dashboard-tabs-nav li").removeClass("active");
    $(this).addClass("active");
    $(".tab-content").hide();
    var activeTab = $(this).find("a").attr("href");
    $(activeTab).fadeIn();
    return false;
  });
})(jQuery);
