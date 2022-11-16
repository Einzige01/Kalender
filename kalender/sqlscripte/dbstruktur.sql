create database kalender;

CREATE TABLE termine (
  id INT not null auto_increment primary KEY,
  datum date not null,
  zeit time not null,
  titel varchar(40) NOT NULL
);

 