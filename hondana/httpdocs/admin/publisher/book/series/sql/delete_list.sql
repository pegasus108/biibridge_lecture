SELECT nc.*
FROM `series` as nc
where
exists(
	select * from series as gc where gc.series_no in({$listString})
	and nc.lft >= gc.lft and nc.lft <= gc.rgt
)
and nc.publisher_no = {$publisher_no}
order by nc.lft;