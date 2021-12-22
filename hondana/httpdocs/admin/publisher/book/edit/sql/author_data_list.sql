select
*
from
author

where
publisher_no = '{$publisher_no|escape}'
and
author_no in ('{"','"|implode:$opus_list}');