<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221191424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE renting DROP FOREIGN KEY FK_13533C0F545317D1');
        $this->addSql('ALTER TABLE renting DROP FOREIGN KEY FK_13533C0FA76ED395');
        $this->addSql('ALTER TABLE renting ADD vehicle_reference VARCHAR(255) NOT NULL, CHANGE vehicle_id vehicle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE renting ADD CONSTRAINT FK_13533C0F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE renting ADD CONSTRAINT FK_13533C0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE vehicle CHANGE name title VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle CHANGE title name VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE renting DROP FOREIGN KEY FK_13533C0FA76ED395');
        $this->addSql('ALTER TABLE renting DROP FOREIGN KEY FK_13533C0F545317D1');
        $this->addSql('ALTER TABLE renting DROP vehicle_reference, CHANGE vehicle_id vehicle_id INT NOT NULL');
        $this->addSql('ALTER TABLE renting ADD CONSTRAINT FK_13533C0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE renting ADD CONSTRAINT FK_13533C0F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
