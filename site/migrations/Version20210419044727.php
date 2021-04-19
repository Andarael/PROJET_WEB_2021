<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419044727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_24CC0DF2CD11A2CF');
        $this->addSql('DROP INDEX IDX_24CC0DF21E969C5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT utilisateurs_id, produits_id, qte_commande FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (utilisateurs_id INTEGER NOT NULL, produits_id INTEGER NOT NULL, qte_commande INTEGER NOT NULL, PRIMARY KEY(utilisateurs_id, produits_id), CONSTRAINT FK_24CC0DF21E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES im2021_utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_24CC0DF2CD11A2CF FOREIGN KEY (produits_id) REFERENCES im2021_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier (utilisateurs_id, produits_id, qte_commande) SELECT utilisateurs_id, produits_id, qte_commande FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF2CD11A2CF ON panier (produits_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF21E969C5 ON panier (utilisateurs_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_24CC0DF21E969C5');
        $this->addSql('DROP INDEX IDX_24CC0DF2CD11A2CF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT utilisateurs_id, produits_id, qte_commande FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateurs_id INTEGER NOT NULL, produits_id INTEGER NOT NULL, qte_commande INTEGER NOT NULL)');
        $this->addSql('INSERT INTO panier (utilisateurs_id, produits_id, qte_commande) SELECT utilisateurs_id, produits_id, qte_commande FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF21E969C5 ON panier (utilisateurs_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2CD11A2CF ON panier (produits_id)');
    }
}
