SELECT
s.*

FROM series AS s,
series as ps

WHERE
s.publisher_no={$publisher_no}
and ps.publisher_no={$publisher_no}
and s.name = '{$name}'
and s.depth = {$depth}
and ps.series_no = {$target_series}
and ps.lft < s.lft
and ps.rgt > s.rgt

limit 1
;