<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423041618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE billetera (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, saldo DOUBLE PRECISION NOT NULL, estado VARCHAR(1) NOT NULL COMMENT 'A:activo|I:inactivo', fecha_creacion DATETIME NOT NULL, UNIQUE INDEX UNIQ_B437632BDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, documento VARCHAR(20) NOT NULL, nombres VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, celular VARCHAR(20) NOT NULL, estado VARCHAR(1) NOT NULL COMMENT 'A:activo|I:inactivo', fecha_creacion DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transaccion (id INT AUTO_INCREMENT NOT NULL, billetera_id INT NOT NULL, tipo VARCHAR(1) NOT NULL COMMENT 'I:Ingreso|E:egreso', descripcion VARCHAR(255) DEFAULT NULL, monto DOUBLE PRECISION NOT NULL, codigo_confirmacion VARCHAR(6) DEFAULT NULL, sesion_id VARCHAR(255) DEFAULT NULL, estado VARCHAR(1) NOT NULL COMMENT 'P:pendiente|C:confirmado|A:anulado', fecha_creacion DATETIME NOT NULL, fecha_confirmacion DATETIME DEFAULT NULL, fecha_anulacion DATETIME DEFAULT NULL, INDEX IDX_BFF96AF7A434F1EE (billetera_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE billetera ADD CONSTRAINT FK_B437632BDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaccion ADD CONSTRAINT FK_BFF96AF7A434F1EE FOREIGN KEY (billetera_id) REFERENCES billetera (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE billetera DROP FOREIGN KEY FK_B437632BDE734E51
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaccion DROP FOREIGN KEY FK_BFF96AF7A434F1EE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE billetera
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cliente
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transaccion
        SQL);
    }
}
