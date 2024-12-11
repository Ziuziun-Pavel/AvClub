<?php echo $header; ?>

<?php echo $content_top; ?>
<section class="section_content section_article">
    <div class="container">

        <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs-back.tpl'); ?>

        <div class="content__title">
            <h1><?php echo $heading_title; ?></h1>
        </div>
        <div class="content__wish">
            <button type="button"
                    class="wish wish-<?php echo $journal_id; ?> wish__text <?php echo $wish_active ? 'active' : ''; ?>"
                    data-active="Добавлено в избранное" data-passive="В избранное"
                    data-id="<?php echo $journal_id;  ?>">
                <svg class="ico">
                    <use xlink:href="#wish"/>
                </svg>
                <span><?php echo $wish_active ? 'Добавлено в избранное' : 'В избранное'; ?></span>
            </button>
            <?php if($type && !empty($date)) { ?>
            <div class="date">
                <?php echo $date; ?>
            </div>
            <?php } ?>

            <?php if($type !== 'opinion' && $author) { ?>
            <div class="content__author">
                <?php
					if(!empty($author['href'])) {
						$author_text = '<a href="' . $author['href'] . '" class="link_under">' . $author['name'] . '</a>
                ';
                }else{
                $author_text = $author['name'];
                } ?>
                <span>Автор:</span> <?php echo $author_text; ?>
            </div>
            <?php } ?>
        </div>

        <div class="content__cont text__cont">

            <div class="content__row row">
                <div class="content__text col-md-8 col-lg-8 col-xl-9">
                    <?php if($type === 'opinion' && $author) { ?>
                    <<?php echo !empty($author['href']) ? 'a href="'.$author['href'].'"' : 'span'; ?>
                    class="aopinion__item link__outer">
                    <span class="aopinion__img">
							<span class="opinion__image">
								<img src="<?php echo $author['avatar']; ?>" alt="">
                                <?php if(!empty($author['href'])) { ?>
                                <span class="opinion__expert"><svg class="ico ico-center"><use
                                                xlink:href="#star"/></svg></span>
                                <?php } ?>
							</span>
						</span>
                    <span class="aopinion__data">
							<span class="aopinion__name"><?php echo $author['name']; ?></span>
							<span class="aopinion__exp"><?php echo $author['exp']; ?></span>
						</span>
                </<?php echo !empty($author['href']) ? 'a' : 'span'; ?>>
                <?php } ?>
                <?php if($thumb) { ?>
                <div class="text__thumb">
                    <?php if($video) { ?>
                    <?php if (strpos($video, 'youtube') !== false || strpos($video, 'youtu.be') !== false): ?>
                            <!-- Было с ссылками на youtube -->
                            <a href="<?php echo $video; ?>" class="text__video" data-fancybox>
                                <img src="<?php echo $thumb; ?>" alt="">
                                <span class="text__play">
                                    <svg class="ico"><use xlink:href="#play"/></svg>
                                </span>
                            </a>
                        <?php else: ?>
                            <!-- Стало с ссылками на vk -->
                            <div class="video-container">
                                <iframe id="videoIframe" class="text__video" src="<?php echo $video; ?>" width="853"
                                        height="480"
                                        allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
                                        frameborder="0" allowfullscreen></iframe>
                                <img id="videoPlaceholder" class="video-placeholder" src="<?php echo $thumb; ?>"
                                     alt="Video Thumbnail">
                                <span id="playButton" class="text__play">
                                    <svg class="ico"><use xlink:href="#play"/></svg>
                                </span>
                            </div>

                    <script>
                        $(document).ready(function () {
                            $('#playButton').click(function () {
                                $('#videoPlaceholder').hide();
                                $(this).hide();
                                $('#videoIframe').attr('src', function (i, val) {
                                    return val + (val.indexOf('?') !== -1 ? '&' : '?') + 'autoplay=1';
                                });
                            });
                        });
                    </script>

                    <style>
                        .video-container {
                            position: relative;
                            width: 100%;
                            padding-top: 68.25%;
                            overflow: hidden;
                        }

                        .text__video {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            border: none;
                        }

                        .video-placeholder {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            cursor: pointer;
                            object-fit: fill;
                        }

                        .text__play {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            cursor: pointer;
                        }
                    </style>
                        <?php endif; ?>
                    <?php }else{ ?>
                    <img src="<?php echo $thumb; ?>" alt="">
                    <?php } ?>
                </div>
                <?php } ?>


                <?php /* CASE */ ?>
                <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-case-inner.tpl'); ?>
                <?php /* # CASE */ ?>
                <div class="text">
                    <?php echo $description; ?>
                </div>


                <?php /* CASE */ ?>
                <?php if(!empty($case['bottom'])) { ?>
                <?php $journal_case_class = 'icase__cont-bottom'; ?>
                <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-case-inner.tpl'); ?>
                <?php } ?>
                <?php /* # CASE */ ?>

                <?php if(!empty($experts)) { ?>
                <div class="content__experts">
                    <div class="content__experts--title">Респонденты публикации</div>
                    <div class="content__experts--list">
                        <?php foreach($experts as $expert) { ?>
                        <div class="content__experts--col">
                            <?php require(DIR_TEMPLATE . 'avclub/template/_include/journal-expert.tpl'); ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <div class="content__info">
                    <?php if(!empty($copies)) { ?>
                    <div class="content__meta clearfix">
                        <?php foreach($copies as $item) { ?>
                        <span><?php echo preg_replace('|\[(.*)\]|isU', '<a href="$1" class="link" target="_blank">$1</a>
                            ', $item['text']); ?></span>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    <?php require(DIR_TEMPLATE . 'avclub/template/_include/breadcrumbs.tpl'); ?>
                </div>
                <?php echo $html; ?>

            </div>
            <?php echo $column_right; ?>

        </div>

    </div>

    <?php echo $column_left; ?>

    </div>
</section>

<?php echo $content_bottom; ?>
<a href="#" class="toTop goTo">
    <svg class="ico ico-center">
        <use xlink:href="#arrow-top"/>
    </svg>
</a>
<?php echo $footer; ?>

