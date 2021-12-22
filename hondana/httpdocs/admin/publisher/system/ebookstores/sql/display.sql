lock tables publisher_ebookstores write;

update publisher_ebookstores
set
	public_status = 1

where ebookstores_no
	in(
		{foreach name=publisher_ebookstores_no from=$ebookstore_no_list item=publisher_ebookstores_no }
		{$publisher_ebookstores_no}{if !$smarty.foreach.publisher_ebookstores_no.last},{/if}
		{/foreach}
	)
	and publisher_no = {$publisher_no}
;


unlock tables;
