select
	rgt,
	lft,
	(rgt - lft) + 1 as toDistance
from series
where lft = ({$myR} + 1)
	AND publisher_no = {$publisher_no};
