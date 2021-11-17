<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211113112948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial DB setup';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== "mysql");

        $this->addSql("CREATE TABLE IF NOT EXISTS vehicle (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            identifier VARCHAR(255) NOT NULL,
            type ENUM('scooter'),
            status ENUM('available', 'occupied', 'unavailable', 'reserved'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (identifier),
            INDEX idx_vehicle__status_type (status, type)
            ) ENGINE=InnoDB;"
        );

        $this->addSql("CREATE TABLE IF NOT EXISTS vehicle_location (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            vehicle_id BIGINT NOT NULL,
            location POINT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE CASCADE,
            UNIQUE (vehicle_id)
            ) ENGINE=InnoDB;"
        );

        $this->addSql("CREATE TABLE IF NOT EXISTS api_user (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            description VARCHAR(255) NOT NULL,
            api_key VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (api_key)
            ) ENGINE=InnoDB;"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS vehicle_location');
        $this->addSql('DROP TABLE IF EXISTS vehicle');
        $this->addSql('DROP TABLE IF EXISTS api_user');
    }
}
