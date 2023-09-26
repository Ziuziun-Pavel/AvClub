<?php echo $header; ?>
<section class="nf_section">
  <div class="container">
    <div class="nf__num">
      <svg viewBox="0 0 514 183" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M223.54 6.686a70.468 70.468 0 0 0-8.741 4.564c-11.667 7.167-20.834 17.667-27.5 31.5-6.5 13.667-9.75 29.917-9.75 48.75s3.25 35.167 9.75 49c6.666 13.667 15.833 24.083 27.5 31.25 11.666 7.167 25 10.75 40 10.75 6.491 0 12.679-.686 18.564-2.059l-11.35-39.584c-2.238.762-4.643 1.143-7.214 1.143-8.5 0-15.25-4-20.25-12-4.834-8.167-7.25-21-7.25-38.5s2.416-30.25 7.25-38.25a34.554 34.554 0 0 1 1.641-2.449L223.54 6.686Zm50.979 123.364.03-.05c5-8.167 7.5-21 7.5-38.5s-2.5-30.25-7.5-38.25C269.715 45.083 263.132 41 254.799 41c-1.97 0-3.845.22-5.627.658l-11.323-39.49C243.24 1.056 248.89.5 254.799.5c14.833 0 28.083 3.583 39.75 10.75 11.667 7.167 20.75 17.667 27.25 31.5 6.666 13.667 10 29.917 10 48.75s-3.334 35.167-10 49c-6.5 13.667-15.583 24.083-27.25 31.25a71.05 71.05 0 0 1-6.99 3.776l-13.04-45.476ZM141.5 145.5h26v-39.25h-26V76.5H94.75v29.75h-35.5L130.5 4H79.25L.75 113.25v32.25h92.5V179h48.25v-33.5Zm346.191 0h26v-39.25h-26V76.5h-46.75v29.75h-35.5L476.691 4h-51.25l-78.5 109.25v32.25h92.5V179h48.25v-33.5Z" fill="#B21A1A"/></svg>
    </div>
    <div class="nf__text">
      Такой страницы не существует
    </div>
    <div class="nf__btn">
      <a href="<?php echo $continue; ?>" class="btn btn-red"><span>Вернуться на главную</span></a>
    </div>
  </div>
</section>
<style>
  .header{display: block !important;}
  .footer{display: none;}
  .foot__before{display: none;}
  .page__outer{
    /*min-height: 100vh;*/
    display: flex;
    -ms-display: flex;
    flex-flow: column;
    -ms-flex-flow: column;
  }
  @media (max-width: 767px) {
    body:not(.branding__mob)
  }
</style>
<?php echo $footer; ?>