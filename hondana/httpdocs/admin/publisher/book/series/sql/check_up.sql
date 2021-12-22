select
	rgt,
	lft,
	(rgt - lft) + 1 as toDistance
from series
where rgt = ({$myL} - 1)
	AND publisher_no = {$publisher_no};
