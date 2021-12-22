SELECT
	nc.news_category_no,
	nc.name, nc.news_fix_category_no,
	nc.lft, nc.rgt, nc.depth,
	ncp.*
FROM news_category AS nc
left join (
	select lft as parent_lft,rgt as parent_rgt,depth as parent_depth, news_category_no as parent_no
	from news_category
	where publisher_no = '{$publisher_no|escape}'
) as ncp
	on nc.lft > ncp.parent_lft and nc.rgt < ncp.parent_rgt and (nc.depth - 1) = ncp.parent_depth
LEFT JOIN news_fix_category AS nfc ON nc.news_fix_category_no = nfc.news_fix_category_no

WHERE nc.display = 1
AND nc.publisher_no = '{$publisher_no|escape}'
AND nc.d_stamp IS NULL
AND nfc.d_stamp IS NULL
ORDER BY nc.lft;