select 
g.* 

from genre as g 

where 
g.publisher_no = {$publisher_no} and 
g.depth = 1 

order by 
g.lft 
;