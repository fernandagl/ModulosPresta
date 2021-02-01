$(document).ready(function(){
	if(!isMobile()){

			$(".variant-links a").hover(function(){
				  $(this).parents('.thumbnail-container').find('.product-container-img').height($(this).parents('.thumbnail-container').find('.product-container-img').height());
				  $(this).parents('.thumbnail-container').find('.product-container-img a img').css("display","none");
				  $(this).parents('.thumbnail-container').find('.product-container-img a').append("<img class=\"variant-image\" src=\""+ this.dataset.variantImageUrl+"\"/>");
			  }, function(){
				 $(this).parents('.thumbnail-container').find('.product-container-img a img.variant-image').remove();
				 $(this).parents('.thumbnail-container').find('.product-container-img a img').css("display","block");
			});
		
	}
	
	function isMobile(){
		return ((screen.width < 768)
		);
	}
})

