SELECT
	s.*,
	sp.*
FROM series AS s
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, series_no as parent_no
	from series
	where publisher_no={$publisher_no}
) as sp
	on s.lft > sp.parent_lft and s.rgt < sp.parent_rgt and (s.depth - 1) = sp.parent_depth

WHERE s.display = 1
and publisher_no={$publisher_no}
ORDER BY s.lft;