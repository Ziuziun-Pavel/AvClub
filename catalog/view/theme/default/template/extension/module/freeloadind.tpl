<div class="row freeloadind top-space" id="loading-content">
<?php foreach ($products as $product) { ?>
<div class="product-layout <?php echo $cols; ?> col-sm-6 col-xs-12">
    <div class="product-thumb transition <?php echo $thumb_class; ?>">
      <div class="image"><a href="<?php echo $product['href']; ?>">
	  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="{{ product.name }}" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <!-- <p><?php //echo $product['description']; ?></p> -->
        <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($product['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i>
		  <i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
<?php } ?>
</div>
<!-- Кнопка Показать еще -->
<div class="row btn-freeload">
  <div class="col-md-12 text-center freeloadgif"> 
    <button type="button" class="btn btn-primary" id="freeload-show"><?php echo $loading_more; ?></button>
  </div> 
</div>
<script type="text/javascript"><!--
$(document).ready(function() {

    var loader_img = '<?php echo $loading_gif; ?>';
	var in_Progress = false;
	var start_From  = <?php echo $start_from; ?>;
	
	$('#freeload-show').click(function() {
	    
		var dataStrfree = {
	    	start: start_From
	        };
			
	    if(!in_Progress) {
		    in_Progress = true;
			
			$.ajax({
				type: 'POST',
			 	url: 'index.php?route=extension/module/free_loadind/show_more',
				dataType: 'json',
				data: dataStrfree,
				cache: false,
				beforeSend: function(){
        	   		$('.freeloadgif').prepend('<div><img src="' + loader_img + '" /></div>').show();
					$('#freeload-show').prop('disabled', true);
       		 	},
				success: function(json){
				
				   	if(json.success) {
					    setTimeout(function(){
						            $('.freeloadgif img').remove();
						            $("#loading-content").append(json.response);
									$('#freeload-show').prop('disabled', false);
								   }, 700);
						
						in_Progress = false;
						start_From += <?php echo $start_from_load; ?>;
				    } else {
					    $("#loading-content").append('<div class="loading-clear"></div><p class="text-not"><?php echo $error_views; ?></p>');
						setTimeout(function(){ 
						    $('.freeloadgif img').remove();
							$('.btn-freeload').hide();
						}, 500);
						
						in_Progress = true;
					}
		    	}
	   		});
		}
	});
});
//--></script> 