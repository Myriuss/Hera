DROP DATABASE IF EXISTS `hera`;
CREATE DATABASE IF NOT EXISTS `hera`;

USE `hera`;
 
 
CREATE TABLE IF NOT EXISTS newsletter
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`email_info` VARCHAR(1024) NOT NULL,
	UNIQUE ( `id`) 
);

CREATE TABLE IF NOT EXISTS user
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`nom` VARCHAR(255) NOT NULL, 
	`prenom` VARCHAR(255) NOT NULL, 
	`adress` VARCHAR(255) , 
	`email` VARCHAR(25) NOT NULL, 
	`tel` varchar(13) , 
	`is_admin` BOOLEAN DEFAULT false,
	`sub_date` INT  NOT NULL DEFAULT UNIX_TIMESTAMP() , 
	`password` VARCHAR(255) NOT NULL,
	UNIQUE ( `email`) ,
    
	UNIQUE ( `id`) ,
	PRIMARY KEY(`id`)
);
 

CREATE TABLE IF NOT EXISTS post
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`slug` VARCHAR(255) NOT NULL,
	`name` VARCHAR(255) NOT NULL, 
	`description` VARCHAR(512) ,
	`prix` smallint ,
	`monnais` varchar(15),
	`disponible` BOOLEAN DEFAULT 1 ,
	`p_date` INT  NOT NULL DEFAULT UNIX_TIMESTAMP() , 
	UNIQUE ( `slug`) ,
	PRIMARY KEY(`id`)
);
  

CREATE TABLE IF NOT EXISTS categorie
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(255) NOT NULL, 
	`slug` VARCHAR(255) NOT NULL, 
	UNIQUE ( `slug`) ,
	PRIMARY KEY(`id`)
);
 

CREATE TABLE IF NOT EXISTS image
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`name` VARCHAR(255) NOT NULL, 
	`for_post` INT NOT NULL,
	
	 CONSTRAINT `fk_img`
	 FOREIGN KEY (`for_post`)
	  REFERENCES `post` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE RESTRICT , 

	PRIMARY KEY(`id`)
);
 

CREATE TABLE IF NOT EXISTS comende
(
	`id` INT NOT NULL AUTO_INCREMENT, 
	`statut` smallint  DEFAULT -2,
	`client` INT  NOT NULL ,
	`type` VARCHAR(255) NOT NULL, 
	`description` VARCHAR(512) ,
	`c_date` INT  NOT NULL DEFAULT UNIX_TIMESTAMP() , 
	
	
		CONSTRAINT `fk_cmd`
	 FOREIGN KEY (`client`)
	  REFERENCES `user` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE RESTRICT , 

	PRIMARY KEY(`id`)
);
 

CREATE TABLE IF NOT EXISTS categorie_post
(
	`id_post` INT NOT NULL , 
	`id_categorie` INT NOT NULL ,
	PRIMARY KEY(`id_post` ,`id_categorie` ),
	CONSTRAINT `fk_post`
	 FOREIGN KEY (`id_post`)
	  REFERENCES `post` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE RESTRICT , 

	CONSTRAINT fk_categorie
	 FOREIGN KEY (`id_categorie`)
	  REFERENCES `categorie` (`id`)
	  ON DELETE CASCADE
	  ON UPDATE RESTRICT 
);
 

INSERT INTO categorie (`name` , `slug`  )
VALUES

(' Rind', 'ring ' ),
(' Bracelet', 'bracelet'  ),
('Collier', 'colier '   )
; 
   


INSERT INTO post  (`name`, `slug`,`description`,`prix`, `monnais` )
VALUES
('bijou 1 ', 'bijou1',' Collier en or ' , '1400' , 'DH'  ),
('bijou 2 ', 'bijou2',' Bracelet en argent ' , '300' , 'DH'  ),
('bijou 3 ', 'bijou3',' Bague en or avec une pierre en quartz ' , '2000' , 'DH'  ),
('bijou 4 ', 'bijou4',' Anneau en or 9 carats ' , '1000' , 'DH'  ),
('bijou 5 ', 'bijou5',' Bague en argent avec une pierre en améthyste' , '700' , 'DH'  ),
('bijou 6 ', 'bijou6',' Collier en argent Lune ' , '600' , 'DH'  ),
('bijou 7 ', 'bijou7',' Bracelet en or 9 carats ' , '1000' , 'DH'  )

; 

 

INSERT INTO categorie_post  (`id_post`, `id_categorie`  )
VALUES
('1','1'),
('1','2'),
('3','3'),
('2','1'),
('2','3')
; 
 
 
select * from categorie_post
left join categorie ON categorie_post.id_categorie = categorie.id
 where categorie_post.id_post = 1 ;
 
select * from categorie_post    left join  post ON categorie_post.id_post = post.id  where categorie_post.id_categorie = 1 ;
 

INSERT INTO user  ( `nom`, `prenom`, `adress`, `email`, `tel`, `password`, `is_admin` )
VALUES

('ISSAZAKOU', 'HALIMA' ,'0559205748' , 'ISSAZAKOUHALIMA@gmail.com' , '0660123456=7' , SHA2('123456', 256),1),
('Hermes', 'Sofiane' ,'Kabylia' , 'hermes@gmail.com' , '0660123456' , SHA2('123456', 256),0),
('Moh', 'Most' ,'' , 'most@gmail.com' , '0559205748' , SHA2('123456', 256),0),
('Meriem', 'maroc' ,'' , 'meriem@gmail.com' , '0559205749' , SHA2('123456', 256),1)
;


 

INSERT INTO comende  (`client`,  `type`, `description` , `statut` )
VALUES

( ' 3' , 'bijou1' , 'marque machin ' , -2  ),
( ' 3' , 'bijou2' , 'machin machin ' , 0  ),
( ' 3' , 'bijou3' , 'marque bidule ' , 1  ),
( '3' , 'bijou4' , 'bojouur blabla',1  )     
;



 

INSERT INTO image  (`name` , `for_post` )
VALUES


('View/image/post/image3.jpeg',1),
('View/image/post/image4.jpeg',2),
('View/image/post/image10.jpeg',3),
('View/image/post/image9.jpeg',4),
('View/image/post/image7.jpeg',5),
('View/image/post/image8.jpeg',6),
('View/image/post/image6.jpeg',7),
('View/image/post/image5.jpeg',1),
('View/image/post/image3.jpeg',2)
; 
 


 
show tables ;