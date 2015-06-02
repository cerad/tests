DROP DATABASE IF EXISTS tests;

CREATE DATABASE tests;

USE tests;

CREATE TABLE users 
(
  id INT AUTO_INCREMENT        NOT NULL, 
  user_name       VARCHAR(255) NOT NULL,
  disp_name       VARCHAR(255),
  email           VARCHAR(255) NOT NULL,
  email_verified  BOOLEAN      DEFAULT false,
  password        VARCHAR(255),
  salt            VARCHAR(255),
  roles           VARCHAR(999),
  person_key      VARCHAR( 80),
  person_verified BOOLEAN      DEFAULT false,
  status          VARCHAR( 20) DEFAULT 'Active',
  PRIMARY KEY(id),
  UNIQUE  INDEX users__user_name (user_name), 
  UNIQUE  INDEX users__email     (email), 
          INDEX users__person_key(person_key)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE user_auths 
(
  id      INT AUTO_INCREMENT NOT NULL, 
  user_id INT                NOT NULL,

  provider VARCHAR(255) NOT NULL,
  sub      VARCHAR(255) NOT NULL,
  iss      VARCHAR(255) NOT NULL,
  name     VARCHAR(255),
  email    VARCHAR(255),
  PRIMARY KEY(id),
  FOREIGN KEY(user_id) REFERENCES users(id),
  UNIQUE   INDEX user_auths__provider_sub_iss(provider,sub,iss)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

INSERT INTO users (id,user_name,disp_name,email) VALUES
(1,'ahundiak','Art Hundiak',    'ahundiak@example.com'),
(2,'bclinton','Bill Clinton',   'bclinton@example.com'),
(3,'hclinton','Hillary Clinton','hclinton@example.com'),
(4,'cclinton','Chelse Clinton', 'cclinton@example.com'),
(5,'gomally', 'George O''Ma<br>ly','gomally@example.com')
;

DROP TABLE IF EXISTS types;

CREATE TABLE types 
(
  id INT AUTO_INCREMENT NOT NULL, 
  strx    VARCHAR(255)  NOT NULL,
  intx    INTEGER,
  boolx   BOOLEAN  DEFAULT false,
  decx    DECIMAL(5,2),
  floatx  FLOAT,
  doublex DOUBLE,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

INSERT INTO types (id,strx,intx,boolx,decx,floatx,doublex) VALUES
(1,'ONE',42,true,5.22,3.14159,3.14159),
(2,'TWO',42,0,   5.22,3.14159,3.14159);
