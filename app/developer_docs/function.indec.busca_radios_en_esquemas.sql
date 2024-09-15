/*
busca en qué ePPDDDLLL.listado está un dado radio por codigo 'PPDDDFFRR'
en cuál esquema de localidad
es para buscar inconsistencias de la tabla radio_localidad
autor: -h+m
fecha: 2022-05-23 Lu
*/

DROP FUNCTION if exists indec.busca_radio_en_esquema_listado(codigo text);
create or replace function indec.busca_radio_en_esquema_listado(codigo text)
returns table (
  listado text
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
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name = 'listado'
            GROUP BY table_schema
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select distinct ''' || rec.table_schema || '''
from ' || rec.table_schema || '.listado
where ''' || codigo || ''' = prov || dpto || frac || radio
  ');
    count_loc := count_loc + 1;
 END LOOP;
return query
EXECUTE strSQL;
end
$function$
;

DROP FUNCTION if exists indec.radios_de_listados();
create or replace function indec.radios_de_listados()
returns table (
  radio text,
  localidad text
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
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name = 'listado'
            GROUP BY table_schema
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select prov || dpto || frac || radio as radio,
  substr(''' || rec.table_schema || ''',2,8) as localidad
from ' || rec.table_schema || '.listado  
  ');
    count_loc := count_loc + 1;
 END LOOP;
return query
EXECUTE strSQL;
end
$function$
;

DROP FUNCTION if exists indec.radios_de_arcs();
create or replace function indec.radios_de_arcs()
returns table (
  radio text,
  localidad text
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
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name = 'arc'
            GROUP BY table_schema
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select substr(mzai,1,5) || substr(mzai,9,4) as radio,
  substr(''' || rec.table_schema || ''',2,8) as localidad
from ' || rec.table_schema || '.arc
where mzai != '''' and mzai is not Null
union 
select substr(mzad,1,5) || substr(mzad,9,4) as radio,
  substr(''' || rec.table_schema || ''',2,8) as localidad
from ' || rec.table_schema || '.arc
where mzad != '''' and mzad is not Null
  ');
    count_loc := count_loc + 1;
 END LOOP;
return query
EXECUTE strSQL;
end
$function$
;


