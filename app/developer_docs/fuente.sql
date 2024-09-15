--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2 (Ubuntu 12.2-4)
-- Dumped by pg_dump version 12.2 (Ubuntu 12.2-4)

SET statement_timeout = 0;
SET lock_timeout = 0;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: fuente; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.fuente VALUES (1, 'Mapa Educativo (GPS)');
INSERT INTO public.fuente VALUES (2, 'IGN. TUCUMAN 100K');
INSERT INTO public.fuente VALUES (3, 'Mapa Educativo');
INSERT INTO public.fuente VALUES (4, 'IGN');
INSERT INTO public.fuente VALUES (5, 'INDEC');
INSERT INTO public.fuente VALUES (6, 'INDEC. ACA');
INSERT INTO public.fuente VALUES (7, 'IGN. SIG 250');


--
-- Name: fuente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.fuente_id_seq', 7, true);


--
-- PostgreSQL database dump complete
--

