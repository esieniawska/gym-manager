<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906081703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gym_pass (id UUID NOT NULL, buyer_card_number VARCHAR(255) NOT NULL, start_date DATE NOT NULL, dtype VARCHAR(255) NOT NULL, end_date DATE DEFAULT NULL, lock_start_date DATE DEFAULT NULL, lock_end_date DATE DEFAULT NULL, number_of_entries INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN gym_pass.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN gym_pass.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN gym_pass.lock_start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN gym_pass.lock_end_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gym_pass');
    }
}
