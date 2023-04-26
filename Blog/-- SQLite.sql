-- SQLite
-- CREATE TABLE users(uuid TEXT, first_name TEXT, last_name TEXT);

-- ALTER TABLE users ADD uuid TEXT;

-- DROP TABLE users;
CREATE TABLE users (
uuid TEXT NOT NULL
CONSTRAINT uuid_primary_key PRIMARY KEY,
username TEXT NOT NULL
CONSTRAINT username_unique_key UNIQUE,
first_name TEXT NOT NULL,
last_name TEXT NOT NULL
);