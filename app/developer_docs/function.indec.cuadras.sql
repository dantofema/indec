/*
junta arcos de todos los ePPDDLLL en cuadras
autor: -h+m
fecha: 2022-05-23 Lu
*/

DROP FUNCTION if exists indec.cuadras();
create or replace function indec.cuadras()
returns table (
    geom public.geometry,
    fnode_ integer,
    tnode_ integer,
    lpoly_ integer,
    rpoly_ integer,
    length double precision,
    codigo10 integer,
    nomencla character varying,
    codigo20 integer,
    ancho integer,
    anchomed double precision,
    tipo character varying,
    nombre character varying,
    ladoi integer,
    ladod integer,
    desdei integer,
    desded integer,
    hastai integer,
    hastad integer,
    mzai character varying,
    mzad character varying,
    codloc20 character varying,
    nomencla10 character varying,
    nomenclai character varying,
    nomenclad character varying,
    codinomb character varying,
    de_esquema character varying,
    srid integer,
--    segi integer,
--    segd integer,
    created_at timestamp with time zone
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
RAISE NOTICE 'Buscando arc de todas las localidades...';
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name = 'arc'
            GROUP BY table_schema
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select 
    st_transform(wkb_geometry,4326) as geom, 
    Null::integer fnode_, Null::integer tnode_, Null::integer lpoly_, Null::integer rpoly_, 
    Null::double precision length, Null::integer codigo10,
--    en algunos esquemas .arc no trae estos datos, se conservan por legacy
    nomencla::character varying,
    codigo20::integer,
    ancho::integer,
    anchomed::double precision,
    tipo::character varying,
    nombre::character varying,
    ladoi::integer,
    ladod::integer,
    desdei::integer,
    desded::integer,
    hastai::integer,
    hastad::integer,
    mzai::character varying,
    mzad::character varying,
    ''' || substr(rec.table_schema,2,8) || '''::character varying as codloc20,
    Null::character varying nomencla10,
    nomenclai::character varying,
    nomenclad::character varying,
    codinomb::character varying,
    ''' || rec.table_schema || '''::character varying de_esquema,
    st_srid(wkb_geometry) srid,
    now() created_at
from ' || rec.table_schema || '.arc 
  ');
    count_loc := count_loc + 1;
 END LOOP;

RAISE NOTICE 'Juntando % localidades',count_loc;

return query

EXECUTE strSQL;

end
$function$

