<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420231057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_lignes_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit INTEGER NOT NULL, utilisateur INTEGER NOT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_5AB9284A29A5EC27 ON im2021_lignes_panier (produit)');
        $this->addSql('CREATE INDEX IDX_5AB9284A1D1C63B3 ON im2021_lignes_panier (utilisateur)');
        $this->addSql('CREATE UNIQUE INDEX usr_prod ON im2021_lignes_panier (utilisateur, produit)');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP INDEX UNIQ_29DD1761C90409EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, motdepasse VARCHAR(64) NOT NULL COLLATE BINARY --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(30) DEFAULT NULL COLLATE BINARY, anniversaire DATE DEFAULT NULL, identifiant VARCHAR(30) NOT NULL, isadmin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin) SELECT pk, motdepasse, nom, prenom, anniversaire, identifiant, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_panier (produit_id INTEGER NOT NULL, utilisateur_id INTEGER NOT NULL, quantite INTEGER DEFAULT NULL, PRIMARY KEY(produit_id, utilisateur_id))');
        $this->addSql('CREATE INDEX IDX_21691B4FB88E14F ON ligne_panier (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_21691B4F347EFB ON ligne_panier (produit_id)');
        $this->addSql('DROP TABLE im2021_lignes_panier');
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
