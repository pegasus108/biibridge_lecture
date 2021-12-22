select
	rgt,
	lft,
	(rgt - lft) + 1 as myDistance
from series
where series_no = {$series_no}
	AND publisher_no = {$publisher_no};
