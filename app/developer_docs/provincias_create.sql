--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET client_encoding = 'UTF8';
SELECT pg_catalog.set_config('search_path', 'public' , false);

--
-- Name: provincia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.provincia (
    id integer NOT NULL,
    codigo character(2),
    nombre character varying(50),
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone,
    observacion_id integer,
    geometria_id integer
);


--
-- Name: provincia_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.provincia_id_seq
    START WITH 25
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: provincia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.provincia_id_seq OWNED BY public.provincia.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.provincia ALTER COLUMN id SET DEFAULT nextval('public.provincia_id_seq'::regclass);



