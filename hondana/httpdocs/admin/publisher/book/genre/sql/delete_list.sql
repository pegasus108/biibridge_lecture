SELECT nc.*
FROM `genre` as nc
WHERE
exists(
	select * from genre as gc where gc.genre_no in({$listString})
	and nc.lft >= gc.lft and nc.lft <= gc.rgt
)
and nc.publisher_no = {$publisher_no}
order by nc.lft;