--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET client_encoding = 'UTF8';
SELECT pg_catalog.set_config('search_path', 'public' , false);

--
-- Name: subtipo_vivienda; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.subtipo_vivienda (
    id integer NOT NULL,
    codigo character(4),
    nombre character varying(50),
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone
);


--
-- Name: subtipo_vivienda_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.subtipo_vivienda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: subtipo_vivienda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.subtipo_vivienda_id_seq OWNED BY public.subtipo_vivienda.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subtipo_vivienda ALTER COLUMN id SET DEFAULT nextval('public.subtipo_vivienda_id_seq'::regclass);



