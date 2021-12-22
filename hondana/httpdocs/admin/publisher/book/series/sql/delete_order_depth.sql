SELECT nc.*
FROM `series` as nc
WHERE nc.series_no in ({$listString})
order by nc.depth desc;