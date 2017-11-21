# noinspection SqlNoDataSourceInspectionForFile

DROP TABLE  IF EXISTS paniers,commandes, produits, users, typeProduits, etats;

-- --------------------------------------------------------
-- Structure de la table typeproduits
--
CREATE TABLE IF NOT EXISTS typeProduits (
  id int(10) NOT NULL,
  libelle varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
)  DEFAULT CHARSET=utf8;
-- Contenu de la table typeproduits
INSERT INTO typeProduits (id, libelle) VALUES
(1, 'Greatsword'),
(2, 'Ultra Greatsword'),
(3, 'Chime'),
(4, 'Dagger'),
(5, 'Bow'),
(6, 'Spear'),
(7, 'Mace'),
(8, 'Axe'),
(9, 'Straight Sword');

-- --------------------------------------------------------
-- Structure de la table etats

CREATE TABLE IF NOT EXISTS etats (
  id int(11) NOT NULL AUTO_INCREMENT,
  libelle varchar(20) NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARSET=utf8 ;
-- Contenu de la table etats
INSERT INTO etats (id, libelle) VALUES
(1, 'A préparer'),
(2, 'Expédié');

-- --------------------------------------------------------
-- Structure de la table produits

CREATE TABLE IF NOT EXISTS produits (
  id int(10) NOT NULL AUTO_INCREMENT,
  typeProduit_id int(10) DEFAULT NULL,
  nom varchar(50) DEFAULT NULL,
  prix float(6,2) DEFAULT NULL,
  photo varchar(50) DEFAULT NULL,
  dispo tinyint(4) NOT NULL,
  stock int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_produits_typeProduits FOREIGN KEY (typeProduit_id) REFERENCES typeProduits (id)
) DEFAULT CHARSET=utf8 ;

INSERT INTO produits (typeProduit_id,nom,prix,photo,dispo,stock) VALUES
(9, 'Anris Straight Sword','100','anris_straight_sword-icon.png',10,10),
(1, 'Astora Greatsword','100','astora_greatsword.png',7,10),
(4, 'Bandit\'s Knife','100','bandits_knife.png',1,20),
(8, 'Battle Axe','100','battle_axe-icon.png',8,25),
(2, 'Black Knight Greatsword','100','black_knight_greatsword.png',2,5),
(3, 'Caitha\'s Chime','100','caithas_chime-icon.png',3,0),
(1, 'Claymore','100','claymore-icon.png',420,15),
(5, 'Dragonrider Bow','100','dragonrider_bow-icon.png',4,5),
(6, 'Dragonslayer Swordspear','100','dragonslayer_swordspear-icon.png',12,5),
(7, 'Drang Hammers','100','drang_hammers-icon.png',24,25),
(6, 'Drang Twinspears','100','drang_twinspears-icon.png',30,30),
(8, 'Eleonora','100','eleonora.png',12,60),
(1, 'Farron Greatsword','100','farron_greatsword-icon.png',10,20),
(1, 'Firelink Greatsword','100','firelink_greatsword1.png',9,50),
(2, 'Fume Ultra Greatsword','100','fume_ultra_greatsword.png',7,90),
(1, 'Hollowslayer Greatsword','100','hollowslayer_greatsword-icon.png',4,10),
(9, 'Irithyll Straight Sword','100','IrithyllStraightSword.png',1,30),
(5, 'Longbow','100','Longbow',5,20),
(9, 'Longsword','100','longsword-icon.png',23,60),
(7, 'Mace','100','mace-icon.png',25,90),
(4, 'Mail Breaker','100','mail_breaker-icon.png',8,70),
(7, 'Morning Star','100','morning_star-icon.png',7,80),
(4, 'Parrying Dagger','100','parrying_dagger-icon.png',7,85),
(3, 'Priest\'s Chime','100','priests_chime-icon.png',23,45),
(5, 'Short Bow','100','short_bow-icon.png',1,20),
(6, 'Spear','100','spear-icon.png',9,65),
(3, 'Yorshka\'s Chime','100','Yorshkas_Chime_icon.png',14,35);

-- --------------------------------------------------------
-- Structure de la table user
-- valide permet de rendre actif le compte (exemple controle par email )


# Structure de la table `utilisateur`
DROP TABLE IF EXISTS users;

# <http://silex.sensiolabs.org/doc/2.0/providers/security.html#defining-a-custom-user-provider>
# Contenu de la table `utilisateur`

CREATE TABLE users (

  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL DEFAULT '',
  motdepasse VARCHAR(255) NOT NULL DEFAULT '',
  roles VARCHAR(255) NOT NULL DEFAULT 'ROLE_CLIENT',
  email  VARCHAR(255) NOT NULL DEFAULT '',
  isEnabled TINYINT(1) NOT NULL DEFAULT 1,

  nom varchar(255),
  code_postal varchar(255),
  ville varchar(255),
  adresse varchar(255),

  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# mot de passe crypté avec security.encoder.bcrypt

INSERT INTO users (id,username,password,motdepasse,email,roles) VALUES
(1, 'admin', '$2y$13$mJK5hyDNAY9rcDuEBofjJ.h3d7xBwlApfMoknBDO0AvXLr1AaJM02', 'admin', 'admin@gmail.com','ROLE_ADMIN'),
(2, 'invite', '$2y$13$j5rdj5QL3fd.IZlA5JNbc.kTRaa1YbJK/G7h2mB51ySzaDdgEbo8W', 'invite', 'admin@gmail.com','ROLE_INVITE'),
(3, 'vendeur', '$2y$13$/gwC0Iv6ssewrr9JeUDDuOcRTWD.uIEjJpH1HUWPAxe.5EwY98OEO','vendeur', 'vendeur@gmail.com','ROLE_VENDEUR'),
(4, 'client', '$2y$13$bhuMlUWdfc5mAhVumuKUG.etahlJ399DEwuQPhbdXjiCdKIeX2nii', 'client', 'client@gmail.com','ROLE_CLIENT'),
(5, 'client2', '$2y$13$SYEM3Tk/5G.C85pIAm0cSOd8BFrFTEnLHBSWsW96Q3k9gCdFXRmvm','client2', 'client2@gmail.com','ROLE_CLIENT');



-- --------------------------------------------------------
-- Structure de la table commandes
CREATE TABLE IF NOT EXISTS commandes (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11)  UNSIGNED  NOT NULL,
  prix float(6,2) NOT NULL,
  date_achat  timestamp default CURRENT_TIMESTAMP,
  etat_id int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_commandes_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_commandes_etats FOREIGN KEY (etat_id) REFERENCES etats (id)
) DEFAULT CHARSET=utf8 ;



-- --------------------------------------------------------
-- Structure de la table paniers
CREATE TABLE IF NOT EXISTS paniers (
  id int(11) NOT NULL AUTO_INCREMENT,
  quantite int(11) NOT NULL,
  prix float(6,2) NOT NULL,
  dateAjoutPanier timestamp default CURRENT_TIMESTAMP,
  user_id int(11)  UNSIGNED  NOT NULL,
  produit_id int(11) NOT NULL,
  commande_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_paniers_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_paniers_produits FOREIGN KEY (produit_id) REFERENCES produits (id),
  CONSTRAINT fk_paniers_commandes FOREIGN KEY (commande_id) REFERENCES commandes (id)
) DEFAULT CHARSET=utf8 ;

