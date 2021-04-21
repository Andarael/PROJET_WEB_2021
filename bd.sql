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
INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin) VALUES (4, '25ce1ae8a81b3b761319abcffe99c54ac07acbfb', 'Josué', 'Raad', '1997-08-13', 'Jojo', 0);

create table im2021_produits
(
    id             INTEGER          not null
        primary key autoincrement,
    libelle        VARCHAR(255)     not null,
    prix           DOUBLE PRECISION not null,
    quantite_stock INTEGER          not null
);

INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (1, 'Chocolat', 3.99, 13);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (2, 'Bananes', 5.99, 17);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (3, 'Pâtes', 1.99, 7);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (4, 'Pommes', 0.99, 14);
INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) VALUES (5, 'oranges', 1.49, 12);

create table im2021_lignes_panier
(
    id          INTEGER not null
        primary key autoincrement,
    produit     INTEGER not null,
    utilisateur INTEGER not null,
    quantite    INTEGER default NULL
);

create index IDX_5AB9284A1D1C63B3
    on im2021_lignes_panier (utilisateur);

create index IDX_5AB9284A29A5EC27
    on im2021_lignes_panier (produit);

create unique index usr_prod
    on im2021_lignes_panier (utilisateur, produit);

INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (34, 1, 2, 3);
INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (35, 2, 2, 2);
INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (36, 3, 2, 2);
INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) VALUES (37, 4, 2, 4);