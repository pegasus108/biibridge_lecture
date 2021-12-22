SELECT
	nc.company_category_no,
	nc.name,
	nc.lft, nc.rgt, nc.depth,
	({$addMaxDepth} >= nc.depth) as add_flag,
	ncp.*
FROM company_category AS nc
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, company_category_no as parent_no
	from company_category
	where publisher_no = '{$publisher_no|escape}'
) as ncp
	on nc.lft > ncp.parent_lft and nc.rgt < ncp.parent_rgt and (nc.depth - 1) = ncp.parent_depth

WHERE nc.display = 1
AND nc.publisher_no = '{$publisher_no|escape}'
AND nc.d_stamp IS NULL
ORDER BY nc.lft;