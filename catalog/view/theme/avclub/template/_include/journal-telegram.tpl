<a href="<?php echo $telegram['link'] ? $telegram['link'] : ''; ?>" class="tg__item" target="_blank">
	<svg viewBox="0 0 100 102" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="50" cy="51" rx="50" ry="51" fill="url(#tg_grad)"/><g clip-path="url(#clip-tg)"><path d="M17.024 49.965l13.365 4.988 5.173 16.635a1.573 1.573 0 002.498.753l7.45-6.073a2.222 2.222 0 012.709-.076l13.436 9.755a1.575 1.575 0 002.468-.952l9.843-47.346c.253-1.221-.947-2.24-2.11-1.79L17.01 47.018c-1.354.522-1.342 2.438.015 2.947zm17.704 2.332l26.119-16.086c.47-.289.952.346.55.72L39.84 56.968a4.468 4.468 0 00-1.385 2.674l-.735 5.441c-.097.727-1.117.799-1.318.096l-2.824-9.923a2.63 2.63 0 011.149-2.959z" fill="#fff" fill-opacity=".85"/></g><defs><linearGradient id="tg_grad" x1="50" y1="0" x2="50" y2="102" gradientUnits="userSpaceOnUse"><stop stop-color="#2BA3E2"/><stop offset="1" stop-color="#0B82BD"/></linearGradient><clipPath id="clip-tg"><path fill="#fff" transform="translate(16 22)" d="M0 0h58v58H0z"/></clipPath></defs></svg>
	<span class="tg__text">
		<?php echo html_entity_decode($telegram['text']); ?>
	</span>
	<span class="tg__btn">
		Перейти в telegram
	</span>
</a>