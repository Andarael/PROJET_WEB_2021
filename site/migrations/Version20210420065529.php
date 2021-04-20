<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420065529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_produits AS SELECT id, libelle, prix, quantite_stock FROM im2021_produits');
        $this->addSql('DROP TABLE im2021_produits');
        $this->addSql('CREATE TABLE im2021_produits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL COLLATE BINARY, quantite_stock INTEGER NOT NULL, prix DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) SELECT id, libelle, prix, quantite_stock FROM __temp__im2021_produits');
        $this->addSql('DROP TABLE __temp__im2021_produits');
        $this->addSql('DROP INDEX UNIQ_29DD1761C90409EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, motdepasse, nom, prenom, anniversaire, isadmin, identifiant FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, motdepasse VARCHAR(64) NOT NULL COLLATE BINARY --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(30) DEFAULT NULL COLLATE BINARY, anniversaire DATE DEFAULT NULL, isadmin BOOLEAN NOT NULL, identifiant VARCHAR(30) NOT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, isadmin, identifiant) SELECT pk, motdepasse, nom, prenom, anniversaire, isadmin, identifiant FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_produits AS SELECT id, libelle, prix, quantite_stock FROM im2021_produits');
        $this->addSql('DROP TABLE im2021_produits');
        $this->addSql('CREATE TABLE im2021_produits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, quantite_stock INTEGER NOT NULL, prix NUMERIC(10, 2) NOT NULL)');
        $this->addSql('INSERT INTO im2021_produits (id, libelle, prix, quantite_stock) SELECT id, libelle, prix, quantite_stock FROM __temp__im2021_produits');
        $this->addSql('DROP TABLE __temp__im2021_produits');
        $this->addSql('DROP INDEX UNIQ_29DD1761C90409EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk COMMENT \'This is a column comment\', motdepasse VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, identifiant VARCHAR(30) NOT NULL COLLATE BINARY, isadmin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin) SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }
}
