/*
junta vias de todos los ePPDDLLL
autor: -h+m
fecha: 2022-05-19 Ju
*/

DROP FUNCTION if exists indec.vias();
create or replace function indec.vias()
   returns table (
      geom public.geometry,
      nomencla character varying,
      codigo character varying,
      tipo character varying,
      nombre character varying,
      desdei character varying,
      desded character varying,
      hastai character varying,
      hastad character varying,
      codloc character varying,
      codaglo character varying,
      link character varying,
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
RAISE NOTICE 'Buscando vias de todas las localidades...';
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name in ('arc')
            GROUP BY table_schema
            HAVING count(*) = 1
 LOOP
     strSQL := CONCAT_WS(
     ' union ',strSQL,'
        select 
          st_union(st_transform(wkb_geometry,4326)) as geom,
          nomencla,
          codigo20::varchar codigo,
          tipo,
          nombre::varchar nombre,
          min(desdei)::varchar desdei,
          min(desded)::varchar desded,
          max(hastai)::varchar hastai,
          max(hastad)::varchar hastad,
          coalesce(substr(mzai,0,9), substr(mzad,0,9))::varchar codloc,
          null::varchar codaglo,
          (nomencla||nombre||tipo)::varchar link,
          now() created_at 
        from ' || rec.table_schema || '.arc a 
        group by 
          nomencla, codigo20, tipo, nombre, 
          coalesce(substr(mzai,0,9), substr(mzad,0,9))
    ');
    count_loc := count_loc + 1;
 END LOOP;

RAISE NOTICE 'Juntando % localidades',count_loc;

return query

EXECUTE strSQL;

end
$function$

