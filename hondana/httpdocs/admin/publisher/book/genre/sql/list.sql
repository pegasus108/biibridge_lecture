SELECT
	nc.genre_no,
	nc.name,
	nc.lft, nc.rgt, nc.depth,
	({$addMaxDepth} >= nc.depth) as add_flag,
	ncp.*
FROM genre AS nc
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, genre_no as parent_no
	from genre
	where publisher_no = {$publisher_no}
) as ncp
	on nc.lft > ncp.parent_lft and nc.rgt < ncp.parent_rgt and (nc.depth - 1) = ncp.parent_depth

WHERE nc.display = 1
AND nc.publisher_no = {$publisher_no}
ORDER BY nc.lft;