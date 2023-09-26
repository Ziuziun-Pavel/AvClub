
$(function(){

	if($('.master__item').length) {
		$(window).on('load resize', function(){
			$.each($('.master__item'), function(key,value) {
				var 
				$self = $(value),
				$item = [],
				$h = [],
				$diff = 0,
				$max = 0;

				$item.image = $self.find('.master__image');
				$item.date = $self.find('.master__date');
				$item.name = $self.find('.master__name');
				$item.preview = $self.find('.master__preview');
				$item.more = $self.find('.master__more');

				$item.preview.css('max-height', '');
				$item.more.css('display', '');

				$h.min = $item.image.outerHeight();
				if($h.min < 220) {$h.min = 220;}

				$h.date = $item.date.outerHeight();
				$h.name = $item.name.outerHeight();
				$h.preview = $item.preview.outerHeight();
				$h.more = $item.more.outerHeight();
				$h.line = $item.preview.css('line-height').replace('px','');
				$max = $h.preview;

				$diff = $h.min - ($h.date + $h.name + $h.preview);
				if($diff < 0) {
					$item.more.show();
					while($diff < 0) {
						$h.preview -= $h.line;
						$max = $h.preview;
						$diff = $h.min - ($h.date + $h.name + $h.preview + $h.more);
					}
					$item.preview.css('max-height', $max + 'px');
				}

			})
		});
	}

});

