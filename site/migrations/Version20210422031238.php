<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210422031238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_lignes_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, produit INTEGER DEFAULT NULL, utilisateur INTEGER NOT NULL, quantite INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_5AB9284A29A5EC27 ON im2021_lignes_panier (produit)');
        $this->addSql('CREATE INDEX IDX_5AB9284A1D1C63B3 ON im2021_lignes_panier (utilisateur)');
        $this->addSql('CREATE UNIQUE INDEX usr_prod ON im2021_lignes_panier (utilisateur, produit)');
        $this->addSql('CREATE TABLE im2021_produits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite_stock INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL, motdepasse VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, isadmin BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD1761C90409EC ON im2021_utilisateurs (identifiant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im2021_lignes_panier');
        $this->addSql('DROP TABLE im2021_produits');
        $this->addSql('DROP TABLE im2021_utilisateurs');
    }
}
