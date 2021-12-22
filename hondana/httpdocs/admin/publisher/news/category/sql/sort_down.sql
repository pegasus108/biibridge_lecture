select
	@myR := rgt,
	@myL := lft,
	@myDistance := (rgt - lft) + 1
from news_category
where news_category_no = '{$news_category_no|escape}'
	AND publisher_no = '{$publisher_no|escape}';


select
	@toR := rgt,
	@toL := lft,
	@toDistance := (rgt - lft) + 1
from news_category
where lft = (@myR + 1)
	AND publisher_no = '{$publisher_no|escape}';


update news_category
set
	rgt = rgt * (-1),
	lft = lft * (-1)
where lft between @myL and @myR
	AND publisher_no = '{$publisher_no|escape}';


update news_category
set
	rgt = rgt - @myDistance,
	lft = lft - @myDistance
where lft between @toL and @toR
	AND publisher_no = '{$publisher_no|escape}';


update news_category
set
	rgt = (rgt * (-1)) + @toDistance,
	lft = (lft * (-1)) + @toDistance
where lft < 0
	AND publisher_no = '{$publisher_no|escape}';


