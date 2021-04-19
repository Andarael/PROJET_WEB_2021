<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419024731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_panier (pk_utilisateurs INTEGER NOT NULL, code_produit INTEGER NOT NULL, qte_commande INTEGER NOT NULL, PRIMARY KEY(pk_utilisateurs, code_produit))');
        $this->addSql('CREATE TABLE im2021_produits (code_produit INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, prix_u DOUBLE PRECISION DEFAULT NULL, qte_stock INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, indentifiant VARCHAR(30) NOT NULL --sert de login (doit etre unique)
        , motdepasse VARCHAR(64) NOT NULL --mot de passe crypté
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, isadmin BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29DD176145963D75 ON im2021_utilisateurs (indentifiant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im2021_panier');
        $this->addSql('DROP TABLE im2021_produits');
        $this->addSql('DROP TABLE im2021_utilisateurs');
    }
}
