--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', 'public', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: fraccion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fraccion (
    id integer NOT NULL,
    codigo character(7),
    departamento_id integer,
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone,
    observacion_id integer,
    geometria_id integer
);


--
-- Name: fraccion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fraccion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fraccion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fraccion_id_seq OWNED BY public.fraccion.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fraccion ALTER COLUMN id SET DEFAULT nextval('public.fraccion_id_seq'::regclass);


--
-- PostgreSQL database dump complete
--

