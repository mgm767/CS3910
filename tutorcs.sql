-- Tutor CS db info

DROP TABLE IF EXISTS users, students, professors, tutors, courses, course_docs, available_sessions, scheduled_sessions;


-- Each user has access to other roles, we track that we boolean fields here
CREATE TABLE users (
	hawk_id VARCHAR(50) NOT NULL,
	first_name VARCHAR(120) NOT NULL,
	last_name VARCHAR(120) NOT NULL,
	student BOOLEAN NOT NULL,
	tutor BOOLEAN NOT NULL,
	administrator BOOLEAN NOT NULL,
	professor BOOLEAN NOT NULL,
	PRIMARY KEY (hawk_id)
);

CREATE TABLE courses (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(120) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE students (
	hawk_id VARCHAR(50) NOT NULL,
	FOREIGN KEY (hawk_id) REFERENCES users (hawk_id),
	-- Supporting international numbers up to 15 digits
	phone_number VARCHAR(15),
	course_id INT NOT NULL,
	FOREIGN KEY (course_id) REFERENCES courses (id),
	PRIMARY KEY (hawk_id)
);

-- Assuming a professor only teaches one course
CREATE TABLE professors (
	hawk_id VARCHAR(50) NOT NULL,
	FOREIGN KEY (hawk_id) REFERENCES users (hawk_id),
	course_id INT NOT NULL,
	FOREIGN KEY (course_id) REFERENCES courses (id),
	PRIMARY KEY (hawk_id)
);

CREATE TABLE tutors (
	hawk_id VARCHAR(50) NOT NULL,
	FOREIGN KEY (hawk_id) REFERENCES users (hawk_id),
	-- Supporting international numbers up to 15 digits
	course_id INT NOT NULL,
	FOREIGN KEY (course_id) REFERENCES courses (id),
	phone_number VARCHAR(15),
	PRIMARY KEY (hawk_id)
);

CREATE TABLE course_docs (
	id INT NOT NULL AUTO_INCREMENT,
	course_id INT NOT NULL,
	FOREIGN KEY (course_id) REFERENCES courses (id),
	PRIMARY KEY (id)
);

CREATE TABLE available_sessions (
	id INT NOT NULL AUTO_INCREMENT,
	slot DATETIME NOT NULL,
	course_id INT NOT NULL,
	FOREIGN KEY (course_id) REFERENCES courses (id),
	tutor_id VARCHAR(50) NOT NULL,
	FOREIGN KEY (tutor_id) REFERENCES users (hawk_id),
	PRIMARY KEY (id)
);

CREATE TABLE scheduled_sessions (
	id INT NOT NULL AUTO_INCREMENT,
	session_id INT NOT NULL,
	FOREIGN KEY (session_id) REFERENCES available_sessions (id),
	student_id VARCHAR(50) NOT NULL,
	FOREIGN KEY (student_id) REFERENCES students (hawk_id),
	doc_id INT NOT NULL,
	FOREIGN KEY (doc_id) REFERENCES course_docs (id),
	PRIMARY KEY (id)
);


INSERT INTO users(hawk_id, first_name, last_name, student, tutor, administrator, professor)
	VALUES ("student", "Jon", "Jameson", TRUE, FALSE, FALSE, FALSE);
INSERT INTO users(hawk_id, first_name, last_name, student, tutor, administrator, professor)
	VALUES ("tutor", "Johanna", "Mendez", FALSE, TRUE, FALSE, FALSE);
INSERT INTO users(hawk_id, first_name, last_name, student, tutor, administrator, professor) 
	VALUES ("admin", "Kelly", "Fields", FALSE, FALSE, TRUE, FALSE);
INSERT INTO users(hawk_id, first_name, last_name, student, tutor, administrator, professor) 
	VALUES ("professor", "Nick", "Jerin", FALSE, FALSE, FALSE, TRUE);
INSERT INTO users(hawk_id, first_name, last_name, student, tutor, administrator, professor) 
	VALUES ("studor", "Yolanda", "Platte", TRUE, TRUE, FALSE, FALSE);
