/*
junta manzanas de todos los ePPDDLLL
autor: -h+m
fecha: 2022-05-04 Mi
*/

DROP FUNCTION if exists indec.manzanas();
create or replace function indec.manzanas()
   returns table (
    prov character varying,
    dpto character varying,
    codloc character varying,
    frac character varying,
    radio character varying,
    mza character varying,
    wkb_geometry public.geometry,
    conteo integer,
    cant_lados integer,
    created_at timestamp with time zone,
    codent character varying,
    noment character varying
)
language plpgsql volatile
set client_min_messages = 'notice'
as $function$
declare
strSQL text;
rec record;
count_loc integer;
begin
count_loc := 0;
RAISE NOTICE 'Buscando manzanas de todas las localidades...';
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name in ('manzanas','conteos')
            GROUP BY table_schema
            HAVING count(*) = 2
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select 
  m.prov,
  m.dpto,
  m.codloc,
  m.frac,
  m.radio,
  m.mza,
  st_transform(wkb_geometry,4326) as wkb_geometry,
  sum(conteo)::integer as conteo,
  count(distinct lado)::integer as cant_lados,
  now() created_at,
  null::character varying codent,
  null::character varying noment
from ' || rec.table_schema || '.manzanas m left join ' || rec.table_schema || '.conteos c on 
    (c.prov,c.dpto,c.codloc,c.frac,c.radio,c.mza) = 
    (m.prov::integer,m.dpto::integer,m.codloc::integer,m.frac::integer,m.radio::integer,m.mza::integer)
group by 
  m.prov,
  m.dpto,
  m.codloc,
  m.frac,
  m.radio,
  m.mza,
  codent,
  noment,
  wkb_geometry ');
    count_loc := count_loc + 1;
 END LOOP;

RAISE NOTICE 'Juntando % localidades',count_loc;

return query

EXECUTE strSQL;

end
$function$
;
