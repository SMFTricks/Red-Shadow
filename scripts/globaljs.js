$(function() {
 $('#dropdown_nav li, .useritem').find('.sub_nav, .useritem ul').hide();
 $('#dropdown_nav li, .useritem').hover(function() {
  $(this).find('.sub_nav, .useritem ul').fadeIn(100);
 }, function() {
  $(this).find('.sub_nav, .useritem ul').fadeOut(50);
 });
});

$(function() {
    var button = $('#loginButton');
    var box = $('#loginBox');
    var form = $('#loginForm');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#loginButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
$(function() {
    var button = $('#searchButton');
    var box = $('#searchBox');
    var form = $('#searchForm');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#searchButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
$(document).ready(function() {
	$(".tab_content").hide(); 
	$("ul.tabs li:eq(0)").addClass("active").show(); 
	$(".tab_content:eq(0)").show(); 
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); 
		$(this).addClass("active");
		$(".tab_content").hide(); 
		var activeTab = $(this).find("a").attr("href"); 
		$(activeTab).fadeIn(); 
		return false;
	});
});
$(document).ready(function () {
  $("#logo ,.facebook ,.twitter ,.youtube,.rss").fadeTo(0.5, 1);
  $("#logo ,.facebook ,.twitter ,.youtube,.rss").hover(
    function () {
      $(this).fadeTo("slow", 0.5);
    },
    function () {
      $(this).fadeTo("slow", 1);
    }
  );
});