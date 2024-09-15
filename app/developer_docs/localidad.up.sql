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
-- Name: localidad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.localidad (
    id integer NOT NULL,
    codigo character(8),
    nombre character varying,
    aglomerado_id integer,
    tipo_de_localidad_id integer,
    tipo_de_poblacion_id integer,
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone,
    observacion_id integer,
    geometria_id integer,
    cap_de_rep integer,
    cap_de_pcia integer,
    cab_de_depto integer,
    sede_gob_loc integer
);


--
-- Name: localidad_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.localidad_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: localidad_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.localidad_id_seq OWNED BY public.localidad.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.localidad ALTER COLUMN id SET DEFAULT nextval('public.localidad_id_seq'::regclass);

--
-- Name: localidad_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX localidad_id_index ON public.localidad USING btree (id);

--
-- PostgreSQL database dump complete
--

