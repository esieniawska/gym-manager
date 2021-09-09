<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909135631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gym_entering (id UUID NOT NULL, gym_pass_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F92A1E48269E07 ON gym_entering (gym_pass_id)');
        $this->addSql('COMMENT ON COLUMN gym_entering.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE gym_entering ADD CONSTRAINT FK_7F92A1E48269E07 FOREIGN KEY (gym_pass_id) REFERENCES gym_pass (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gym_entering');
    }
}
