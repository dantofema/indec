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
-- Name: gobierno_local; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gobierno_local (
    id integer NOT NULL,
    codigo character(6),
    nombre character varying(50),
    categoria_de_agl_id integer,
    tipo_de_agl integer,
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
-- Name: gobierno_local_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gobierno_local_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gobierno_local_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gobierno_local_id_seq OWNED BY public.gobierno_local.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gobierno_local ALTER COLUMN id SET DEFAULT nextval('public.gobierno_local_id_seq'::regclass);


--
-- PostgreSQL database dump complete
--

