<?php if (strpos($video['href'], 'youtube') !== false || strpos($video['href'], 'youtu.be') !== false): ?>
<!-- Было с ссылками на youtube -->
<section id="video" class="section_evideo section_evideo-page">
    <div class="container">
        <a href="<?php echo $video['href']; ?>" class="evideo__video" data-fancybox="gallery">
            <img src="<?php echo $video['image']; ?>" alt="">
            <span class="eplay">
				<svg class="ico ico-center"><use xlink:href="#play"/></svg>
			</span>
        </a>
    </div>
</section>
<?php else: ?>
<!-- Стало с ссылками на vk -->
<section id="video" class="section_evideo section_evideo-page">
    <div class="container">
        <div class="video-container">
            <iframe id="videoIframe" class="evideo__video" src="<?php echo $video['href']; ?>" width="853" height="480"
                    allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;"
                    frameborder="0" allowfullscreen></iframe>
            <img id="videoPlaceholder" class="video-placeholder" src="<?php echo $video['image']; ?>" alt="Video Thumbnail">
            <span id="playButton" class="eplay">
                <svg class="ico ico-center"><use xlink:href="#play"/></svg>
            </span>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        $('#playButton').click(function() {
            $('#videoPlaceholder').hide();
            $(this).hide();
            $('#videoIframe').attr('src', function(i, val) {
                return val + (val.indexOf('?') !== -1 ? '&' : '?') + 'autoplay=1';
            });
        });
    });
</script>

<style>
    .video-container {
        position: relative;
        width: 100%;
        height: 100%;
        padding-top: 68.25%;
        overflow: hidden;
    }

    .evideo__video {
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
        object-fit: fill;
        cursor: pointer;
    }

    .eplay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        cursor: pointer;
    }
</style>

<?php endif; ?>
