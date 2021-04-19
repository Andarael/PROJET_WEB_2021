<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419044835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_panier (utilisateurs_id INTEGER NOT NULL, produits_id INTEGER NOT NULL, qte_commande INTEGER NOT NULL, PRIMARY KEY(utilisateurs_id, produits_id))');
        $this->addSql('CREATE INDEX IDX_2129058F1E969C5 ON im2021_panier (utilisateurs_id)');
        $this->addSql('CREATE INDEX IDX_2129058FCD11A2CF ON im2021_panier (produits_id)');
        $this->addSql('DROP TABLE panier');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (utilisateurs_id INTEGER NOT NULL, produits_id INTEGER NOT NULL, qte_commande INTEGER NOT NULL, PRIMARY KEY(utilisateurs_id, produits_id))');
        $this->addSql('CREATE INDEX IDX_24CC0DF21E969C5 ON panier (utilisateurs_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2CD11A2CF ON panier (produits_id)');
        $this->addSql('DROP TABLE im2021_panier');
    }
}
