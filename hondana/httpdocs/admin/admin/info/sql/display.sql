lock tables info write;


update info
set
	public_status = 1
where info_no
	in(
		{foreach name=info_no from=$info_no_list item=info_no }
		{$info_no}{if !$smarty.foreach.info_no.last},{/if}
		{/foreach}
	)
;


unlock tables;
