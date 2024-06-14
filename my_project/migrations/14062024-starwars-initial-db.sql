-- Characters table
CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mass INT(5),
    height INT(5),
    gender VARCHAR(10),
    picture VARCHAR(100)
);

-- Movies table
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Movies - Characters relation table
CREATE TABLE movies_characters (
   movie_id INT,
   character_id INT,
   PRIMARY KEY (movie_id, character_id),
   FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
   FOREIGN KEY (character_id) REFERENCES characters(id) ON DELETE CASCADE
);