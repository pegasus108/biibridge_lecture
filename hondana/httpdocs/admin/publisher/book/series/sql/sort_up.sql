lock tables series write;


select
	@myR := rgt,
	@myL := lft,
	@myDistance := (rgt - lft) + 1
from series
where series_no = {$series_no}
	AND publisher_no = {$publisher_no};


select
	@toR := rgt,
	@toL := lft,
	@toDistance := (rgt - lft) + 1
from series
where rgt = (@myL - 1)
	AND publisher_no = {$publisher_no};


update series
set
	rgt = rgt * (-1),
	lft = lft * (-1)
where lft between @myL and @myR
	AND publisher_no = {$publisher_no};


update series
set
	rgt = rgt + @myDistance,
	lft = lft + @myDistance
where lft between @toL and @toR
	AND publisher_no = {$publisher_no};


update series
set
	rgt = (rgt * (-1)) - @toDistance,
	lft = (lft * (-1)) - @toDistance
where lft < 0
	AND publisher_no = {$publisher_no};


unlock tables;
