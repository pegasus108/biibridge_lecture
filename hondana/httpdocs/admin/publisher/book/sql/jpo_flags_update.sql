{*lock tables book write;*}


update book set
	{if $imagePsoted}
	image_posted = null,
	{/if}
	sync_allowed = {if $syncAllowed}now(){else}NULL{/if}
	
where
	book_no = {$book_no};


{*unlock tables;*}
