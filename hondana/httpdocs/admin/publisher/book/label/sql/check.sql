select
	rgt,
	lft,
	(rgt - lft) + 1 as myDistance
from label
where label_no = {$label_no}
	AND publisher_no = {$publisher_no};
