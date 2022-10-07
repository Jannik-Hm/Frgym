$('.drop-menu ul').hide();
$(".drop-menu a").click(function () {
  if($(this).parent('.drop-menu').children("ul").css("display") == "none"){var slidedown = true;}else{var slidedown = false;}
  $(this).parent("li.drop-menu").parent("ul").children("li.drop-menu").children("ul").slideUp("200");
  $(this).parent("li.drop-menu").parent("ul").children("li.drop-menu").find("i.fa").attr("class", "fa fa-angle-down");
  if(slidedown){
    $(this).parent(".drop-menu").children("ul").slideDown("200");
    $(this).find("i.fa").attr("class", "fa fa-angle-up");
  }
});