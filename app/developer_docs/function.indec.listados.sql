/*
junta listados segmentados de todos los ePPDDLLL
autor: -h+m
fecha: 2022-04-12 Ma
*/

DROP FUNCTION if exists indec.listados();
create or replace function indec.listados()
 returns table (
  prov text,
  provincia text, 
  dpto text, 
  departamento text, 
  codloc text,
  localidad text,
  frac text, 
  radio text, 
  mza text, 
  lado text, 
  seg text, 
  ccalle text,
  ncalle text,
  nrocatastr text, 
  piso text, 
  casa text,
  dpto_habit text,
  sector text,
  edificio text,
  entrada text,
  orden_reco text, 
  tipoviv text,
  created_at timestamp with time zone
)
language plpgsql volatile
set client_min_messages = 'notice'
as $function$
declare
strSQL text;
rec record;
begin
RAISE NOTICE 'Buscando listados de todas las localidades...';
 FOR rec IN SELECT table_schema
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name in ('listado','segmentacion','r3')
            GROUP BY table_schema
            HAVING count(*)=3       
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'
select 
  l.prov::text, 
  p.nombre::text provincia, 
  l.dpto::text, 
  d.nombre::text departamento, 
  l.codloc::text ,
  loc.nombre::text localidad,
  l.frac::text, 
  l.radio::text, 
  l.mza::text, 
  l.lado::text, 
  seg::text, 
  ccalle::text,
  ncalle::text,
  nrocatastr::text, 
  piso::text, 
  casa::text,
  dpto_habit::text,
  sector::text,
  edificio::text,
  entrada::text,
  orden_reco::text,
  tipoviv::text,
  now() created_at 
from ' || rec.table_schema || '.listado l join provincia p on p.codigo = prov 
join departamentos d on d.codigo = prov || dpto 
join ' || rec.table_schema || '.segmentacion s on s.listado_id = l.id
join ' || rec.table_schema || '.r3 on r3.segmento_id = s.segmento_id
join localidad loc on loc.codigo = l.prov || l.dpto || l.codloc');
 END LOOP;

return query
EXECUTE strSQL;

end
$function$

