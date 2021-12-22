SELECT nc.*
FROM `label` as nc
WHERE
exists(
	select * from label as gc where gc.label_no in({$listString})
	and nc.lft >= gc.lft and nc.lft <= gc.rgt
)
and nc.publisher_no = {$publisher_no}
order by nc.lft;