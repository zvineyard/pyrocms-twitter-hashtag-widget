<ol>
	<li class="even">
		<label>Tag</label>
		<?php echo form_input('tag', $options['tag']); ?>
	</li>
	<li class="even">
		<label>Number of items</label>
		<?php echo form_input('number', $options['number']); ?>
	</li>
	<li class="even">
		<label>Show Twitter user images?</label>
		<?php echo form_checkbox('show_images', $options['show_images']); ?>
	</li>
</ol>