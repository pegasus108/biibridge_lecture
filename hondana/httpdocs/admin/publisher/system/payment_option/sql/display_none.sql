lock tables publisher_payment write;


update publisher_payment
set
	public_status = 0
where publisher_payment_no
	in(
		{foreach name=publisher_payment_no from=$publisher_payment_no_list item=publisher_payment_no }
		{$publisher_payment_no}{if !$smarty.foreach.publisher_payment_no.last},{/if}
		{/foreach}
	)
;


unlock tables;
