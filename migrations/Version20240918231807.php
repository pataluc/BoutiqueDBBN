<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918231807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vente AS SELECT id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook FROM vente');
        $this->addSql('DROP TABLE vente');
        $this->addSql('CREATE TABLE vente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, beneficiaire_id INTEGER NOT NULL, vendeur_id INTEGER DEFAULT NULL, date_paiement DATE NOT NULL, date_livraison DATE DEFAULT NULL, date_controle DATE DEFAULT NULL, montant_total NUMERIC(5, 2) NOT NULL, commentaire CLOB DEFAULT NULL, details_webhook CLOB DEFAULT NULL, CONSTRAINT FK_888A2A4C5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_888A2A4C858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO vente (id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook) SELECT id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook FROM __temp__vente');
        $this->addSql('DROP TABLE __temp__vente');
        $this->addSql('CREATE INDEX IDX_888A2A4C5AF81F68 ON vente (beneficiaire_id)');
        $this->addSql('CREATE INDEX IDX_888A2A4C858C065E ON vente (vendeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vente AS SELECT id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook FROM vente');
        $this->addSql('DROP TABLE vente');
        $this->addSql('CREATE TABLE vente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, beneficiaire_id INTEGER NOT NULL, date_paiement DATE NOT NULL, date_livraison DATE DEFAULT NULL, date_controle DATE DEFAULT NULL, montant_total NUMERIC(5, 2) NOT NULL, commentaire CLOB DEFAULT NULL, details_webhook CLOB DEFAULT NULL, CONSTRAINT FK_888A2A4C5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO vente (id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook) SELECT id, beneficiaire_id, date_paiement, date_livraison, date_controle, montant_total, commentaire, details_webhook FROM __temp__vente');
        $this->addSql('DROP TABLE __temp__vente');
        $this->addSql('CREATE INDEX IDX_888A2A4C5AF81F68 ON vente (beneficiaire_id)');
    }
}
