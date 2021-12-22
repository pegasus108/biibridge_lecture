SELECT nc.*
FROM `label` as nc
WHERE nc.label_no in ({$listString})
order by nc.depth desc;