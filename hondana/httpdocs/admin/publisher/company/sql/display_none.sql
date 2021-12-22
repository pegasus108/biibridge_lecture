update company
set
	public_status = 0
where company_no
	in(
		{foreach name=company_no from=$company_no_list item=company_no }
		{$company_no}{if !$smarty.foreach.company_no.last},{/if}
		{/foreach}
	)
	and publisher_no = '{$publisher_no|escape}';
