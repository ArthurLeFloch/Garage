-- =====================
--   Database : Garage
-- =====================

-- Ajout de l'utilisateur et des privilèges
CREATE USER web WITH PASSWORD 'password';
CREATE DATABASE garage WITH OWNER web;

\c garage
\i create.sql
\i update.sql
\i insert.sql
\i select.sql

GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO web;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO web;
