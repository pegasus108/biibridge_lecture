lock tables publisher_netshop write;


update publisher_netshop
set
	public_status = 1
where publisher_netshop_no
	in(
		{foreach name=publisher_netshop_no from=$publisher_netshop_no_list item=publisher_netshop_no }
		{$publisher_netshop_no}{if !$smarty.foreach.publisher_netshop_no.last},{/if}
		{/foreach}
	)
	and publisher_no = {$publisher_no}
;


unlock tables;
