select
	@myR := rgt,
	@myL := lft,
	@myDistance := (rgt - lft) + 1
from company_category
where company_category_no = '{$company_category_no|escape}'
	AND publisher_no = '{$publisher_no|escape}';


select
	@toR := rgt,
	@toL := lft,
	@toDistance := (rgt - lft) + 1
from company_category
where rgt = (@myL - 1)
	AND publisher_no = '{$publisher_no|escape}';


update company_category
set
	rgt = rgt * (-1),
	lft = lft * (-1)
where lft between @myL and @myR
	AND publisher_no = '{$publisher_no|escape}';


update company_category
set
	rgt = rgt + @myDistance,
	lft = lft + @myDistance
where lft between @toL and @toR
	AND publisher_no = '{$publisher_no|escape}';


update company_category
set
	rgt = (rgt * (-1)) - @toDistance,
	lft = (lft * (-1)) - @toDistance
where lft < 0
	AND publisher_no = '{$publisher_no|escape}';


