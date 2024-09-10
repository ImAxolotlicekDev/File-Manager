CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    group_id INT
);

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

ALTER TABLE users ADD FOREIGN KEY (group_id) REFERENCES groups(id);

INSERT INTO groups (name) VALUES ('Group1'), ('Group2');
