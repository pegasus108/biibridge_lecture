select node.lft, parent.series_no, parent.name, node.depth, parent.depth AS parent_depth

from
	series as node,
	series as parent

where
	node.lft between parent.lft and parent.rgt
	and node.series_no = {$series_no}
	and parent.display = 1
	and parent.publisher_no = {$publisher_no}

order by node.lft;