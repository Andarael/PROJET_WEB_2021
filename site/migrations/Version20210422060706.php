<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422060706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX usr_prod');
        $this->addSql('DROP INDEX IDX_5AB9284A1D1C63B3');
        $this->addSql('DROP INDEX IDX_5AB9284A29A5EC27');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_lignes_panier AS SELECT id, produit, utilisateur, quantite FROM im2021_lignes_panier');
        $this->addSql('DROP TABLE im2021_lignes_panier');
        $this->addSql('CREATE TABLE im2021_lignes_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit INTEGER DEFAULT NULL, utilisateur INTEGER NOT NULL, quantite INTEGER DEFAULT NULL, CONSTRAINT FK_5AB9284A29A5EC27 FOREIGN KEY (produit) REFERENCES im2021_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5AB9284A1D1C63B3 FOREIGN KEY (utilisateur) REFERENCES im2021_utilisateurs (pk) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) SELECT id, produit, utilisateur, quantite FROM __temp__im2021_lignes_panier');
        $this->addSql('DROP TABLE __temp__im2021_lignes_panier');
        $this->addSql('CREATE UNIQUE INDEX usr_prod ON im2021_lignes_panier (utilisateur, produit)');
        $this->addSql('CREATE INDEX IDX_5AB9284A1D1C63B3 ON im2021_lignes_panier (utilisateur)');
        $this->addSql('CREATE INDEX IDX_5AB9284A29A5EC27 ON im2021_lignes_panier (produit)');
        $this->addSql('DROP INDEX UNIQ_29DD1761C90409EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, motdepasse VARCHAR(64) NOT NULL COLLATE BINARY --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(30) DEFAULT NULL COLLATE BINARY, anniversaire DATE DEFAULT NULL, identifiant VARCHAR(30) NOT NULL, isadmin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin) SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_5AB9284A29A5EC27');
        $this->addSql('DROP INDEX IDX_5AB9284A1D1C63B3');
        $this->addSql('DROP INDEX usr_prod');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_lignes_panier AS SELECT id, produit, utilisateur, quantite FROM im2021_lignes_panier');
        $this->addSql('DROP TABLE im2021_lignes_panier');
        $this->addSql('CREATE TABLE im2021_lignes_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit INTEGER DEFAULT NULL, utilisateur INTEGER NOT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO im2021_lignes_panier (id, produit, utilisateur, quantite) SELECT id, produit, utilisateur, quantite FROM __temp__im2021_lignes_panier');
        $this->addSql('DROP TABLE __temp__im2021_lignes_panier');
        $this->addSql('CREATE INDEX IDX_5AB9284A29A5EC27 ON im2021_lignes_panier (produit)');
        $this->addSql('CREATE INDEX IDX_5AB9284A1D1C63B3 ON im2021_lignes_panier (utilisateur)');
        $this->addSql('CREATE UNIQUE INDEX usr_prod ON im2021_lignes_panier (utilisateur, produit)');
        $this->addSql('DROP INDEX UNIQ_29DD1761C90409EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, motdepasse VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, identifiant VARCHAR(30) NOT NULL COLLATE BINARY, isadmin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin) SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }
}
