select node.lft, parent.genre_no, parent.name, node.depth, parent.depth AS parent_depth

from
	genre as node,
	genre as parent

where
	node.lft between parent.lft and parent.rgt
	and node.genre_no = {$genre_no}
	and parent.display = 1
	and parent.publisher_no = {$publisher_no}

order by node.lft;