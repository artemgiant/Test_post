<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190730094542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, country_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, region_oblast VARCHAR(255) NOT NULL, region_rayon VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, is_my_address TINYINT(1) DEFAULT NULL, zip VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house VARCHAR(255) NOT NULL, apartment VARCHAR(255) DEFAULT NULL, user_first_name VARCHAR(255) NOT NULL, user_last_name VARCHAR(255) NOT NULL, user_second_name VARCHAR(255) DEFAULT NULL, alias_of_address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, is_confirmed TINYINT(1) NOT NULL, INDEX IDX_6FCA7516A76ED395 (user_id), INDEX IDX_6FCA7516F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Countryes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_price (id INT AUTO_INCREMENT NOT NULL, cost DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, is_paid TINYINT(1) NOT NULL, INDEX IDX_6A2F2F958D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, order_status INT DEFAULT NULL, tracking_number VARCHAR(255) DEFAULT NULL, volume_weigth VARCHAR(255) DEFAULT NULL, declareValue VARCHAR(255) DEFAULT NULL, send_from_address VARCHAR(512) DEFAULT NULL, send_from_index VARCHAR(15) DEFAULT NULL, send_from_city VARCHAR(255) DEFAULT NULL, send_from_phone VARCHAR(255) DEFAULT NULL, send_from_email VARCHAR(255) DEFAULT NULL, send_detail_places INT DEFAULT NULL, send_detail_weight DOUBLE PRECISION DEFAULT NULL, send_detail_length DOUBLE PRECISION DEFAULT NULL, send_detail_width DOUBLE PRECISION DEFAULT NULL, send_detail_height DOUBLE PRECISION DEFAULT NULL, comment LONGTEXT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, shipping_costs DOUBLE PRECISION DEFAULT NULL, delivery_status VARCHAR(255) DEFAULT NULL, ship_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, country_code VARCHAR(256) DEFAULT NULL, country VARCHAR(256) DEFAULT NULL, from_country VARCHAR(256) DEFAULT NULL, city VARCHAR(256) DEFAULT NULL, zip VARCHAR(256) DEFAULT NULL, towarehouse VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, number VARCHAR(512) DEFAULT NULL, company_send_to_usa VARCHAR(512) DEFAULT NULL, system_number_to_usa VARCHAR(512) DEFAULT NULL, company_send_in_usa VARCHAR(512) DEFAULT NULL, system_number_in_usa VARCHAR(512) DEFAULT NULL, account_country VARCHAR(20) DEFAULT NULL, admin_create TINYINT(1) DEFAULT \'0\', INDEX IDX_E52FFDEEA76ED395 (user_id), INDEX IDX_E52FFDEEF5B7AF75 (address_id), INDEX IDX_E52FFDEEB88F75C9 (order_status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_products (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, desc_en LONGTEXT DEFAULT NULL, desc_ua LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, count INT DEFAULT NULL, total_summ DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5242B8EB8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_statuses (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) DEFAULT NULL, status_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_liq_pay (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, invoice INT DEFAULT NULL, number VARCHAR(512) DEFAULT NULL, sum VARCHAR(512) DEFAULT NULL, status VARCHAR(512) DEFAULT NULL, first_name VARCHAR(512) DEFAULT NULL, last_name VARCHAR(512) DEFAULT NULL, phone_number VARCHAR(512) DEFAULT NULL, created_at DATETIME NOT NULL, liqpay_order_id VARCHAR(512) DEFAULT NULL, liqpay_info LONGTEXT DEFAULT NULL, INDEX IDX_DE39B63AA76ED395 (user_id), INDEX IDX_DE39B63A90651744 (invoice), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, second_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL, last_login DATETIME DEFAULT NULL, is_suspended TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516F92F3E70 FOREIGN KEY (country_id) REFERENCES Countryes (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F958D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEF5B7AF75 FOREIGN KEY (address_id) REFERENCES addresses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEB88F75C9 FOREIGN KEY (order_status) REFERENCES order_statuses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EB8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE transaction_liq_pay ADD CONSTRAINT FK_DE39B63AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE transaction_liq_pay ADD CONSTRAINT FK_DE39B63A90651744 FOREIGN KEY (invoice) REFERENCES invoices (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEF5B7AF75');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516F92F3E70');
        $this->addSql('ALTER TABLE transaction_liq_pay DROP FOREIGN KEY FK_DE39B63A90651744');
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F958D9F6D38');
        $this->addSql('ALTER TABLE order_products DROP FOREIGN KEY FK_5242B8EB8D9F6D38');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEB88F75C9');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516A76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE transaction_liq_pay DROP FOREIGN KEY FK_DE39B63AA76ED395');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE Countryes');
        $this->addSql('DROP TABLE delivery_price');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_products');
        $this->addSql('DROP TABLE order_statuses');
        $this->addSql('DROP TABLE transaction_liq_pay');
        $this->addSql('DROP TABLE user');
    }
}
