<div class="book_wrapper">
    <a id="next_page_button">Forward</a>
    <a id="prev_page_button">Back</a>
    <div id="loading" class="loading">Loading pages...</div>
    <div id="mybook" style="display:none;">
        <div class="b-load"><?php
			$itr=1; foreach( $imgs as $img ): if( $img->menu_order < 1 ){ $itr++; continue; } ?>
                <div>
                    <!-- If you would rather have clickable pages, simply uncomment the links before and after the image -->
                    <!-- <a class="fb_img img-id-<?php echo $img->ID; ?>" id="jp_page_<?php echo $itr; ?>" href="<?php echo $img->guid; ?>"> -->
                        <img src="<?php echo $img->guid; ?>" alt="" />
                    <!-- </a> -->
                </div><?php
			$itr++; endforeach; ?>
        </div>
	</div>
    <noscript>To view this content, you must view using a JavaScript-enabled browser.</noscript>
</div>
