CREATE TABLE IF NOT EXISTS dialogue (
  id_dialogue int(11) NOT NULL AUTO_INCREMENT,
  id_membre int(3) NOT NULL,
  message text NOT NULL,
  date datetime NOT NULL,
  PRIMARY KEY (id_dialogue)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS membre (
  id_membre int(3) NOT NULL AUTO_INCREMENT,
  pseudo varchar(60) NOT NULL,
  civilite enum('m','f') NOT NULL,
  ville varchar(50) NOT NULL,
  date_de_naissance date NOT NULL,
  ip varchar(15) NOT NULL,
  date_connexion varchar(11) NOT NULL,
  PRIMARY KEY (id_membre)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
