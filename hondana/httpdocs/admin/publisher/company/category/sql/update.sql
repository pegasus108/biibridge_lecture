update company_category
set
	name = '{$category_name}'
where
	company_category_no = '{$company_category_no|escape}';


