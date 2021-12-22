select
b.*,
g.name as gname,
g.depth,
g.lft,
g.rgt 

from book as b 
left join book_genre as bg on b.book_no = bg.book_no and b.publisher_no = {$publisher_no}
left join genre as g on bg.genre_no = g.genre_no and g.publisher_no = {$publisher_no}

where 
b.publisher_no = {$publisher_no} and 
b.public_status = 1 and 
b.public_date <= now()

order by 
g.lft,b.book_date,b.name
;

