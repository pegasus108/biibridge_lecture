select
	rgt,
	lft,
	(rgt - lft) + 1 as myDistance
from genre
where genre_no = {$genre_no}
	AND publisher_no = {$publisher_no};
