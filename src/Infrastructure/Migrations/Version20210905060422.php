<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905060422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, order_item_id UUID NOT NULL, buyer_card_number VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, start_date DATE NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "order".start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "order".created_at IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "order"');
    }
}
