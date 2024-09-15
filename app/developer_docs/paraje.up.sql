--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', 'public' , false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: paraje; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.paraje (
    id serial,
    codigo character(8) NOT NULL,
    nombre character varying,
    departamento_id integer NOT NULL,
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone,
    observacion_id integer,
    fuente_id integer,
    geometria_id integer,
    sede_gob_loc integer,
    gobierno_local_id integer,
    tipo_de_poblacion_id integer
);





--
-- PostgreSQL database dump complete
--

