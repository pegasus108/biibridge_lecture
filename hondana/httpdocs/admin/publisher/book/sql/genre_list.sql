SELECT
	g.*,
	gp.*
FROM genre AS g
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, genre_no as parent_no
	from genre
	where publisher_no={$publisher_no}
) as gp
	on g.lft > gp.parent_lft and g.rgt < gp.parent_rgt and (g.depth - 1) = gp.parent_depth

WHERE g.display = 1
and publisher_no={$publisher_no}
ORDER BY g.lft;