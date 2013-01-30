jQuery(document).ready(function($){
		$('.addtocart').submit(function(){
			var Id =$(this).attr("id");
			var data = {action: 'eshop_special_action',post:$('#'+Id).serialize() };
			$.post(""+eshopCartParams.adminajax, data,
				function(response){
				$('#'+Id +" .eshopajax").insertAfter(this).fadeIn(eshopCartParams.addfadein).html(response).fadeOut(eshopCartParams.addfadeout);
				setTimeout (cleareshopCart,eshopCartParams.cartcleardelay);
				setTimeout (doeshopRequest,eshopCartParams.cartdelay);
				setTimeout (cleareshopRequest,eshopCartParams.cartupdate);
			});
			function doeshopRequest(){
				var tdata = {action: 'eshop_cart'};
				$.post(""+eshopCartParams.adminajax, tdata,
				function(response){
					$(".ajaxcart").insertAfter(this).fadeOut(eshopCartParams.cartfadeout).html(response).fadeIn(eshopCartParams.cartfadein);
				});
			}
			function cleareshopRequest(){
				$(".eshopajax").empty();
			}
			function cleareshopCart(){
				$(".ajaxcart").insertAfter();
			}
			return false;
		});

	});