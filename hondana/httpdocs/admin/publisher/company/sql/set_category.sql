update company
set
	company_category_no = '{$company_category_no|escape}'
where
	company_no in ({$listString})
	and publisher_no = '{$publisher_no|escape}';
