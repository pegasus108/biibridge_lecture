select
	rgt,
	lft,
	(rgt - lft) + 1 as toDistance
from label
where rgt = ({$myL} - 1)
	AND publisher_no = {$publisher_no};
