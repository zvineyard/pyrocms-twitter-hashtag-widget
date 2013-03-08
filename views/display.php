<ul class="rss">
    <?php foreach($tweets->results as $tweet): ?>
	<li>
	    <?php
	    if($show_images == 1)
	    {
	    	echo '<div class="twitter_profile_img"><a href="http://twitter.com/'.$tweet->from_user.'" target="_blank"><img src="'.$tweet->profile_image_url.'" height="30" width="30" alt="'.$tweet->from_user.'"/></a></div>';
	    }
	    ?>
	    <?php echo '<div class="twwet">'.$tweet->text.'</div>'; ?>
	    <p class="date"><em><?php echo anchor('https://twitter.com/' . $tag . '/status/' . $tweet->id_str, format_date($tweet->created_at, Settings::get('date_format') . ' h:i'), 'target="_blank"'); ?></em></p>
	</li>
    <?php endforeach; ?>
</ul>