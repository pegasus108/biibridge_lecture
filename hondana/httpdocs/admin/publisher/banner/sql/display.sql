update banner
set
	public_status = 1
where banner_no
	in(
		{foreach name=banner_no from=$banner_no_list item=banner_no }
		{$banner_no}{if !$smarty.foreach.banner_no.last},{/if}
		{/foreach}
	)
	and publisher_no = '{$publisher_no|escape}';

