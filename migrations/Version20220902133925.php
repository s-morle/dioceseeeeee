<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220902133925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immobilier DROP user_id');
        $this->addSql('ALTER TABLE bien_immobilier ADD CONSTRAINT FK_D1BE34E1A832C1C9 FOREIGN KEY (email_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D1BE34E1A832C1C9 ON bien_immobilier (email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immobilier DROP FOREIGN KEY FK_D1BE34E1A832C1C9');
        $this->addSql('DROP INDEX IDX_D1BE34E1A832C1C9 ON bien_immobilier');
        $this->addSql('ALTER TABLE bien_immobilier ADD user_id INT NOT NULL');
    }
}
