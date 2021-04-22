create table im2021_utilisateurs
(
    pk           INTEGER     not null
        primary key autoincrement,
    motdepasse   VARCHAR(64) not null,
    nom          VARCHAR(30) default NULL,
    prenom       VARCHAR(30) default NULL,
    anniversaire DATE        default NULL,
    identifiant  VARCHAR(30) not null,
    isadmin      BOOLEAN     not null
);

create unique index UNIQ_29DD1761C90409EC
    on im2021_utilisateurs (identifiant);

INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin) VALUES (1, 'a4cbb2f3933c5016da7e83fd135ab8a48b67bf61', null, null, null, 'admin', 1);
INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin) VALUES (2, 'ab9240da95937a0d51b41773eafc8ccb8e7d58b5', 'Subrenat', 'Gilles', '2000-01-01', 'gilles', 0);
INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin) VALUES (3, '1811ed39aa69fa4da3c457bdf096c1f10cf81a9b', 'Zrour', 'Rita', '2001-01-02', 'rita', 0);

create table im2021_produits
(
    id             INTEGER          not null
        primary key autoincrement,
    libelle        VARCHAR(255)     not null,
    prix           DOUBLE PRECISION not null,
    quantite_stock INTEGER          not null
);

INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (1, 'Nvidia RTX 3090 24G', 2759.95, 1);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (2, 'Nvidia RTX 3080 FE', 719.99, 0);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (3, 'Nvidia RTX 3080', 1579.94, 1);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (4, 'AMD RX 6800XT', 1399.94, 0);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (5, 'AMD RX 6800', 1199.95, 0);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (6, 'Nvidia gt 1030', 122.95, 3);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (7, 'Nvidia RTX 3060ti', 929.95, 0);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (8, 'Mémoire DDR5 13.5 GB', 123.95, 11);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (9, 'Mémoire DDR 4 (8gb)', 80.95, 16);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (10, 'Carte mère x79 (2011)', 115.99, 35);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (11, 'Processeur athlon celeron pentium III', 35, 10);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (12, 'intel ryzen core i11', 549.99, 2);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (13, 'Disque dur 4TB', 125.49, 18);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (14, 'Disque mou (SSD) 500 GB', 125.49, 14);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (15, 'Alimentation 750W', 89.95, 9);

create table im2021_lignes_panier
(
    id          INTEGER not null
        primary key autoincrement,
    produit     INTEGER default NULL
        constraint FK_5AB9284A29A5EC27
            references im2021_produits,
    utilisateur INTEGER not null
        constraint FK_5AB9284A1D1C63B3
            references im2021_utilisateurs,
    quantite    INTEGER default NULL
);

create index IDX_5AB9284A1D1C63B3
    on im2021_lignes_panier (utilisateur);

create index IDX_5AB9284A29A5EC27
    on im2021_lignes_panier (produit);

create unique index usr_prod
    on im2021_lignes_panier (utilisateur, produit);

INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (13, 14, 2, 1);
INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (14, 8, 2, 2);