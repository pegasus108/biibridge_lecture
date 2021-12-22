SELECT
	nc.label_no,
	nc.name,
	nc.url,
	nc.lft, nc.rgt, nc.depth,
	({$addMaxDepth} >= nc.depth) as add_flag,
	ncp.*
FROM label AS nc
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, label_no as parent_no
	from label
	where publisher_no = {$publisher_no}
) as ncp
	on nc.lft > ncp.parent_lft and nc.rgt < ncp.parent_rgt and (nc.depth - 1) = ncp.parent_depth

WHERE nc.display = 1
AND nc.publisher_no = {$publisher_no}
ORDER BY nc.lft;