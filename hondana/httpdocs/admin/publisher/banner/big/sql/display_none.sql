update banner_big
set
	public_status = 0
where banner_big_no
	in(
		{foreach name=banner_big_no from=$banner_big_no_list item=banner_big_no }
		{$banner_big_no}{if !$smarty.foreach.banner_big_no.last},{/if}
		{/foreach}
	)
	and publisher_no = '{$publisher_no|escape}';


