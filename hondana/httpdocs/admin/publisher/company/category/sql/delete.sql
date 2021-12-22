{foreach name=deleteCategory from=$deleteCategoryList item=deleteCategory}

select @myL := lft from company_category
where
	company_category_no = '{$deleteCategory.company_category_no|escape}';

delete
from company
where
	company_category_no = '{$deleteCategory.company_category_no|escape}'
	and publisher_no = '{$publisher_no|escape}';

delete
from company_category
where
	company_category_no = '{$deleteCategory.company_category_no|escape}'
	and publisher_no = '{$publisher_no|escape}';


update company_category
set
	lft = lft - 2
where
	lft > @myL
	and publisher_no = '{$publisher_no|escape}';


update company_category
set
	rgt = rgt - 2
where
	rgt > @myL
	and publisher_no = '{$publisher_no|escape}';

{/foreach}

