-- SQLite
-- CREATE TABLE users(uuid TEXT, first_name TEXT, last_name TEXT);

-- ALTER TABLE users ADD uuid TEXT;

-- DROP TABLE users;

-- CREATE TABLE users (
-- uuid TEXT NOT NULL
-- CONSTRAINT uuid_primary_key PRIMARY KEY,
-- username TEXT NOT NULL
-- CONSTRAINT username_unique_key UNIQUE,
-- first_name TEXT NOT NULL,
-- last_name TEXT NOT NULL
-- );

-- CREATE TABLE posts (
-- uuid TEXT NOT NULL
-- CONSTRAINT uuid_primary_key PRIMARY KEY,
-- author_uuid TEXT NOT NULL,
-- title TEXT NOT NULL,
-- text TEXT NOT NULL
-- );

CREATE TABLE comments (
uuid TEXT NOT NULL
CONSTRAINT uuid_primary_key PRIMARY KEY,
post_uuid TEXT NOT NULL,
author_uuid TEXT NOT NULL,
text TEXT NOT NULL
);
